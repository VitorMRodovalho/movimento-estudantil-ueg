<?php
	/**
	 *	online_estagio Component for Mambo 4.5
	**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

DEFINE( 'SPLITCHAR', ';' );


function getJobTypeString( $jobtype ) {
	$ret = "";
	switch ( $jobtype ) {
		case '0': $ret = _JL_JOBTYPE_FULLTIME; break;
		case '1': $ret = _JL_JOBTYPE_PARTTIME; break;
		case '2': $ret = _JL_JOBTYPE_INTERNSHIP; break;
	}
	return $ret;
}



/**
 * Send an email
 * @param string email Email adress
 * @param string subject Subject of the email
 * @param string message The message body of the email
 * @param string fromname Name of the sender of the email
 * @param string fromemail Email address of the sender
 * @param string replyto Email address in Reply-To header
 */
function sendEmail( $email, $subject, $message, $fromname='', $fromemail='', $replyto='') {
	if ( function_exists( "mosMail" ) ) {
		mosMail($fromemail, $fromname, $email, $subject, $message);
	} else {
		$headers = "";
		if ( trim( $fromemail ) ) {
			$headers = "From: $fromname <$fromemail>\r\n";
		}
		if ( trim( $replyto ) ) {
			$headers .= "Reply-To: <$replyto>\r\n";
		}
		$headers .= "X-Priority: 3\r\n";
		$headers .= "X-MSMail-Priority: Low\r\n";
		$headers .= "X-Mailer: Mambo Open Source 4.5\r\n";
		@mail($email, $subject, $message, $headers);
	}
}

/**
 * Show an error message.
 *
 * @param string The error message to show.
 * @param string Heading for the error message.
 */
function showError( $error, $error_header = _JL_ERROR ) {
	HTML_online_estagio::showError( $error, $error_header );
}

/**
 * Return an ORDER BY value based on the given string
 *
 * @param string What kind of order by statement to use
 */
function getOrdering( $sort ) {
	switch ( $sort ) {
		case "titledesc": $ordering = "c.title DESC"; break;
		case "titleasc": $ordering = "c.title ASC"; break;
		case "orderingdesc": $ordering = "c.ordering DESC"; break;
		case "orderingasc": $ordering = "c.ordering ASC"; break;
		case "jobiddesc": $ordering = "c.id DESC"; break;
		case "jobidasc": $ordering = "c.id ASC"; break;
		case "createddesc": $ordering = "c.created DESC"; break;
		case "createdasc": $ordering = "c.created ASC"; break;
		case "companydesc": $ordering = "c.company DESC"; break;
		case "companyasc": $ordering = "c.company ASC"; break;
		case "referencedesc": $ordering = "c.reference DESC"; break;
		case "referenceasc": $ordering = "c.reference ASC"; break;
		case "locationdesc": $ordering = "c.location DESC"; break;
		case "locationasc": $ordering = "c.location ASC"; break;
		case "durationdesc": $ordering = "c.duration DESC"; break;
		case "durationasc": $ordering = "c.duration ASC"; break;
		default: $ordering = "c.id DESC";
	}
	return $ordering;
}

function getSortArg( $type, $sort ) {
	$mat = array();
	if ( preg_match( "/(\w+)(asc|desc)/i", $sort, $mat ) ) {
		if ( $type == $mat[1] ) {
			return ( $mat[2] == "asc" ) ? "{$type}desc" : "{$type}asc";
		} else {
			return $type . $mat[2];
		}
	}
	return "jobiddesc";
}

/**
 * Make sure we can use the file_get_contents function
 *
 * @param string Filename to return contents of
 */
if (!function_exists('file_get_contents')) {
   function file_get_contents($file) {
       $v = file($file);
       return ($v) ? implode('', $v) : false;
   }
}

/**
 * Split a string by SPLITCHAR and trim each element.
 */
function explodeTrim( $string ) {
	$arr = explode( SPLITCHAR, $string );
	$count = count( $arr );
	for ( $i=0; $i < $count; $i++ ) {
		$arr[$i] = trim( $arr[$i] );
	}
	return $arr;
}

/**
 * Return an array of extra fields to use for the items.
 */
function getExtraFields() {
	global $cfgjl;

	return explodeTrim( $cfgjl['extrafields'] );
}

/**
 * Return an array of arrays containing the keys available for each item.
 */
function getKeywordSets() {
	global $cfgjl;

	$keysets = mosParseParams( $cfgjl['keywordsets'] );
	$keysets = get_object_vars( $keysets );
	foreach ( $keysets as $key => $set ) {
		$set = explodeTrim( $set );
		$keysets[$key] = $set;
	}
	return $keysets;
}

/**
 * Cleanup a string and replace any odd characters with a similar equivalent.
 * 
 * @param string String to cleanup.
 * @return string A cleaned-up version of the string
 */
function cleanString( $string ) {
	// First we'll replace some characters with accents with their alphabetical counterparts.
	strtr($string, "\x{0160}\x{0152}\x{017D}\x{0161}\x{0153}\x{017E}\x{0178}¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
	// Now we'll remove any unwanted characters that are left.
	$set = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_";
	$first = strtr( $string, $set,
                    str_repeat("#", strlen($set)) );
	$second = strtr( $string, $first,
					 str_repeat("_", strlen($first)) );
	return $second;
}

/**
 * Wrapper for mosFormatDate so that it works with an empty format string as well.
 *
 * @param string Date to format
 * @param format How to format the date, uses on strftime() syntax, leave empty to use default language file date format
 * @return string Formatted date string.
 */
function mxFormatDate( $date, $format=_DATE_FORMAT_LC ) {
	if ( trim( $format ) ) {
		return mosFormatDate( $date, $format );
	}
	return mosFormatDate( $date );
}

/**
 * Return a text string with all Extra Fields and Keyword sets as a string that
 * can be parsed by mosParseParams, presumably to be saved in the attribs db field.
 *
 * @return string A string with key=value pairs separated by newline.
 */
function getExtraAttribs() {
	// Save the extra fields
	$attribs = "";
	$extrafields = getExtraFields();
	foreach ( $extrafields as $f ) {
		$f = cleanString( $f );
		if ( isset( $_REQUEST[$f] ) ) {
			$attribs .= "$f=" . trim( $_REQUEST[$f] ) . "\n";
		}
	}
	// Find all sets of available keywords.
	$keysets = getKeywordSets();
	foreach ( $keysets as $k => $v ) {
		$k = cleanString( $k );
		if ( isset( $_REQUEST[$k] ) ) {
			$attribs .= "$k=" . trim( $_REQUEST[$k] ) . "\n";
		}
	}
	return $attribs;
}


/* **** Mambo functions that might not exist in the backend for some reason. **** */

/**
* Mambo function to log searches.
*/
if ( !function_exists( "mosLogSearch" ) ) {
	function mosLogSearch( $search_term ) {
		global $database;
		global $mosConfig_enable_log_searches;
		
		if (@$mosConfig_enable_log_searches) {
			$database->setQuery( "SELECT hits"
								 . "\nFROM #__core_log_searches"
								 . "\nWHERE LOWER(search_term)='$search_term'" );
			$hits = intval( $database->loadResult() );
			echo $database->getErrorMsg();
			if ($hits) {
				$database->setQuery( "UPDATE #__core_log_searches SET hits=(hits+1)"
									 . "\nWHERE LOWER(search_term)='$search_term'" );
				$database->query();
				echo $database->getErrorMsg();
			} else {
				$database->setQuery( "INSERT INTO #__core_log_searches VALUES"
									 . "\n('$search_term','1')" );
				$database->query();
				echo $database->getErrorMsg();
			}
		}
	}
}


/**
* Mambo function to parse parameters
*/
if ( !function_exists( 'mosParseParams' ) ) {
	function mosParseParams( $txt ) {
		$sep1 = "\n";	// line separator
		$sep2 = "=";	// key value separator
		
		$temp = explode( $sep1, $txt );
		$obj = new stdClass();
		// We use trim() to make sure a numeric that has spaces
		// is properly treated as a numeric
		foreach ($temp as $item) {
			if($item) {
				$temp2 = explode( $sep2, $item, 2 );
				$k = trim( $temp2[0] );
				if (isset( $temp2[1] )) {
					$obj->$k = trim( $temp2[1] );
				} else {
					$obj->$k = $k;
				}
			}
		}
		return $obj;
	}
}


/* Joomla wysiwyg editor functions */
/* ------------------------------- */

/**
 * Create place holder editor functions if they haven't been loaded by the template.
 *
 */

if ( !function_exists( "editorArea" ) ) {
	function editorArea( $name, $content, $hiddenField, $width, $height, $col, $row  ) {
		?>
	<textarea class="inputbox" name="<?php echo $hiddenField; ?>" id="<?php echo $hiddenField; ?>" cols="<?php echo $col; ?>" rows="<?php echo $row; ?>" style="width:<?php echo $width; ?>; height:<?php echo $height; ?>"><?php echo $content; ?></textarea>
        <?php
	}
}
if ( !function_exists( "initEditor" ) ) {
	function initEditor() {
	}
}
if ( !function_exists( "getEditorContents" ) ) {
	function getEditorContents( $editorArea, $hiddenField ) {
	}
}

function editorAreaJx( $name, $content, $hiddenField, $col, $row  ) {
	global $cfgjl;
	$editorwidth = intval( $cfgjl['editorwidth'] );
	$editorheight = intval( $cfgjl['editorheight'] );

	$content = "";
	if ( strtolower( $cfgjl['editor'] ) == '_jx_default' ) {
		ob_start();
		editorArea( $name, $content, $hiddenField, $editorwidth, $editorheight, $col, $row  );
		$content = ob_get_contents();
		ob_end_clean();
	} else {
		$content = "<textarea class='inputbox' name='$hiddenField' id='$hiddenField' cols='$col' rows='$row' style='width: $editorwidth; height: $editorheight'>$content</textarea>";
	}
	return $content;
}

function getEditorContentsJx( $editorArea, $hiddenField ) {
	global $cfgjl;

	$content = "";
	if ( strtolower( $cfgjl['editor'] ) == '_jx_default' ) {
		ob_start();
		getEditorContents( $editorArea, $hiddenField );
		$content = ob_get_contents();
		ob_end_clean();
	}
	return $content;
}

function initEditorJx() {
	global $mainframe, $cfgjl, $mosConfig_absolute_path;

	// Make sure editor script is called.
	if (!defined( '_MOS_EDITOR_INCLUDED' )) {
		include( "$mosConfig_absolute_path/editor/editor.php" );
	}

	// Make sure that the editor will be loaded, needed for Joomla 1.0.3
	$mainframe->set( 'loadEditor', true );

	// Only initialize editor if editor config option is set to default Joomla editor.
	$content = "";
	if ( strtolower( $cfgjl['editor'] ) == '_jx_default' ) {
		ob_start();
		initEditor();
		$content = ob_get_contents();
		ob_end_clean();
	}
	return $content;
}


?>

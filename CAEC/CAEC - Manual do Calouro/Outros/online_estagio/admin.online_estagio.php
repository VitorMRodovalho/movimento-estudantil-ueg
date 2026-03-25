<?php
//online_estagio Admin//


defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

//Get right Language file
if ( file_exists( "$mosConfig_absolute_path/components/$option/language/$mosConfig_lang.php" ) ) {
	include_once("$mosConfig_absolute_path/components/$option/language/$mosConfig_lang.php");
} else {
	include_once("$mosConfig_absolute_path/components/$option/language/english.php");
}

// Read html classes
require_once( $mainframe->getPath( 'front_html' ) );
require_once( $mainframe->getPath( 'admin_html' ) );

// Read database class information
require_once( $mainframe->getPath( 'class' ) );

// Read a file with common functions
require_once("$mosConfig_absolute_path/components/$option/online_estagio.common.php");

// Read a file containing the mxTemplate class
require_once("$mosConfig_absolute_path/components/$option/mxtemplate.php");

$cfgfile = "$mosConfig_absolute_path/components/$option/configuration.php";
include_once( $cfgfile );

$pid = mosGetParam( $_POST, 'pid', array(0) );
if (!is_array( $pid )) {
	$pid = array(0);
}


// Define which admin page to show.
switch ($task) {
	case "conf":
		showConfig();
		break;
	case "saveconf":
		saveConfig();
		break;
	case "new":
		editJobPosting( 0 );
		break;
	case "edit":
		editJobPosting( $pid[0], "list" );
		break;
	case "editqueue":
		editJobPosting( $pid[0], "queue" );
		break;
	case "save":
		saveJobPosting();
		break;
	case "cancel":
		cancelJobPosting();
		break;
	case "remove":
		removeJobPosting( $pid );
		break;
	case "publish":
		changeJobPosting( $pid, 1 );
		break;
	case "unpublish":
		changeJobPosting( $pid, 0 );
		break;
	case "orderup":
		orderJobPosting( $pid[0], -1 );
		break;
	case "orderdown":
		orderJobPosting( $pid[0], 1 );
		break;
	case "postjob":
		postJobPosting( $pid );
		break;
	case "rejectjob":
		rejectJobPosting( $pid );
		break;
	case "queue":
		listJobPostings( 1 );
		break;
	case "edittemplate":
		editTemplate( $pid[0] );
		break;
	case "savetemplate":
		saveTemplate();
		break;
	case "canceltemplate":
		cancelTemplate();
		break;
	case "listtemplates":
		listTemplates();
		break;
	case "list":
	default:
		listJobPostings();
}


/* ******************** Main functions ******************** */

function listTemplates() {
	global $database, $mosConfig_absolute_path, $option, $cfgfile, $cfgjl, $mainframe;

	$tmpldir = "$mosConfig_absolute_path/components/com_online_estagio/templates/{$cfgjl['template']}";
	// Find the available template files
	$templates = array();
	if ( $handle = opendir( $tmpldir ) ) {
		while ( false !== ( $file = readdir( $handle ) ) ) {
			if ( $file != "." && $file != ".." && substr( $file, -5 ) == ".tmpl" ) {
				if ( is_file( "$tmpldir/$file" ) ) {
					$templates[] = substr( $file, 0, -5 );
				}
			}
		}
	}

	HTML_online_estagio_admin::listTemplates( $templates );
}

function editTemplate( $template ) {
	global $database, $mosConfig_absolute_path, $option, $cfgfile, $cfgjl, $mainframe;

	$tmpldir = "$mosConfig_absolute_path/components/com_online_estagio/templates/{$cfgjl['template']}";
	if ( file_exists( "$tmpldir/$template.tmpl" ) ) {
		$template_content = file_get_contents( "$tmpldir/$template.tmpl" );
		$template_content = htmlspecialchars( $template_content );
		
		HTML_online_estagio_admin::editTemplate( $template, $template_content, "$tmpldir/$template.tmpl" );
	} else {
		showError( _JL_A_COULDNTREADTEMPLATE . " $tmpldir/$template.tmpl" );
	}
}

function saveTemplate() {
	global $database, $mosConfig_absolute_path, $mosConfig_live_site, $option, $cfgfile, $cfgjl, $mainframe;

	$template = mosGetParam( $_POST, 'template', '' );
	$tmplcontent = mosGetParam( $_POST, 'content', '', _MOS_ALLOWHTML );
	$tmplcontent = stripslashes( $tmplcontent );

	$tmpldir = "$mosConfig_absolute_path/components/com_online_estagio/templates/{$cfgjl['template']}";
	$tmplfile = "$tmpldir/$template.tmpl";
	@chmod( $tmplfile, 0766 );
	if ( !is_writable( $tmplfile ) ) {
		mosRedirect( "index2.php?option=$option&task=listtemplates", _JL_ERR_TMPL_NOT_WRITEABLE );
		exit;
	}

	if ( $fp = fopen( $tmplfile, "w" ) ) {
		fputs( $fp, $tmplcontent, strlen( $tmplcontent ) );
		fclose( $fp );
		mosRedirect( "index2.php?option=$option&task=listtemplates", _JL_A_TEMPLATE_SAVED );
	} else {
		mosRedirect( "index2.php?option=$option&task=listtemplates", _JL_ERR_OPEN_FILE );
	}
}

function cancelTemplate() {
	global $database, $mosConfig_absolute_path, $mosConfig_live_site, $option, $cfgfile, $cfgjl, $mainframe;

	mosRedirect( "index2.php?option=$option&task=listtemplates", _JL_A_CANCEL_TMPL );
}

function listJobPostings( $listtype = 0 ) {
	global $database, $mosConfig_absolute_path, $option, $cfgfile, $cfgjl, $mainframe;
	
	$category = null;
	$search = "";

	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', 10 );
	$limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart",
													   'limitstart', 0 );
	$levellimit = $mainframe->getUserStateFromRequest( "view{$option}limit",
													   'levellimit', 10 );

	$where = array();
	if ( $listtype == 1 ) {
		$where[] = "c.state = -2";
	} else {
		$where[] = "c.state >= 0";
	}
	if ($category) {
		$where[] = "catid='$category->id'";
	}
	if ($search) {
		$where[] = "LOWER(title) LIKE '%$search%'";
	}

	$database->setQuery( "SELECT COUNT(*) FROM #__jl_jobposting AS c"
	    . (count( $where ) ? "\nWHERE " . implode( ' AND ', $where ) : "")
    );
	$total = $database->loadResult();

	require_once( "includes/pageNavigation.php" );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

/*
	switch ( $cfgjl['adminlistorder'] ) {
		case "pricedesc": $ordering = "c.price DESC"; break;
		case "priceasc": $ordering = "c.price ASC"; break;
		case "orderingdesc": $ordering = "c.ordering DESC"; break;
		case "orderingasc": $ordering = "c.ordering ASC"; break;
		case "addressdesc": $ordering = "c.address DESC"; break;
		case "addressasc": $ordering = "c.address ASC"; break;
		case "createddesc": $ordering = "c.created DESC"; break;
		case "createdasc": $ordering = "c.created ASC"; break;
		default: $ordering = "c.id DESC";
	}
*/

	if ( $listtype == 1 ) {
		$ordering = "c.created DESC";
	} else {
		$ordering = "c.created DESC";
	}

	// Read job postings from db.
	$database->setQuery( "SELECT c.*, u.name AS editor"
		. "\nFROM #__jl_jobposting AS c"
		. "\nLEFT JOIN #__users AS u ON u.id = c.checked_out"
		// . "\nWHERE c.catid='$category->id' AND c.access<='$gid' $xwhere "
	    . (count( $where ) ? "\nWHERE " . implode( ' AND ', $where ) : "")
		. "\nORDER BY $ordering"
		. "\nLIMIT $limitstart, $limit"
	);
	$items = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->getQuery();
		echo $database->stderr();
		return false;
	}

	if ( $listtype == 1 ) {
		HTML_online_estagio_admin::listJobQueue( $items, $pageNav );
	} else {
		HTML_online_estagio_admin::listJobPostings( $items, $pageNav );
	}

}

/**
 * Edit an item, if no id is given, a new item will be created.
 * @param int id ID of the item to edit.
 */
function editJobPosting( $pid, $returnpage="list" ) {
	global $database, $mosConfig_absolute_path, $option, $cfgfile, $cfgjl, $mainframe;
	global $my, $_VERSION;

	$row = new mosJobPosting( $database );
	// load the row from the db table
	$row->load( $pid );

	// fail if checked out not by 'me'
	if ($row->checked_out && $row->checked_out <> $my->id) {
		mosRedirect( "index2.php?option=content",
		_JL_ERR_CHECKED_OUT1 . " $row->title " . _JL_ERR_CHECKED_OUT2 );
	}

	// Read the current template for the preview
	if ( $_VERSION->RELEASE >= "4.5" ) {
		$sql = "SELECT template FROM #__templates_menu WHERE client_id='0' AND menuid='0'";
	} else {
		$sql = "SELECT cur_template FROM #__templates";
	}
	$database->setQuery( $sql );
	$cur_template = $database->loadResult();

	$extrafields = getExtraFields();

	if ($pid) {
		$row->checkout( $my->id );
		if ( trim( $row->attribs ) ) {
			// Read all attributes from the item.
			$row->attribs = mosParseParams( $row->attribs );
			$row->attribs = get_object_vars( $row->attribs );
		} else {
			$row->attribs = array();
		}
	} else {
		$row->version = 0;
		$row->showcomp = 0;	
		$row->showcontact = 0;
		$row->ordering = 9999;
		$row->attribs = array();
		if ( $cfgjl['autoapprove'] ) {
			$row->state = 1;
		} else {
			$row->state = 0;
		}
	}

	// Set all unavailable attributes to empty string.
	foreach ( $extrafields as $f ) {
		$f = cleanString( $f );
		if ( !isset($row->attribs[$f]) ) {
			$row->attribs[$f] = "";
		}
	}

	// Find all sets of available keywords.
	$keysets = getKeywordSets();

	$keywords = array();
	foreach ( $keysets as $k => $v ) {
		$k = cleanString( $k );
		if ( isset( $row->attribs[$k] ) ) {
			$keywords[$k] = explodeTrim( $row->attribs[$k] );
		} else {
			$keywords[$k] = array();
		}
	}
	$row->keywords = $keywords;
	
	// make the select list for the job types
	$jobtypelist = array();
	$jobtypelist[] = mosHTML::makeOption( '0', _JL_JOBTYPE_FULLTIME );
	$jobtypelist[] = mosHTML::makeOption( '1', _JL_JOBTYPE_PARTTIME );
	$jobtypelist[] = mosHTML::makeOption( '2', _JL_JOBTYPE_INTERNSHIP );

	// make a standard yes/no list
	$yesno = array();
	$yesno[] = mosHTML::makeOption( '0', _JL_A_NO );
	$yesno[] = mosHTML::makeOption( '1', _JL_A_YES );


	// Set access restriction for job posting
	$access = array();
	$access[] = mosHTML::makeOption( '0', _JL_A_ACCESS_ALL );
	$access[] = mosHTML::makeOption( '1', _JL_A_ACCESS_REGISTERED );
	$access[] = mosHTML::makeOption( '2', _JL_A_ACCESS_USER );

	// Find out start and end year for the the expiration year drop-down
	$thisyear = date( "Y" );
	$lastyear = $thisyear + 10;

	// Add the html select options
	$lists = array();
	$lists['jobtype'] = mosHTML::selectList( $jobtypelist, 'jobtype',
											  'class="inputbox" size="1"', 'value', 'text', 
											  $row->jobtype );
	$lists['published'] = mosHTML::selectList( $yesno, 'state',
											  'class="inputbox" size="1"', 'value', 'text', 
											  $row->state );

	$lists['access'] = mosHTML::selectList( $access, 'access', 'class="inputbox" size="1"', 'value', 'text', $row->access );


	$lists['ccexpyear'] = mosHTML::integerSelectList( $thisyear, $lastyear, 1, 'creditcardexpyear', 'class="inputbox" size="1"', $row->creditcardexpyear );
	$lists['ccexpmon'] = mosHTML::monthSelectList( 'creditcardexpmon', 'class="inputbox" size="1"', $row->creditcardexpmon );


	HTML_online_estagio_admin::editJobPosting( $row, $lists, $cur_template, $returnpage );
}

function saveJobPosting() {
	global $database, $mosConfig_absolute_path, $option, $cfgfile, $cfgjl, $mainframe, $my;

	$row = new mosJobPosting( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if ($row->id) {
		$row->modified = date( "Y-m-d H:i:s" );
		$row->modified_by = $my->id;
	} else {
		$row->created = date( "Y-m-d H:i:s" );
		$row->created_by = $my->id;
	}

	$row->ordering = 99999;

	if ( !$row->showcontact ) {
		$row->showcontact = 0;
	}
	if ( !$row->showcomp ) {
		$row->showcomp = 0;
	}
	
	if ( !preg_match( "#^http://#i", $row->companyurl ) ) {
		$row->companyurl = "http://" . $row->companyurl;
	}


	// Save the extra fields
	$row->attribs = getExtraAttribs();
	
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->version++;
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	$row->updateOrder( "state >= 0" );

	$returnpage = mosGetParam( $_REQUEST, "returnpage", "" );
	$task = ( $returnpage == "queue" ) ? "queue" : "list";

	mosRedirect( "index2.php?option=$option&task=$task" );
}

/**
* Cancels an edit operation and checks in the item
*/
function cancelJobPosting() {
	global $database, $option;

	$row = new mosJobPosting( $database );
	$row->bind( $_POST );
	$row->checkin();

	$returnpage = mosGetParam( $_REQUEST, "returnpage", "" );
	$task = ( $returnpage == "queue" ) ? "queue" : "list";

	mosRedirect( "index2.php?option=$option&task=$task" );
}

/**
* Removes the given items from the database
* @param pid An array of ids to delete
*/
function removeJobPosting( &$pid ) {
	global $database, $option, $mosConfig_absolute_path;

	if (count( $pid ) < 1) {
		echo "<script> alert('" . _JL_A_SELECTDELITEM . "'); window.history.go(-1);</script>\n";
		exit;
	}

	$pids = implode( ',', $pid );

	$database->setQuery( "DELETE FROM #__jl_jobposting WHERE id IN ($pids)" );
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
	}

	mosRedirect( "index2.php?option=$option" );
}

/**
* Changes the state of one or more items
* @param integer A unique item id (passed from an edit form)
* @param integer 0 if unpublishing, 1 if publishing
*/
function changeJobPosting( $pid=null, $state=0 ) {
	global $database, $option;

	if (count( $pid ) < 1) {
		$action = $state == 1 ? 'publish' : ($state == -1 ? 'archive' : 'unpublish');
		echo "<script> alert('" . _JL_A_SELECTITEM . " $action'); window.history.go(-1);</script>\n";
		exit;
	}

	$pids = implode( ',', $pid );

	$database->setQuery( "UPDATE #__jl_jobposting SET state='$state'"
	. "\nWHERE id IN ($pids) AND (checked_out=0 OR (checked_out='$my->id'))"
	);
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if (count( $pid ) == 1) {
		$row = new mosJobPosting( $database );
		$row->checkin( $pid[0] );
	}

	mosRedirect( "index2.php?option=$option&task=list" );
}

/**
* Moves the order of a record
* @param integer The increment to reorder by
* @param integer 1 to move up, -1 to move down.
*/
function orderJobPosting( $uid, $inc ) {
	global $database, $option;

	$row = new mosJobPosting( $database );
	$row->load( $uid );
	$row->move( $inc, "state >= 0" );

	mosRedirect( "index2.php?option=$option&task=list" );
}

/**
* Sets items as posted and sends posting info to the contact
* @param integer A unique item id (passed from an edit form)
*/
function postJobPosting( $pid=null ) {
	global $database, $option;

	if (count( $pid ) < 1) {
		echo "<script> alert('" . _JL_A_SELECTITEMPOST . "'); window.history.go(-1);</script>\n";
		exit;
	}

	foreach ( $pid as $thisid ) {
		$row = new mosJobPosting( $database );
		$row->load( $thisid );
		if ( $row->id ) {
			sendApprovalEmail( $row->contactname, $row->contactemail, $row->title );
			$row->state = 1;
			$row->created = date( "Y-m-d H:i:s" );
			$row->store();
		}
	}

	mosRedirect( "index2.php?option=$option&task=queue" );
}

/**
 * Sends a Approval email
 * @param string name Name of person to send to
 * @param string email Email address to send to
 * @param string title Title of approved posting
 */
function sendApprovalEmail( $name, $email, $title ) {
	global $cfgjl, $mosConfig_sitename;

	$subject = _JL_A_JOBPOSTINGAPPROVED;
	$msg = _JL_A_APPROVE;
	$msg = str_replace( "<<contactname>>", $name, $msg );
	$msg = str_replace( "<<jobtitle>>", $title, $msg );
	$msg = str_replace( "<<sitename>>", $mosConfig_sitename, $msg );
	$msg = str_replace( "<<adminemail>>", $cfgjl['mailfromaddress'], $msg );
	sendEmail( $email, $subject, $msg, $cfgjl['mailfromname'], $cfgjl['mailfromaddress'], $cfgjl['mailfromaddress'] );
}

/**
* Sets items as rejected and sends rejection mail
* @param integer A unique item id (passed from an edit form)
*/
function rejectJobPosting( $pid=null ) {
	global $database, $option;

	if (count( $pid ) < 1) {
		echo "<script> alert('" . _JL_A_SELECTITEM . " $action'); window.history.go(-1);</script>\n";
		exit;
	}

	foreach ( $pid as $thisid ) {
		$row = new mosJobPosting( $database );
		$row->load( $thisid );
		if ( $row->id ) {
			sendRejectionEmail( $row->contactname, $row->contactemail, $row->title );
			$row->state = -3;
			$row->store();
		}
	}

	mosRedirect( "index2.php?option=$option&task=queue" );
}

/**
 * Sends a rejection email
 * @param string name Name of person to send to
 * @param string email Email address to send to
 * @param string title Title of rejected posting
 */
function sendRejectionEmail( $name, $email, $title ) {
	global $cfgjl, $mosConfig_sitename;

	$subject = _JL_A_JOBPOSTINGREJECTED;
	$message = _JL_A_REJHI . " $name\n\n";
	$msg = _JL_A_REJECTION;
	$msg = str_replace( "<<contactname>>", $name, $msg );
	$msg = str_replace( "<<jobtitle>>", $title, $msg );
	$msg = str_replace( "<<sitename>>", $mosConfig_sitename, $msg );
	$msg = str_replace( "<<adminemail>>", $cfgjl['mailfromaddress'], $msg );
	sendEmail( $email, $subject, $msg, $cfgjl['mailfromname'], $cfgjl['mailfromaddress'], $cfgjl['mailfromaddress'] );
}

function showConfig() {
	global $database, $mosConfig_absolute_path, $option, $cfgfile, $cfgjl;

	@chmod( $cfgfile, 0766 );
	$permission = is_writable( $cfgfile );
	if ( !$permission ) {
		$err = "<b>" . _JL_ERR_YOURCONFIG . " $cfgfile</b><br />";
		$err .= "<b>". _JL_ERR_CHMODFILE . "</b></div><br /><br />";
		showError ( $err, _JL_ERR_WARNING );
	}

	// Make a select list for list sort order
	$listso = array();
	$listso[] = mosHTML::makeOption( 'titleasc', _JL_TITLEASC );
	$listso[] = mosHTML::makeOption( 'titledesc', _JL_TITLEDESC );
#   $listso[] = mosHTML::makeOption( 'orderingasc', _JL_ORDERINGASC );
#   $listso[] = mosHTML::makeOption( 'orderingdesc', _JL_ORDERINGDESC);
	$listso[] = mosHTML::makeOption( 'jobidasc', _JL_JOBIDASC );
	$listso[] = mosHTML::makeOption( 'jobiddesc', _JL_JOBIDDESC );
	$listso[] = mosHTML::makeOption( 'createdasc', _JL_A_CREATEDASC );
	$listso[] = mosHTML::makeOption( 'createddesc', _JL_A_CREATEDDESC );
	

	// Set access rights for who can post jobs.
	$postjobs = array();
	$postjobs[] = mosHTML::makeOption( '99', _JL_A_ACCESS_NONE );
	$postjobs[] = mosHTML::makeOption( '0', _JL_A_ACCESS_ALL );
	$postjobs[] = mosHTML::makeOption( '1', _JL_A_ACCESS_REGISTERED );
	$postjobs[] = mosHTML::makeOption( '2', _JL_A_ACCESS_USER );

	// Find the available template sets
	$templates = array();
	if ( $handle = opendir( "$mosConfig_absolute_path/components/com_online_estagio/templates" ) ) {
		while ( false !== ( $file = readdir( $handle ) ) ) {
			if ( $file != "." && $file != ".." && $file != "index.html" ) {
				if ( is_dir( "$mosConfig_absolute_path/components/com_online_estagio/templates/$file" ) ) {
					$templates[] = mosHTML::makeOption( "$file", "$file" );
				}
			}
		}
	}

	// List editor selection option.
	$edits = array();
	$edits[] = mosHTML::makeOption( '_jx_none', _JL_A_NO_EDITOR );
	$edits[] = mosHTML::makeOption( '_jx_default', _JL_A_DEFAULT_EDITOR );

	// Configuration options
	$cfg = array();
	$cfg['sortorder'] = mosHTML::selectList( $listso, 'cfg_sort_order', 'class="inputbox" size="1"', 'value', 'text', $cfgjl['sort_order'] );
	
	$cfg['ccfields'] = mosHTML::yesnoSelectList( 'cfg_ccfields', 'class="inputbox" size="1"', $cfgjl['ccfields'] );
	$cfg['emailapp'] = mosHTML::yesnoSelectList( 'cfg_emailapp', 'class="inputbox" size="1"', $cfgjl['emailapp'] );
	$cfg['postjobs'] = mosHTML::selectList( $postjobs, 'cfg_postjobs', 'class="inputbox" size="1"', 'value', 'text', $cfgjl['postjobs'] );
	$cfg['templates'] = mosHTML::selectList( $templates, 'cfg_template', 'class="inputbox" size="1"', 'value', 'text', $cfgjl['template'] );
	$cfg['autoapprove'] = mosHTML::yesnoSelectList( 'cfg_autoapprove', 'class="inputbox" size="1"', $cfgjl['autoapprove'] );
	$cfg['editor'] = mosHTML::selectList( $edits, 'cfg_editor', 'class="inputbox" size="1"', 'value', 'text', $cfgjl['editor'] );
	$cfg['initeditor'] = mosHTML::yesnoSelectList( 'cfg_initeditor', 'class="inputbox" size="1"', $cfgjl['initeditor'] );

	
	// Make sure all configuration variables can be used in a form.
	foreach ( $cfgjl as $cfgkey => $cfgval ) {
		$cfgjl[$cfgkey] = htmlspecialchars( $cfgval );
	}

	HTML_online_estagio_admin::showConfig( $cfgjl, $cfg, $cfg );
}

function saveConfig() {
	global $database, $mosConfig_absolute_path, $option, $cfgfile;

	@chmod( $cfgfile, 0766 );
	if ( !is_writable( $cfgfile ) ) {
		mosRedirect( "index2.php?option=$option", _JL_ERR_NOT_WRITEABLE );
	}
	
	$txt = "<?php\n";
	foreach ( $_POST as $k => $v ) {
		if ( strpos( $k, 'cfg_' ) === 0 ) {
			if ( !get_magic_quotes_gpc() ) {
				$v = addslashes( $v );
			}
			$txt .= "\$cfgjl['" . substr( $k, 4 ) . "']='$v';\n";
		}
	}
	$txt .= "foreach( \$cfgjl as \$_k => \$_v ) { \$cfgjl[\$_k] = stripslashes( \$_v ); }\n";
	$txt .= "?>";
	
	if ( $fp = fopen( $cfgfile, "w" ) ) {
		fputs( $fp, $txt, strlen( $txt ) );
		fclose( $fp );
		mosRedirect( "index2.php?option=$option&task=conf", _JL_A_CONFIG_SAVED );
	} else {
		mosRedirect( "index2.php?option=$option&task=conf", _JL_ERR_OPEN_FILE );
	}
}

function showPages( $pages ) {
	global $mosConfig_absolute_path, $option;
	
	// Read all files and convert if necessary.
	for ( $i = 1; $i <= count($pages); $i++ ) {
		$filecontent = implode( '',  @file( "$mosConfig_absolute_path/administrator/components/$option/" . $pages[$i]['file'] ) );
		
		// Text files get newlines added after each line.
		if ( substr($pages[$i]['file'], -4) == ".txt" ) {
			$filecontent = nl2br($filecontent);
		}
		$pages[$i]['content'] = $filecontent;
	}

	HTML_online_estagio_admin::showAdminPages( $pages, _JL_A_INFORMATION );
}


?>
<?php
// online_estagio Component


defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

//Get right Language file
if ( file_exists( "$mosConfig_absolute_path/components/$option/language/$mosConfig_lang.php" ) ) {
	include_once("$mosConfig_absolute_path/components/$option/language/$mosConfig_lang.php");
} else {
	include_once("$mosConfig_absolute_path/components/$option/language/english.php");
}

// Read configuration file
include_once("$mosConfig_absolute_path/components/$option/configuration.php");

// Read a file with common functions
require_once("$mosConfig_absolute_path/components/$option/online_estagio.common.php");

// Read a file containing the mxTemplate class
require_once("$mosConfig_absolute_path/components/$option/mxtemplate.php");

// Read frontend html classes
require_once( $mainframe->getPath( 'front_html' ) );

// Read database class
require_once( $mainframe->getPath( 'class' ) );

// Load request parameters.
$task =  mosGetParam( $_REQUEST ,'task', '' );
$view =  mosGetParam( $_REQUEST ,'view', '' );
$id =  mosGetParam( $_REQUEST ,'id', '' );
$Itemid =  mosGetParam( $_REQUEST ,'Itemid', '' );
$type =  mosGetParam( $_REQUEST ,'type', '' );
$search = mosGetParam( $_REQUEST, 'search','' );
$limit = trim( mosGetParam( $_REQUEST, 'limit', $cfgjl['item_limit'] ) );
$limitstart = trim( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
$sort = trim( mosGetParam( $_REQUEST, 'sort', $cfgjl['sort_order'] ) );
$msg = trim( mosGetParam( $_REQUEST, 'msg', '' ) );

// Let's see what action to execute.
switch ( $task ) {
	case "add":
		editJobPosting( 0 );
		break;
	case "preview":
		previewJobPosting();
		break;
	case "save":
		saveJobPosting();
		break;
	case "reedit":
		editJobPostingFromForm();
		break;
	case "cancel":
		cancelJobPosting();
		break;
	case "apply":
		showApplicationForm( $id );
		break;
	case "send":
		sendApplication( $id );
		break;
	case "thankyou":
		showThankYou( $msg, $id );
		break;
	case "error":
		showError( $msg );
		break;
	case "view":
		viewJobPosting( $id, true );
		break;
	case "results":
		searchJobPostings( $search, $sort, $type );
		break;
	case "search":
		showSearchForm( $search );
		break;
	case "list":
	default:
		listJobPostings( $type, $sort, $limit, $limitstart );
}

/* ******************** Main functions ******************** */

/**
 * Send an application to the job posting contact
 */
function sendApplication( $id ) {
	global $database, $mosConfig_absolute_path, $mosConfig_live_site, $option, $cfgjl, $mainframe, $my, $Itemid;

	$row = new mosJobPosting( $database );
	$row->load( $id );

	if ( !$row->id ) {
		mosRedirect( "$mosConfig_live_site/index.php?option=$option&task=error&msg=" . _JL_NOSUCHJOB );
	} else {

		$tmplvars = get_object_vars( $row );
		foreach ( $_REQUEST as $k => $v ) {
			$tmplvars["req_$k"] = $v;
		}

		$tmpl = new mxTemplate( "$mosConfig_absolute_path/components/com_online_estagio/templates/{$cfgjl['template']}" );
		if ( $tmpl->setTemplate( "applicationemail" ) ) {
			$tmpl->setVars( $tmplvars );
			$tmpl->parseTemplate();
			$message = $tmpl->getOutput();

			sendEmail( $row->contactemail, _JL_APPLICATION_SUBJECT, $message, $cfgjl['mailfromname'], $cfgjl['mailfromaddress'] );

			mosRedirect( "$mosConfig_live_site/index.php?option=$option&Itemid=$Itemid&task=thankyou&id=$id" );
		} else {
			showError( _JL_ERRORSETTMPL . ": applicationemail" );
			
		}
	
	}

}

/**
 * Show a thank you message.
 *
 * @param int ID of a job to link back to.
 */
function showThankYou( $msg, $id ) {
	HTML_online_estagio::showThankYou( $msg, $id );
}

/**
 * Show an application form for the given job.
 * 
 * @param int ID of the job to apply for.
 */
function showApplicationForm( $id ) {
	global $database, $mosConfig_absolute_path, $option, $cfgjl, $mainframe, $my;

	$gid = $my->gid;
	$access = !$mainframe->getCfg( 'shownoauth' );

	$row = new mosJobPosting( $database );
	$row->load( $id );

	if ( $row->id ) {
		if ( $row->state != "1" ) {
			showError( _JL_ACCESS_DENIED );
			return;
		}
		if ( $access && $row->access > $gid ) {
			showError( _JL_ACCESS_DENIED );
			return;
		}


		HTML_online_estagio::showApplicationForm( $row
);
	} else {
		showError( _JL_NOSUCHJOB );
}
}

/**
 * Save an item to the database.
 */
function saveJobPosting() {
	global $database, $mosConfig_live_site, $mosConfig_absolute_path, $option, $cfgjl, $mainframe, $my, $Itemid;

	if ( $cfgjl['postjobs'] > $my->gid ) {
		mosRedirect( "$mosConfig_live_site/index.php?option=$option&Itemid=$Itemid&task=error&msg=" . _JL_JOBPOSTINGNOTALLOWED );
		exit;
	}

	$row = new mosJobPosting( $database );
	if (!$row->bind( $_POST, "state hits ordering checked_out checked_out_time created created_by modified modified_by _db _tbl _tbl_key _error" )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if ( $row->id ) {
		$row->modified = date( "Y-m-d H:i:s" );
		$row->modified_by = $my->id;
	} else {
		$row->created = date( "Y-m-d H:i:s" );
		$row->created_by = $my->id;
		if ( $cfgjl['autoapprove'] ) {
			$row->state = 1;
		} else {
			$row->state = -2;
		}
	}

	if ( $row->id ) {
		if ( $my->id ) {
			if ( $row->created_by != $my->id ) {
				mosRedirect( "$mosConfig_live_site/index.php?option=$option&Itemid=$Itemid&task=error&msg=" . _JL_NOTOWNER );
				return;
			}
		} else {
			mosRedirect( "$mosConfig_live_site/index.php?option=$option&Itemid=$Itemid&task=error&msg=" . _JL_NOTLOGGEDIN );
			return;
		}
	}

	$row->ordering = 99999;
	
	if ( !preg_match( "#^http(s?)://#i", $row->companyurl ) ) {
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

	// Send mail to admin about this job posting.
	if ( !$row->modified_by && trim( $cfgjl['mailfromaddress'] ) ) {
		$msg = str_replace( "<<jobtitle>>", $row->title, _JL_NEWJOB_MSG );
		$msg = str_replace( "<<livesite>>", $mosConfig_live_site, $msg );
		sendEmail( $cfgjl['mailfromaddress'], _JL_NEWJOBPOSTED, $msg, $cfgjl['mailfromname'], $cfgjl['mailfromaddress'] );
	}

	mosRedirect( "$mosConfig_live_site/index.php?option=$option&Itemid=$Itemid&task=thankyou&msg=" . _JL_JOBPOSTINGSENT );
}

/**
 * Show the item and give the user the option to save or re-edit.
 */
function previewJobPosting() {
	global $database, $mosConfig_absolute_path, $mosConfig_live_site, $option, $cfgjl, $mainframe, $my;

	if ( $cfgjl['postjobs'] > $my->gid ) {
		mosRedirect( "$mosConfig_live_site/index.php?option=$option&Itemid=$Itemid&task=error&msg=" . _JL_JOBPOSTINGNOTALLOWED );
		exit;
	}

	// Bind the posted form variables to a new database object for viewing.
	$row = new mosJobPosting( $database );
	if (!$row->bind( $_POST, "state hits ordering checked_out checked_out_time created created_by modified modified_by _db _tbl _tbl_key _error" )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	// Make sure created time and modified time will show as well
	if ($row->id) {
		$row->modified = date( "Y-m-d H:i:s" );
		$row->modified_by = $my->id;
	} else {
		$row->created = date( "Y-m-d H:i:s" );
		$row->created_by = $my->id;
	}

	// Retrieve the extra fields
	$row->attribs = getExtraAttribs();
	
	// Insert linebreaks before newlines for the viewing
	$row->description = str_replace( "\n", "<br />\n", $row->description );
	$row->qualifications = str_replace( "\n", "<br />\n", $row->qualifications );
	$row->applyinfo = str_replace( "\n", "<br />\n", $row->applyinfo );

	HTML_online_estagio::show( $row, "preview" );

	// Remove the linebreaks for the posting
	$row->description = str_replace( "<br />\n", "\n", $row->description );
	$row->qualifications = str_replace( "<br />\n", "\n", $row->qualifications );
	$row->applyinfo = str_replace( "<br />\n", "\n", $row->applyinfo );

	HTML_online_estagio::previewJobPosting( $row );
}

/**
* Cancels an edit operation
* @param database A database connector object
*/
function cancelJobPosting( ) {
	global $database, $option, $Itemid, $mosConfig_live_site;

	if ( $cfgjl['postjobs'] > $my->gid ) {
		mosRedirect( "$mosConfig_live_site/index.php?option=$option&Itemid=$Itemid&task=error&msg=" . _JL_JOBPOSTINGNOTALLOWED );
		exit;
	}

	$row = new mosJobPosting( $database );
	$row->bind( $_POST, "state hits ordering checked_out checked_out_time created created_by modified modified_by" );
	$row->checkin();
	mosRedirect( "$mosConfig_live_site/index.php?option=$option&Itemid=$Itemid&task=list" );
}

/**
 * Edit an item, if no id is given, a new item will be created.
 * @param int id ID of the item to edit.
 */
function editJobPosting( $pid ) {
	global $database, $mosConfig_absolute_path, $mosConfig_live_site, $option, $cfgjl, $mainframe, $Itemid;
	global $my;

	if ( $cfgjl['postjobs'] > $my->gid ) {
		mosRedirect( "$mosConfig_live_site/index.php?option=$option&Itemid=$Itemid&task=error&msg=" . _JL_JOBPOSTINGNOTALLOWED );
		exit;
	}

	$row = new mosJobPosting( $database );
	// load the row from the db table
	$row->load( $pid );

	// fail if checked out not by 'me'
	if ($row->checked_out && $row->checked_out != $my->id) {
		mosRedirect( "$mosConfig_live_site/index2.php?option=content",
		_JL_ERR_CHECKED_OUT1 . " $row->title " . _JL_ERR_CHECKED_OUT2 );
	}

	if ( $row->id ) {
		if ( $my->id ) {
			if ( $row->created_by != $my->id ) {
				showError( _JL_NOTOWNER );
				return;
			}
		} else {
			showError( _JL_NOTLOGGEDIN );
			return;
		}
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
		if ( $cfgjl['autoapprove'] ) {
			$row->state = 1;
		} else {
			$row->state = -2;
		}
		$row->access = 0;
		$row->showcomp = 0;
		$row->showcontact = 0;
		$row->ordering = 9999;
		$row->attribs = array();
	}

	editJobPostingObject( $row );
}

/**
 * Populate an edit form for an item with the data given.
 */
function editJobPostingFromForm( ) {
	global $database, $mosConfig_absolute_path, $mosConfig_live_site, $option, $cfgjl, $mainframe;
	global $my;

	if ( $cfgjl['postjobs'] > $my->gid ) {
		mosRedirect( "$mosConfig_live_site/index.php?option=$option&Itemid=$Itemid&task=error&msg=" . _JL_JOBPOSTINGNOTALLOWED );
		exit;
	}

	$row = new mosJobPosting( $database );
	if (!$row->bind( $_POST, "state access hits ordering checked_out checked_out_time created created_by modified modified_by" )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	// Retrieve the extra fields
	$row->attribs = getExtraAttribs();
	$row->attribs = mosParseParams( $row->attribs );
	$row->attribs = get_object_vars( $row->attribs );
	
	editJobPostingObject( $row );
}

/**
 * Edit a job posting based on the info in the given object.
 *
 * @param object A mosJobPosting object that is either empty or contains
 * data to be changed.
 */
function editJobPostingObject( $row ) {
	global $database, $mosConfig_absolute_path, $option, $cfgjl, $mainframe;
	global $my;

	// Set all unavailable attributes to empty string.
	$extrafields = getExtraFields();
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
	
	$lists['access'] = mosHTML::selectList( $access, 'access', 'class="inputbox" size="1"', 'value', 'text', $row->access );




	// Initialize wysiwyg html editor?
	if ( $cfgjl['initeditor'] && !$my->id ) {
		print initEditorJx();
	}

	HTML_online_estagio::editJobPosting( $row, $lists );
}

/**
 * Show a search form
 *
 * @param string String to prefill the search form with.
 */
function showSearchForm( $search ) {
	global $database, $option, $is_editor, $my, $mainframe, $cfgjl, $Itemid;

	HTML_online_estagio::showSearch( $search );
}

/**
 * Search through all items based on the given searchword.
 *
 * @param string The word to search for.
 * @param string How to sort the result list.
 */
function searchJobPostings( $searchword, $sort, $type ) {
	global $database, $option, $is_editor, $my, $mainframe, $cfgjl, $limit, $limitstart, $Itemid;

	mosLogSearch( $searchword );

	$ordering = getOrdering( $sort );

	// Search the Job Postings based on seleccted criteria
	$obj = new mosJobPosting( $database );
	$rows = $obj->search( $searchword, '', $ordering, $limitstart, $limit, $totalRows );

	if ( $totalRows > 0 ) {
		// Include page navigation script
		require_once("includes/pageNavigation.php");
		$pageNav = new mosPageNav( $totalRows, $limitstart, $limit );

		$link = "index.php?option=$option&Itemid=$Itemid&task=results&search=$searchword";

		// List Job Postings.
		HTML_online_estagio::listJobPostings( $rows, $totalRows, $searchword, $pageNav, $link, $type, $sort );
	} else {
		showError( _JL_NO_RESULTS, _JL_NOTHING_FOUND );
		showSearchForm( $searchword );
	}
}

/*
 * Shows information about an item.
 *
 * @param integer ID of the item to show.
 * @param boolean Show full description? 
 */
function viewJobPosting( $id, $full=true ) {
	global $database, $option, $is_editor, $my, $mainframe, $pop, $cfgjl;
	$gid = $my->gid;

	$access = !$mainframe->getCfg( 'shownoauth' );

	$row = new mosJobPosting( $database );
	$row->load( $id );

	if ( $row->id ) {
		if ( $row->state != "1" && !$is_editor ) {
			showError( _JL_ACCESS_DENIED );
			return;
		}
		if ( $access && $row->access > $gid ) {
			showError( _JL_ACCESS_DENIED );
			return;
		}
		if ( ( $cfgjl['publishinglimit'] > 0 ) && ( strtotime( $row->created ) < ( time() - ( intval( $cfgjl['publishinglimit'] ) * 86400 ) ) ) ) {
			showError( _JL_NOTACCESIBLE );
			return;
		}

		// Insert linebreaks before newlines for the viewing
		$row->description = str_replace( "\n", "<br />\n", $row->description );
		$row->qualifications = str_replace( "\n", "<br />\n", $row->qualifications );
		$row->applyinfo = str_replace( "\n", "<br />\n", $row->applyinfo );

		// Record this hit
		$row->hit();

		HTML_online_estagio::show( $row );
	} else {
		showError( _JL_ITEM_NOT_FOUND );
	}
}

/**
 * List items in the database
 *
 * @param string Type of jobs to list, 2 to only show internships, -1 to show all types
 * @param string What to order the list by (titleasc, titledesc, orderingasc, orderingdesc, jobiddesc, jobidasc, createdasc, createddesc)
 * @param int How many items to show on the page
 * @param int What item to start the list at
 */
function listJobPostings( $type, $sort, $limit, $limitstart ) {
	global $database, $option, $is_editor, $my, $mainframe, $cfgjl, $Itemid;
	$gid = $my->gid;

	$access = !$mainframe->getCfg( 'shownoauth' );

	// Limit the list
	if ( $limit > 0 ) {
		$limitquery = "\nLIMIT $limitstart, $limit";
	}

	// Only show one job type
	$typequery = "";
	if ( $type != "" ) {
		switch ( $type ) {
			case "internships": $typequery = "\n    AND c.jobtype = 2"; break;
			case "normal": $typequery = "\n    AND ( c.jobtype = 0 OR c.jobtype = 1 )"; break;
			default:
				$type = intval( $type );
				$typequery = "\n    AND c.jobtype = '$type'";
		}
	}

	// Check out of we need to show only published items
	$publishedquery = "";
	if ( $cfgjl['publishinglimit'] > 0 ) {
		$ptime = date( "Y-m-d H:i:s", time() - intval( $cfgjl['publishinglimit'] ) * 86400 );
		$publishedquery = "\n	AND c.created >= '$ptime'";
	}

	$ordering = getOrdering( $sort );

	// Read properties from db.
	$database->setQuery( "SELECT * "
		. "\nFROM #__jl_jobposting AS c"
		. "\nWHERE c.state='1' "
		. ($access ? "\n	AND c.access<='$gid'" : "" )
        . $publishedquery
		. $typequery
		. "\nORDER BY $ordering"
		. "$limitquery"
	);
	$items = $database->loadObjectList();
#	print $database->getQuery();

	// get the total number of records
	$database->setQuery( "SELECT count(id)"
		. "\nFROM #__jl_jobposting AS c"
		. "\nWHERE c.state='1' "
		. ($access ? "\n	AND c.access<='$gid'" : "" )
		. $publishedquery
		. $typequery
	);
	$totalRows = $database->loadResult();
#	print $database->getQuery();

	// Include page navigation script
	require_once("includes/pageNavigation.php");
	$pageNav = new mosPageNav( $totalRows, $limitstart, $limit );

	$link = "index.php?option=$option&Itemid=$Itemid&task=list";

	// List Job Postings.
	HTML_online_estagio::listJobPostings( $items, $totalRows, "", $pageNav, $link, $type, $sort );

}

?>

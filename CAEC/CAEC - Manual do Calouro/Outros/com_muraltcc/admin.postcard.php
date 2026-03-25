<?php
// $Id: admin.banners.php,v 1.16 2004/01/13 00:57:16 eddieajau Exp $
/**
* Banner admin
* @package Mambo Open Source
* @Copyright (C) 2000 - 2003 Miro International Pty Ltd
* @ All rights reserved
* @ Mambo Open Source is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.16 $
**/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'class' ) );
require_once( $mosConfig_absolute_path."/administrator/components/com_postcard/html/config.php" );
require_once( $mosConfig_absolute_path."/administrator/components/com_postcard/html/postcard.php" );
require_once( $mosConfig_absolute_path."/administrator/components/com_postcard/html/category.php" );

$cid = mosGetParam( $_REQUEST, 'cid', array(0) );
if (!is_array( $cid )) {
	$cid = array(0);
}

switch ($task) {
// CONFIG EVENTS
	case "config_save":
		saveConfig( $option, $introtext, $footertext, $view_in_template );
		break;

	case "config":
		viewConfig( $option );
		break;

// IMAGE MANAGER EVENTS
	case "manage_new":
		editManage( $option, 0 );
		break;

	case "manage_cancel":
		cancelEditManage( $option );
		break;

	case "manage_edit":
		editManage( $option, $cid[0] );
		break;

	case "manage_save":
		saveManage( $option, $id, $id_category, $pname, $filename );
		break;

	case "manage_remove":
		removeManage( $cid, $option );
		break;

	case "manage":
		viewManage( $option, $category );
		break;

// CATEGORY EVENTS
	case "category_new":
		editCategory( null, $option );
		break;

	case "category_cancel":
		cancelEditCategory( $option );
		break;

	case "category_save":
		saveCategory( $option, $id, $cname, $description, $style );
		break;

	case "category_edit":
		editCategory( $cid[0], $option );
		break;

	case "category_remove":
		removeCategory( $cid, $option );
		break;

	case "category":
		viewCategory( $option );
		break;

	case "category_orderup":
		orderCategory( $cid[0], -1, $option );
		break;

	case "category_orderdown":
		orderCategory( $cid[0], 1, $option );
		break;
}

// CONFIG FUNCTIONS
function viewConfig( $option ) {
	global $database;

	$lists = array();
	
	$database->setQuery( "SELECT * FROM #__postcard_config WHERE id='1'" );
	$row = null;
	$database->loadObject( $row );

	$listall[] = mosHTML::makeOption( '0', 'Yes' );
	$listall[] = mosHTML::makeOption( '1', 'No' );
	$lists['view_in_template'] = mosHTML::selectList( $listall, 'view_in_template', 'class="inputbox" size="1"', 'value', 'text', $row->view_in_template);
	
	HTML_config::configForm( $row, $option, $lists );
}

function saveConfig( $option, $introtext, $footertext, $view_in_template )
{
	global $database;
	
	$database->setQuery( "UPDATE #__postcard_config SET footertext='$footertext', view_in_template='$view_in_template'" );
	$database->query();

	mosRedirect( "index2.php?option=$option&task=config", "Configuration saved!" );
}

// IMAGE MEDIA FUNCTIONS
function viewManage( $option, $category ) {
	global $database, $mainframe;

	$limit = intval( mosGetParam( $_REQUEST, 'limit', 10 ) );
	$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

	// category filter
	if($category > 0) {
		$where = " WHERE id_category='$category'";
	}else{
		$where = "";
	}
	
	// get the total number of records
	$database->setQuery( "SELECT count(*) FROM #__postcard $where" );
	$total = $database->loadResult();
	echo $database->getErrorMsg();

	require_once("includes/pageNavigation.php");
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	$query = "SELECT * FROM #__postcard $where"
	. "\nORDER BY filename LIMIT $pageNav->limitstart,$pageNav->limit";
	$database->setQuery( $query );

	if(!$result = $database->query()) {
		echo $database->stderr();
		return false;
	}

	$rows = $database->loadObjectList();

	// Build Category select list
	$sql = "SELECT `id` AS value, `cname` AS text FROM #__postcard_category ORDER BY cname";
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database->stderr();
		return false;
	}
	$catlistall[] = mosHTML::makeOption( '0', 'All' );
	$catlistall = array_merge( $catlistall, $database->loadObjectList() );
	$catlist = mosHTML::selectList( $catlistall, 'category', 'class="inputbox" size="1" onChange="SubmitForm();"', 'value', 'text', $category);

	HTML_postcard::showPostcards( $rows, $pageNav, $option, $catlist );
}

function editManage( $option, $id ) { 
	global $database;
	
	$row = new mosPostcard($database);
	$row->load( $id );

	// Build Client select list
	$sql = "SELECT id as value, cname as text FROM #__postcard_category";
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database->stderr();
		return false;
	}

	$categorylist[] = mosHTML::makeOption( '0', 'Select Category' );
	$categorylist = array_merge( $categorylist, $database->loadObjectList() );
	$lists['category'] = mosHTML::selectList( $categorylist, 'id_category', 'class="inputbox" size="1"','value', 'text', $row->id_category);

	HTML_postcard::postcardForm( $option, $row, $lists ); ?>
<?php
}

function saveManage( $option, $id, $id_category, $pname, $filename ) {	
	global $mosConfig_absolute_path, $database;
	require_once( $mosConfig_absolute_path."/administrator/components/com_postcard/upload.php" );
	
	if($id==0) {
		if(file_exists($mosConfig_absolute_path."/components/com_postcard/postcards/".$_FILES['imagefile']['name'])) {
			$file_exists = true;
			$message = "ERRO: A file with that name already exists.";
		}else{
			$file_exists = false;
		}
		
		if($file_exists == false) {
			$yukle = new upload;
			$yukle->set_max_size(17500000); //1,75MB
			$yukle->set_directory($mosConfig_absolute_path."/components/com_postcard/postcards");
			$yukle->set_tmp_name($_FILES['imagefile']['tmp_name']);
			$yukle->set_file_size($_FILES['imagefile']['size']);
			$yukle->set_file_type($_FILES['imagefile']['type']);
			$yukle->set_file_name($_FILES['imagefile']['name']);
			$yukle->start_copy();
			
			if($yukle->is_ok()) {
				$message = "File uploaded to the server.";
				
				$yukle->set_thumbnail_name("thumb_".substr($_FILES['imagefile']['name'], 0, strlen($_FILES['imagefile']['name'])-4));
				$yukle->create_thumbnail();
				$yukle->set_thumbnail_size(230, 164);

				$database->setQuery( "INSERT INTO #__postcard(filename, id_category, pname) VALUES('".$_FILES['imagefile']['name']."', '$id_category', '$pname')" );
				$database->query();
			}else{
				$message = "ERRO: File couldn't be uploaded to the server.";
			}
		}

		unset($yukle);
	}else{
		if($_FILES['imagefile']['name']!="") {
			unlink($mosConfig_absolute_path."/components/com_postcard/postcards/".$filename);
			$yukle = new upload;
			$yukle->set_max_size(17500000); //1,75MB
			$yukle->set_directory($mosConfig_absolute_path."/components/com_postcard/postcards");
			$yukle->set_tmp_name($_FILES['imagefile']['tmp_name']);
			$yukle->set_file_size($_FILES['imagefile']['size']);
			$yukle->set_file_type($_FILES['imagefile']['type']);
			$yukle->set_file_name($_FILES['imagefile']['name']);
			$yukle->start_copy();

			$update_file = ", filename='".$_FILES['imagefile']['name']."'";
		}

		$database->setQuery( "UPDATE #__postcard SET id_category='$id_category', pname='$pname'$update_file WHERE id='$id'" );
		$database->query();
	}

	mosRedirect( "index2.php?option=$option&task=manage", $message );
}

function cancelEditManage( $option ) {
	global $database;
	$row = new mosPostcard($database);
	$row->bind( $_POST );
	$row->checkin();
	mosRedirect( "index2.php?option=$option&task=manage" );
}

function removeManage( $cid, $option ) {
	global $database, $mosConfig_absolute_path;
	
	for($i=0; $i<count($cid); $i++) {
		$database->setQuery( "SELECT filename FROM #__postcard WHERE id='".$cid[$i]."'" );
		$filename = $database->loadResult();
		unlink($mosConfig_absolute_path."/components/com_postcard/postcards/".$filename);
		unlink($mosConfig_absolute_path."/components/com_postcard/postcards/thumb_".$filename);
	}

	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$database->setQuery( "DELETE FROM #__postcard WHERE id IN ($cids)" );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}

	mosRedirect( "index2.php?option=$option&task=manage" );
}

// CATEGORY FUNCTIONS
function viewCategory( $option ) {
	global $database, $mainframe;

	$limit = intval( mosGetParam( $_REQUEST, 'limit', 10 ) );
	$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

	// get the total number of records
	$database->setQuery( "SELECT count(*) FROM #__postcard_category" );
	$total = $database->loadResult();
	echo $database->getErrorMsg();

	require_once("includes/pageNavigation.php");
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	$query = "SELECT * FROM #__postcard_category"
	. "\nORDER BY ordering LIMIT $pageNav->limitstart,$pageNav->limit";
	$database->setQuery( $query );

	if(!$result = $database->query()) {
		echo $database->stderr();
		return false;
	}
	$rows = $database->loadObjectList();
	HTML_category::showCategory( $rows, $pageNav, $option );
}

function editCategory( $Categoryid, $option ) {
	global $database;
	global $mainframe;
	$lists = array();

	$row = new mosPostcardCategory($database);
	$row->load( $Categoryid );

	$stylelist[] = mosHTML::makeOption( '0', 'Style 1' );
	$stylelist[] = mosHTML::makeOption( '1', 'Style 2' );
	$lists['style'] = mosHTML::selectList( $stylelist, 'style', 'class="inputbox" size="1"','value', 'text', $row->style);

	HTML_category::categoryForm( $row, $option, $lists );
}

function saveCategory( $option, $id, $cname, $description, $style ) {
	global $database;
	$row = new mosPostcardCategory($database);

	if($id>0) {
		$database->setQuery( "UPDATE #__postcard_category SET cname='$cname', description='$description', style='$style' WHERE id='$id'" );
		$database->query();
	}else{
		$database->setQuery( "SELECT MAX(ordering) FROM #__postcard_category" );
		$orderpage = $database->loadResult();

		$database->setQuery( "INSERT INTO #__postcard_category(cname, description, ordering, style) VALUES('$cname', '$description',  '".($orderpage+1)."', '$style')" );
		$database->query();
	}
	
	if($database->getErrorMsg()) {
		echo "<p>".$database->getErrorMsg();
	}else{
		mosRedirect( "index2.php?option=$option&task=category" );
	}
}

function cancelEditCategory( $option ) {
	global $database;
	$row = new mosPostcardCategory($database);
	$row->bind( $_POST );
	$row->checkin();
	mosRedirect( "index2.php?option=$option&task=category" );
}

function removeCategory( $cid, $option ) {
	global $database;
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$database->setQuery( "DELETE FROM #__postcard_category WHERE id IN ($cids)" );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	mosRedirect( "index2.php?option=$option&task=category" );
}

function orderCategory( $uid, $inc, $option ) {
	global $database;

	$row = new mosPostcardCategory( $database );
	$row->load( $uid );
	$row->move( $inc, "published >= 0" );
	mosRedirect( "index2.php?option=$option&task=category" );
}
?>
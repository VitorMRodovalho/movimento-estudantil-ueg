<?php
//zOOm Media Gallery//
/** 
-----------------------------------------------------------------------
|  zOOm Media Gallery! by Mike de Boer - a multi-gallery component    |
-----------------------------------------------------------------------

-----------------------------------------------------------------------
|                                                                     |
| Date: February, 2005                                                |
| Author: Mike de Boer, <http://www.mikedeboer.nl>                    |
| Copyright: copyright (C) 2004 by Mike de Boer                       |
| Description: zOOm Media Gallery, a multi-gallery component for      |
|              Mambo. It's the most feature-rich gallery component    |
|              for Mambo! For documentation and a detailed list       |
|              of features, check the zOOm homepage:                  |
|              http://zoom.ummagumma.nl                               |
| License: GPL                                                        |
| Filename: admin.zoom.php                                            |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder	Alerts
defined( '_VALID_MOS' )	or die(	'Direct	Access to this location	is not allowed.' );

// Create zOOm Image Gallery object
require($mosConfig_absolute_path.'/components/com_zoom/classes/zoom.class.php');
require($mosConfig_absolute_path.'/components/com_zoom/classes/toolbox.class.php');
require($mosConfig_absolute_path.'/components/com_zoom/classes/editmon.class.php'); //like a common session-monitor...
require($mosConfig_absolute_path.'/components/com_zoom/classes/gallery.class.php');
require($mosConfig_absolute_path.'/components/com_zoom/classes/image.class.php');
require($mosConfig_absolute_path.'/components/com_zoom/classes/comment.class.php');
// Load configuration file...
include($mosConfig_absolute_path.'/components/com_zoom/zoom_config.php');

$zoom = new zoom();
$zoom->_isBackend = true;
// now create an instance of the ToolBox!
$zoom->_toolbox = new toolbox();

// list of common inclusions:
if (file_exists($mosConfig_absolute_path."/components/com_zoom/language/".$mosConfig_lang.".php")){ 
	include_once($mosConfig_absolute_path."/components/com_zoom/language/".$mosConfig_lang.".php");
}else{ 
	include_once($mosConfig_absolute_path."/components/com_zoom/language/english.php");
}
if($zoom->isWin()){
	include($mosConfig_absolute_path.'/components/com_zoom/classes/fs_win32.php');
}else{
	include($mosConfig_absolute_path.'/components/com_zoom/classes/fs_unix.php');
}
// load gallery object if a catid is specified...
if(array_key_exists('catid', $_REQUEST)){
	$catid = $_REQUEST['catid'];
}
if (isset($catid) && $catid!="" && !is_array($catid)){
	$zoom->setGallery($catid);
}
if ($zoom->_isBackend) {
	$backend = "2";
} else {
	$backend = "";
}
// Standard (D)HTML...
echo ("<div id=\"overDiv\" style=\"position:absolute; visibility:hidden; z-index:1000;\"></div>\n"
 . "\t<script language=\"JavaScript\" type=\"text/JavaScript\" src=\"" . $mosConfig_live_site . "/includes/js/overlib_mini.js\"></script>\n");

$page = mosGetParam($_REQUEST,'page');
switch ($page){
	case 'admin':
		include($mosConfig_absolute_path.'/components/com_zoom/admin/admin.php');
		$zoom->adminFooter();
		break;
	case 'catsmgr':
		include($mosConfig_absolute_path.'/components/com_zoom/admin/catsmgr.php');
		$zoom->adminFooter();
		break;
	case 'mediamgr':
		include($mosConfig_absolute_path.'/components/com_zoom/admin/mediamgr.php');
		$zoom->adminFooter();
		break;
	case 'delpic':
		if($zoom->_isAdmin || ($zoom->_isUser && $zoom->_CONFIG['allowUserUpload'])){
			include($mosConfig_absolute_path.'/components/com_zoom/delimg.php');
		}else{
			echo "Error: You'll have to be logged in as admin or user/ editor to view this page!";
		}
	break;
	case 'editpic':
		if($zoom->_isAdmin || ($zoom->_isUser && $zoom->_CONFIG['allowUserUpload'])){
			include($mosConfig_absolute_path.'/components/com_zoom/editimg.php');
		}else{
			echo "Error: You'll have to be logged in as admin or user/ editor to view this page!";
		}
	break;
	case 'new':
		include($mosConfig_absolute_path.'/components/com_zoom/admin/new.php');
		$zoom->adminFooter();
		break;
	case 'upload':
	   	include($mosConfig_absolute_path.'/components/com_zoom/admin/upload.php');
	   	$zoom->adminFooter();
	   	break;
	case 'settings':
		include($mosConfig_absolute_path.'/components/com_zoom/admin/settings.php');
		$zoom->adminFooter();
		break;
	case 'update':
		if (strstr($version, '4.5.1')) {
			echo "I'm sorry, but the update feature isn't ready yet for Mambo 4.5.1.";
		}else{
			include($mosConfig_absolute_path.'/components/com_zoom/admin/update.php');
			$zoom->adminFooter();
		}
		break;
	case 'movefiles':
		include($mosConfig_absolute_path.'/components/com_zoom/admin/movefiles.php');
		$zoom->adminFooter();
		break;
	case 'credits':
		include($mosConfig_absolute_path.'/components/com_zoom/admin/credits.php');
		$zoom->adminFooter();
	    break;
	default:
		include($mosConfig_absolute_path.'/components/com_zoom/admin/admin.php');
		$zoom->adminFooter();
		break;
}

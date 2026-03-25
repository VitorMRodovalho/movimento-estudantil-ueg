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
| Filename: zoom.php                                                  |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// Turn off Magic quotes runtime, because it interferes with saving info to the 
// database and vice versa.
set_magic_quotes_runtime(0); 

// Create zOOm Image Gallery object
require($mosConfig_absolute_path.'/components/com_zoom/classes/zoom.class.php');
require($mosConfig_absolute_path.'/components/com_zoom/classes/toolbox.class.php');
require($mosConfig_absolute_path.'/components/com_zoom/classes/pdf.class.php');
require($mosConfig_absolute_path.'/components/com_zoom/classes/editmon.class.php'); //like a common session-monitor...
require($mosConfig_absolute_path.'/components/com_zoom/classes/gallery.class.php');
require($mosConfig_absolute_path.'/components/com_zoom/classes/image.class.php');
require($mosConfig_absolute_path.'/components/com_zoom/classes/comment.class.php');
require($mosConfig_absolute_path.'/components/com_zoom/classes/ecard.class.php');
require($mosConfig_absolute_path.'/components/com_zoom/classes/lightbox.class.php');
// Load configuration file...
include($mosConfig_absolute_path.'/components/com_zoom/zoom_config.php');

$zoom = new zoom();
// now create an instance of the ToolBox!
$zoom->_toolbox = new toolbox();
// Start session for the LightBox...
if($zoom->_CONFIG['lightbox']){
  session_name('zoom');
  session_start();
  
  if(!isset($_SESSION['lightbox'])){
  	$_SESSION['lightbox'] = new lightbox();
  	// for test purposes:
  	$_SESSION['lb_checked_out'] = false;
  }
}

// list of common inclusions:
if (file_exists($mosConfig_absolute_path."/components/com_zoom/language/".$mosConfig_lang.".php")){ 
	include($mosConfig_absolute_path."/components/com_zoom/language/".$mosConfig_lang.".php");
}else{ 
	include($mosConfig_absolute_path."/components/com_zoom/language/english.php");
}
if($zoom->isWin()){
	include($mosConfig_absolute_path.'/components/com_zoom/classes/fs_win32.php');
}else{
	include($mosConfig_absolute_path.'/components/com_zoom/classes/fs_unix.php');
}

// Update the Edit Monitor, eg. delete unnecessary rows
$zoom->_EditMon->updateEditMon();

// prevent Itemid missing...
if (!isset($Itemid) || empty($Itemid)){
    $Itemid = $zoom->getItemid($option);
}

// load gallery object if a catid is specified...
$catid = mosGetParam($_REQUEST,'catid');
if (isset($catid) && !is_array($catid) && !empty($catid)){
	$zoom->setGallery($catid);
}
if ($zoom->_isBackend) {
	$backend = "2";
} else {
	$backend = "";
}
// Standard (D)HTML...
?>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script language="JavaScript" type="text/JavaScript" src="includes/js/overlib_mini.js"></script>
<?php
$page = mosGetParam($_REQUEST,'page');
switch ($page)
{
case 'editpic':
	if($zoom->_isAdmin || ($zoom->_isUser && $zoom->_CONFIG['allowUserUpload'])){
		include($mosConfig_absolute_path.'/components/com_zoom/editimg.php');
	}else{
		echo "Error: You'll have to be logged in as admin or user/ editor to view this page!";
	}
	break;
case 'view':
	include($mosConfig_absolute_path.'/components/com_zoom/view.php');
	break;
case 'special':
	include($mosConfig_absolute_path.'/components/com_zoom/special.php');
	break;
// Admin module pages...
case 'admin':
	if($zoom->_isAdmin || ($zoom->_isUser && $zoom->_CONFIG['allowUserUpload'])){
		include($mosConfig_absolute_path.'/components/com_zoom/admin/admin.php');
		$zoom->adminFooter();
	}else{
		echo "Error: You'll have to be logged in as admin or user/ editor to view this page!";
	}
	break;
case 'catsmgr':
	if($zoom->_isAdmin || ($zoom->_isUser && $zoom->_CONFIG['allowUserUpload'] && $zoom->_CONFIG['allowUserEdit'])){
		include($mosConfig_absolute_path.'/components/com_zoom/admin/catsmgr.php');
		$zoom->adminFooter();
	}else{
		echo "Error: You'll have to be logged in as admin or user/ editor to view this page, or you're rights are limited!";
	}
	break;
case 'mediamgr':
	if($zoom->_isAdmin || ($zoom->_isUser && $zoom->_CONFIG['allowUserUpload'] && $zoom->_CONFIG['allowUserEdit'])){
		include($mosConfig_absolute_path.'/components/com_zoom/admin/mediamgr.php');
		$zoom->adminFooter();
	}else{
		echo "Error: You'll have to be logged in as admin or user/ editor to view this page, or you're rights are limited!";
	}
	break;
case 'new':
	if($zoom->_isAdmin || ($zoom->_isUser && $zoom->_CONFIG['allowUserUpload']) && $zoom->_CONFIG['allowUserCreate']){
		include($mosConfig_absolute_path.'/components/com_zoom/admin/new.php');
		$zoom->adminFooter();
	}else{
		echo "Error: You'll have to be logged in as admin or user/ editor to view this page, or you're rights are limited!";
	}
	break;
case 'upload':
	if($zoom->_isAdmin || ($zoom->_isUser && $zoom->_CONFIG['allowUserUpload'])){
   		include($mosConfig_absolute_path.'/components/com_zoom/admin/upload.php');
   		$zoom->adminFooter();
    }else{
		echo "Error: You'll have to be logged in as admin or user/ editor to view this page, or you're rights are limited!";
	}
    break;
case 'settings':
	if($zoom->_isAdmin){
		include($mosConfig_absolute_path.'/components/com_zoom/admin/settings.php');
		$zoom->adminFooter();
	}else{
		echo "Error: You'll have to be logged in as admin to view this page!";
	}
	break;
case 'update':
	if($zoom->_isAdmin){
		include($mosConfig_absolute_path.'/components/com_zoom/admin/update.php');
		$zoom->adminFooter();
	}else{
		echo "Error: You'll have to be logged in as admin to view this page!";
	}
	break;
case 'movefiles':
	if($zoom->_isAdmin){
		include($mosConfig_absolute_path.'/components/com_zoom/admin/movefiles.php');
		$zoom->adminFooter();
	}else{
		echo "Error: You'll have to be logged in as admin to view this page!";
	}
	break;
case 'credits':
	if($zoom->_isAdmin || ($zoom->_isUser && $zoom->_CONFIG['allowUserUpload'])){
   		include($mosConfig_absolute_path.'/components/com_zoom/admin/credits.php');
   		$zoom->adminFooter();
    }else{
		echo "Error: You'll have to be logged in as admin or user/ editor to view this page!";
	}
    break;
case 'lightbox':
	if($zoom->_CONFIG['lightbox'])
		include($mosConfig_absolute_path.'/components/com_zoom/lightbox.php');
	else
		echo "Error: You're not authorized to access this page!";
	break;
case 'ecard':
	if($zoom->_CONFIG['ecards'])
		include($mosConfig_absolute_path.'/components/com_zoom/ecard.php');
	else
		echo "Error: You're not authorized to access this page!";
	break;
case 'search':
	include($mosConfig_absolute_path.'/components/com_zoom/search.php');
	break;
default:
	$action = mosGetParam($_REQUEST,'action');
	if ($action === 'delimg') {
		if($zoom->_isAdmin || ($zoom->_isUser && $zoom->_CONFIG['allowUserUpload'])){
			$key = mosGetParam($_REQUEST,'key');
			if ($key){
				$zoom->_gallery->_images[$key]->getInfo();
				if(!$zoom->_gallery->_images[$key]->delete()){
					echo "<center><h4>"._ZOOM_ALERT_NODELPIC."</h4></center><br /><br />";
				}else{
					echo "<center><h4>"._ZOOM_ALERT_DELPIC."</h4></center><br /><br />";
				}
				// reset the gallery object, key and action variable to prevent errors...
				unset($zoom->_gallery, $key, $action);
				$zoom->setGallery($catid);
			}
		}else{
			echo "Error: You'll have to be logged in as admin or user/ editor to delete a medium!";
		}
	}
	if($zoom->_gallery->_published || !isset($catid) || $zoom->_isAdmin){
    	include($mosConfig_absolute_path.'/components/com_zoom/galleryshow.php');
    }else{
    	echo "Error: You're trying to access a restricted area! Please contact the Webmaster.";
    }
    break;
}
?>

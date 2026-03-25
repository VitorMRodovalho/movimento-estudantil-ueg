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
| Filename: save_dnd.php                                              |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
define( "_VALID_MOS", 1 );
function mosGetParam( &$arr, $name, $def=null, $mask=0 ) {
	$return = null;
	if (isset( $arr[$name] )) {
	    if (is_string( $arr[$name] )) {
			$arr[$name] = trim( $arr[$name] );
			$arr[$name] = strip_tags( $arr[$name] );
		}
		return $arr[$name];
	} else {
		return $def;
	}
} 
echo "Processing images from list...<br /><br />";
	$catid = mosGetParam($_REQUEST,'catid');
	$uid = mosGetParam($_REQUEST,'uid');
	$name = mosGetParam($_REQUEST,'name');
	$setFilename = mosGetParam($_REQUEST,'setFilename');
	$keywords = mosGetParam($_REQUEST,'keywords');
	$descr = mosGetParam($_REQUEST,'descr');	
    // $mosConfig_absolute_path = $_REQUEST['dnd_mospath'];
	if (!$catid){
		echo "No category specified, please select one from the list.";
		exit();
	}
	/*
	* Iterate over all received files.
	* PHP > 4.2 / 4.3 ? will save the file information into the
	* array $_FILES[]. Before these versions, the data was saved into
	* $HTTP_POST_FILES[]
	*/
	include('../../configuration.php');
	if (file_exists($mosConfig_absolute_path."/version.php")) {
		include($mosConfig_absolute_path."/version.php");
	}else{
		include($mosConfig_absolute_path."/includes/version.php");
	}
	// redefine the mambo database object to use the comment function...
	require($mosConfig_absolute_path.'/includes/database.php');
	$database = new database( $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix );
	// Include zOOm configuration
	include($mosConfig_absolute_path.'/components/com_zoom/zoom_config.php');
	// Create zOOm Image Gallery object
	require('classes/zoom.class.php');
	require('classes/editmon.class.php'); //like a common session-monitor...
	require('classes/gallery.class.php');
	require('classes/image.class.php');
	require('classes/comment.class.php');
    require('classes/toolbox.class.php');
    require('classes/ecard.class.php');
    require('classes/lightbox.class.php');
	$zoom = new zoom();
    // now create an instance of the ToolBox!
    $zoom->_toolbox = new toolbox();
    
	$zoom->setGallery($catid, true);
	$zoom->_isAdmin = true; //set this manually, so language file can be read completely...
	$zoom->_CurrUID = $uid;
	
	// inclusion of filesystem-functions, platform dependent.
	if($zoom->isWin())
		include('classes/fs_win32.php');
	else
		include('classes/fs_unix.php');
	
	if (file_exists("language/".$mosConfig_lang.".php") ) { 
		include_once("language/".$mosConfig_lang.".php");
	} else { 
		include_once("language/english.php");
	}
	if ($zoom->_CONFIG['readEXIF']) {
		include($mosConfig_absolute_path."/components/com_zoom/classes/iptc/JPEG.php");
		include($mosConfig_absolute_path."/components/com_zoom/classes/iptc/EXIF.php");
		include($mosConfig_absolute_path."/components/com_zoom/classes/iptc/Photoshop_IRB.php");
		include($mosConfig_absolute_path."/components/com_zoom/classes/iptc/XMP.php");
		include($mosConfig_absolute_path."/components/com_zoom/classes/iptc/Photoshop_File_Info.php");
	}
	// counter:
 	$i = 0;
 		
	foreach($_FILES as $tagname=>$objekt){
 		// get the temporary name (e.g. /tmp/php34634.tmp)
 		$tempName = $objekt['tmp_name'];
	 	// get the real filename
 		$realName = urldecode($objekt['name']);
 		if(isset($setFilename))
 			$name = $realName;
 		if ($zoom->_CONFIG['autonumber'])
			$name = $name." ".($i+1);
 		if ($realName != ""){
 			echo _ZOOM_INFO_PROCESSING." ".$realName."...";
	 		//Check for right format
            if($zoom->_toolbox->processImage($tempName,$realName,$keywords,$name,$descr,false)){
                echo "<b>"._ZOOM_INFO_DONE."</b><br />";
                $i++;
            }else{
                echo "<b>error!</b><br />";
            }
        }
	} // end of for-loop FILES
    if($zoom->_toolbox->_err_num > 0)
	   $zoom->_toolbox->displayErrors($err_num, $err_names, $err_types);
	echo "<b>".$i." "._ZOOM_ALERT_UPLOADSOK."</b><br />";
?>

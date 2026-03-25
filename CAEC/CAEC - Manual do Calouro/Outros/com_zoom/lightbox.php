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
| Filename: lightbox.php                                              |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
-----------------------------------------------------------------------
|                                                                     |
| Ok, what is a lightbox? First of all, it's a little cardboard box,  |
| containing two dozen or so matches.                                 |
| The digital lightbox is somewhat the same: it's a box (ZIP-file),   |
| containing a dozen or so images, which the user selected.           |
| The idea is, that users can download a gallery filled with images   |
| at once, intead of downloading each image individually...that would |
| be a bore...:-p  Plus in a nice package too!                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$PageNo = mosGetParam($_REQUEST,'PageNo');
$action = mosGetParam($_REQUEST,'action'); 
?>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
	<td align="center" width="100%"><a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo);?>">
		<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?php echo _ZOOM_BACKTOGALLERY;?>" border="0">&nbsp;&nbsp;<?php echo _ZOOM_BACKTOGALLERY;?></a>&nbsp; | &nbsp;
	</td>
</tr>
</table>
<?php
// get files into array...
if($action == 'add'){
	$key = mosGetParam($_REQUEST,'key');
	if($key){
		$key = $_REQUEST['key'];
		$object_id = $zoom->_gallery->_images[$key]->_id;
		$type = 1;
	}else{
		$object_id = $zoom->_gallery->_id;
		$type = 2;
	}
	if($_SESSION['lightbox']->addItem($object_id, $type)){
		?>
		<script language="JavaScript" type="text/JavaScript">
			alert("<?php echo html_entity_decode( _ZOOM_LIGHTBOX_ADDED ); ?>");
			location = "<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo);?>";
		</SCRIPT>
		<?php
	}else{
		?>
		<script language="JavaScript" type="text/JavaScript">
			alert("<?php echo html_entity_decode(_ZOOM_LIGHTBOX_NOTADDED);?>");
			location = "<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo);?>";
		</SCRIPT>
		<?php
	}
}elseif($action == 'edit'){
	$id = mosGetParam($_REQUEST,'id');
	$qty = mosGetParam($_REQUEST,'qty'); 
	if($_SESSION['lightbox']->editItem($id, $qty)){
		?>
		<script language="JavaScript" type="text/JavaScript">
			alert("<?php echo html_entity_decode(_ZOOM_LIGHTBOX_EDITED);?>");
			location = "<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo);?>";
		</SCRIPT>
		<?php
	}else{
		?>
		<script language="JavaScript" type="text/JavaScript">
			alert("<?php echo html_entity_decode(_ZOOM_LIGHTBOX_NOTEDITED);?>");
			location = "<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo);?>";
		</SCRIPT>
		<?php
	}
}elseif($action == 'remove'){
	$id = mosGetParam($_REQUEST,'id');
	if($_SESSION['lightbox']->getNoOfItems() == 1){
		$lightbox = $_SESSION['lightbox'];
		unset($_SESSION['lightbox'], $lightbox);
		?>
		<script language="JavaScript" type="text/JavaScript">
			alert("<?php echo html_entity_decode(_ZOOM_LIGHTBOX_DEL);?>");
			location = "<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo);?>";
		</SCRIPT>
		<?php
	}elseif($_SESSION['lightbox']->removeItem($id)){
		?>
		<script language="JavaScript" type="text/JavaScript">
			alert("<?php echo html_entity_decode(_ZOOM_LIGHTBOX_DEL);?>");
			location = "<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo);?>";
		</SCRIPT>
		<?php
	}else{
		?>
		<script language="JavaScript" type="text/JavaScript">
			alert("<?php echo html_entity_decode(_ZOOM_LIGHTBOX_NOTDEL);?>");
			location = "<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo);?>";
		</SCRIPT>
		<?php
	}
}elseif($action == 'zip'){
	// tell the lightbox to output the zip-file...
	if(!$_SESSION['lb_checked_out'] && $_SESSION['lightbox']->getNoOfItems() > 0){
  	$_SESSION['lightbox']->createZipFile();
  	$_SESSION['lb_checked_out'] = true;
  	unset($_SESSION['lightbox']);
  }else{
  	?>
		<script language="JavaScript" type="text/JavaScript">
			alert("<?php echo html_entity_decode(_ZOOM_LIGHTBOX_NOZIP);?>");
			location = "<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo);?>";
		</SCRIPT>
		<?php
  }
}else{
	//no action is set, so view the contents of the lightbox...
	?>
 <h3><img src="components/com_zoom/images/lightbox.png" border="0">
		<?php echo _ZOOM_YOUR_LIGHTBOX;?></h3>
 <br /><br />
	<form name="lightbox" method="post" action="index.php?option=com_zoom&page=lightbox">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr class="sectiontableheader">
		<td align="left" valign="middle" width="<?php echo $zoom->_CONFIG['size'];?>"><?php echo _ZOOM_LIGHTBOX_CATS;?></td>
		<td align="left" valign="middle"><?php echo _ZOOM_LIGHTBOX_TITLEDESCR;?></td>
		<td align="left" valign="middle"><?php echo _ZOOM_ACTION;?></td>
	</tr>
	<?php
	// first, iterate over all the galleries in the current lightbox...
	$zoom->_counter = 0;
	if(!empty($_SESSION['lightbox']->_items)){
  	foreach($_SESSION['lightbox']->_items as $item){
  		if(isset($item)){
  			if(isset($item->_gallery) && !empty($item->_gallery)){
  				echo ("<tr>\n"
  				 . "\t<td align=\"left\" valign=\"middle\"><img src=\"".$mosConfig_live_site."/components/com_zoom/images/folder.png\" /></td>\n"
  				 . "\t<td align=\"left\" valign=\"middle\">\n"
  				 . "\t\t".$item->_gallery->_name."<br />".$item->_gallery->_descr."\n"
  				 . "\t</td>\n"
  				 . "\t<td>\n"
  				 . "\t\t<a href=\"index.php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&action=remove&id=".$zoom->_counter."&catid=".$catid."&PageNo=".$PageNo."\" onmouseover=\"return overlib('"._ZOOM_DELETE."');\" onmouseout=\"return nd();\">\n"
  				 . "\t\t<img src=\"components/com_zoom/images/delete.png\" border=\"0\" onmouseover=\"MM_swapImage('delimgA".$zoom->_counter."','','".$mosConfig_live_site."/components/com_zoom/images/delete_f2.png',1);\" onmouseout=\"MM_swapImgRestore();\" name=\"delimgA".$zoom->_counter."\"></a>\n"
  				 . "</td>\n"
  				 . "</tr>\n");
            }
        }
  		$zoom->_counter++;
  	}
    $zoom->_counter = 0;
    // NOW, list all images...
    foreach($_SESSION['lightbox']->_items as $item){
        if(isset($item)){
            if(isset($item->_image) && !empty($item->_image)){
  				$item->_image->getInfo();
  				$zoom->setGallery($item->_image->_catid);
  				echo ("<tr>\n"
  				 . "\t<td align=\"left\" valign=\"middle\">n.a.&nbsp;</td>\n"
  				 . "\t<td align=\"left\" valign=\"middle\">\n"
  				 . "\t\t<img src=\"".$item->_image->_thumbnail."\" align=\"left\" hspace=\"3\" />\n"
  				 . "\t\t".$item->_image->_name."<br />".$item->_image->_descr."\n"
  				 . "\t</td>\n"
  				 . "\t<td align=\"left\" valign=\"middle\">\n"
  				 . "\t\t<a href=\"index.php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&action=remove&id=".$zoom->_counter."&catid=".$catid."&PageNo=".$PageNo."\" onmouseover=\"return overlib('"._ZOOM_DELETE."');\" onmouseout=\"return nd();\">\n"
  				 . "\t\t<img src=\"components/com_zoom/images/delete.png\" border=\"0\" name=\"delimgB".$zoom->_counter."\"></a>\n"
  				 . "\t</td>\n"
  				 . "</tr>\n");
  			}
        }
        $zoom->_counter++;
    }
	}
	?>
	</table><br />
	<center>
			<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
			<input type="hidden" name="catid" value="<?php echo $catid;?>" />
			<input type="hidden" name="PageNo" value="<?php echo $PageNo;?>" />
			<input type="hidden" name="action" value="zip" />
			<input type="submit" name="submit" value="<?php echo _ZOOM_LIGHTBOX_ZIPBTN;?>" class="button" />
		</form>
	</center>
	<?php
}
?>                               


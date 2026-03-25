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
| Filename: movefiles.php                                             |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$zoom->createSubmitScript("select_cat");
$zoom->createCheckAllScript();
?>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td width="33%" align="left">
			<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/move_f2.png" border="0" alt="<?php echo _ZOOM_MOVEFILES;?>" />
			&nbsp;
			<strong><font size="4"><?php echo _ZOOM_MOVEFILES;?></font></strong>
		</td>

		<td width="33%" align="center">
			<a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=admin";?>">
			<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN;?>" border="0" />
			<?php echo _ZOOM_MAINSCREEN;?></a>
		</td>

		<td width="33%" align="right">&nbsp;</td>
	</tr>
</table>
<?php
$wannamoves = mosGetParam($_REQUEST,'wannamoves');
if(isset($wannamoves) && !empty($wannamoves) && is_array($wannamoves)){
	//First, copy the file to the target gallery-dir. Then, second, delete the
	//image from the source gallery (meanwhile, update the database ;-) )...
	$movecat = mosGetParam($_REQUEST,'movecat');
	foreach($wannamoves as $wannamove){
		//get dir of source gallery...
		$database->setQuery("SELECT catdir FROM #__zoom WHERE catid = ".$movecat." LIMIT 1");
		$result = $database->query();
		$row = mysql_fetch_object($result);
		$movedir = $row->catdir;
		// get filename of image,,,
		$database->setQuery("SELECT imgfilename FROM #__zoomfiles WHERE imgid = $wannamove LIMIT 1");
		$result = $database->query();
		$row = mysql_fetch_object($result);
		$filename = $row->imgfilename;
		//now copy the file...
		if(fs_copy($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$filename, $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$movedir."/".$filename)){
			//delete the file from the OLD location...
			if(fs_unlink($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$filename)){
				//Do the same for the thumbnail...
				if(fs_copy($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/thumbs/".$filename, $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$movedir."/thumbs/".$filename)){
					if(fs_unlink($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/thumbs/".$filename)){
						//update dbase...
						$database->setQuery("UPDATE #__zoomfiles SET catid = $movecat WHERE imgid = $wannamove");
						$database->query();
						?>
						<script language="javascript" type="text/javascript">
							<!--
							alert('<?php echo html_entity_decode(_ZOOM_ALERT_MOVEOK);?>');
							location = "<?php echo "index".$backend.".php?option=com_zoom&page=admin&Itemid=".$Itemid;?>";
							//-->
						</SCRIPT>
						<?php
					}else{
						?>
						<script language="javascript" type="text/javascript">
							<!--
							alert('<?php echo html_entity_decode(_ZOOM_ALERT_NODELPIC);?>');
							location = "<?php echo "index".$backend.".php?option=com_zoom&page=admin&Itemid=".$Itemid;?>";
							//-->
						</SCRIPT>
						<?php
					}
				}else{
					?>
					<script language="javascript" type="text/javascript">
						<!--
						alert('<?php echo html_entity_decode(_ZOOM_ALERT_MOVEFAILURE);?>');
						location = "<?php echo "index".$backend.".php?option=com_zoom&page=admin&Itemid=".$Itemid;?>";
						//-->
					</SCRIPT>
					<?php
				}
			}else{
				?>
				<script language="javascript" type="text/javascript">
					<!--
					alert('<?php echo html_entity_decode(_ZOOM_ALERT_NODELPIC);?>');
					location = "<?php echo "index".$backend.".php?option=com_zoom&page=admin&Itemid=".$Itemid;?>";
					//-->
				</SCRIPT>
				<?php
			}
		}else{
			?>
			<script language="javascript" type="text/javascript">
				<!--
				alert('<?php echo html_entity_decode(_ZOOM_ALERT_MOVEFAILURE);?>');
				location = "<?php echo "index".$backend.".php?option=com_zoom&page=admin&Itemid=".$Itemid;?>";
				//-->
			</SCRIPT>
			<?php
		}
	}
}elseif(isset($catid) && !empty($catid)){
	//Step 2: select images...
	//zOOm already generated an array with images, so we just have to walk through them...
	?>
	<h3><?php echo _ZOOM_MOVEFILES_STEP1;?></h3>
	<br />
	<center>
	<form name="select_cat" action="index<?php echo ($zoom->_isBackend) ? "2" : "";?>.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=movefiles" method="post">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="center">
		<?php echo $zoom->createCatDropDown('catid', '<option value="">---&nbsp;'._ZOOM_PICK.'&nbsp;---</option>', 1, $catid);?>
		</td>
	</tr>
	</table>
	</form>
	</center>
	<h3><?php echo _ZOOM_MOVEFILES_STEP2;?></h3>
	<br /><br />
	<center>
	<?php
	//Step 3: select target gallery & upload images...
	?>
	<form name="select_img" method="post"action="index<?php echo ($zoom->_isBackend) ? "2" : "";?>.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=movefiles">
	<table cellpadding="0" cellspacing="0" border="0" width="90%">
	<tr class="sectiontableheader">
		<td width="50" class="sectiontableheader">&nbsp;</td><td class="sectiontableheader" width="50%">&nbsp;<?php echo _ZOOM_IMGNAME;?></td><td class="sectiontableheader" width="50%"><?php echo _ZOOM_FILENAME;?></td><td class="sectiontableheader" width="<?php echo $zoom->_CONFIG['maxsize'];?>"><?php _ZOOM_HD_PREVIEW;?></td>
	</tr>
	<?php
	if($zoom->_gallery->getNumOfImages() >= 0 ){
		$tabcnt = 0;
		foreach($zoom->_gallery->_images as $image){
			if($tabcnt > 1)
				$tabcnt = 0;
			$image->getInfo();
			echo '<tr class="'.$zoom->_tabclass[$tabcnt].'"><td align="center"><input type="checkbox" name="wannamoves[]" value="'.$image->_id.'"></td><td>'.$image->_name.'</td><td>'.$image->_filename.'</td><td><img src="'.$image->_thumbnail.'" border="0"></td></tr>';
			$tabcnt++;
		}
	}
	?>
	<tr>
		<td  height="20" class="sectiontableheader" align="center"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this, 'wannamoves[]');"></td>
		<td  height="20" class="sectiontableheader" colspan="3">
			<strong><?php echo _ZOOM_HD_CHECKALL;?></strong>
		</td>
	</tr>
	</table>
	<input type="hidden" name="catid" value="<?php echo $catid;?>">
	</center>
	<h3><?php echo _ZOOM_MOVEFILES_STEP3;?></h3>
	<center>
	<?php echo $zoom->createCatDropDown('movecat', '<option value="">---&nbsp;'._ZOOM_PICK.'&nbsp;---</option>');?>
	<br /><br />
	<input class="button" type="submit" value="<?echo _ZOOM_BUTTON_MOVE;?>" name="submit">
	</center>
	</form>
	<?php
}else{
	//Step 1: select source gallery...
	?>
	<h3><?php echo _ZOOM_MOVEFILES_STEP1;?></h3>
	<br /><br />
	<center>
	<form name="select_cat" action="index<?php echo ($zoom->_isBackend) ? "2" : "";?>.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=movefiles" method="post">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="center">
		<?php echo $zoom->createCatDropDown('catid', '<option value="">---&nbsp;'._ZOOM_PICK.'&nbsp;---</option>', 1, $catid);?>
		</td>
	</tr>
	</table>
	</form>
	</center>
	<?php
}
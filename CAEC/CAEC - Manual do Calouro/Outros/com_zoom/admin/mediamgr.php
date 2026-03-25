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
| Filename: mediamgr.php                                              |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$task = mosGetParam($_REQUEST,'task');
$return = "mediamgr";
if($task == 'edit'){
    $keys = mosGetParam($_REQUEST,'keys');
    if(!empty($keys) || $keys == 0){
        if(is_array($keys)) {
        	$key = $keys[0];
        } else {
        	$key = $keys;
        }
        include($mosConfig_absolute_path.'/components/com_zoom/editimg.php');
    }else{
		//Back to new gallery page
		?>
		<script language="javascript" type="text/javascript">
		<!--
			alert("<?php echo html_entity_decode(_ZOOM_ALERT_NOMEDIA);?>");
			location = "<?php echo "index".$backend.".php?option=com_zoom&page=mediamgr&Itemid=".$Itemid."&catid=".$catid;?>";
		//-->
		</SCRIPT>
		<?
	}
}elseif($task == 'move'){
	// not implemented yet...
}elseif($task == 'delete'){
	$keys = mosGetParam($_REQUEST,'keys');
	if($keys){
        foreach ($keys as $key){
        	$zoom->_gallery->_images[$key]->getInfo();
			if($zoom->_gallery->_images[$key]->delete()){
				?>
				<script language="javascript" type="text/javascript">
				<!--
					alert("<?php echo html_entity_decode(_ZOOM_ALERT_NODELPIC);?>");
					location = "<?php echo "index".$backend.".php?option=com_zoom&page=mediamgr&Itemid=".$Itemid."&catid=".$catid;?>";
				//-->
				</SCRIPT>
				<?php
			}else{
				?>
				<script language="javascript" type="text/javascript">
				<!--
					alert("<?php echo html_entity_decode(_ZOOM_ALERT_DELPIC);?>");
					location = "<?php echo "index".$backend.".php?option=com_zoom&page=mediamgr&Itemid=".$Itemid."&catid=".$catid;?>";
				//-->
				</SCRIPT>
				<?php
			}	
        }
	}else{
		?>
		<script language="javascript" type="text/javascript">
		<!--
			alert("<?php echo html_entity_decode(_ZOOM_ALERT_NOMEDIA);?>");
			location = "<?php echo "index".$backend.".php?option=com_zoom&page=mediamgr&Itemid=".$Itemid."&catid=".$catid;?>";
		//-->
		</SCRIPT>
		<?php
	}
}else{
	$zoom->createSubmitScript('catselect');
	?>
	<br />
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td align="center" width="100%"><a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=admin";?>">
		<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN;?>" border="0">&nbsp;&nbsp;<?php echo _ZOOM_MAINSCREEN;?></a>&nbsp; | &nbsp;
		</td>
	</tr>
	<tr>
		<td align="left"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/mediamgr_f2.png" border="0" alt="<?php echo _ZOOM_MEDIAMGR;?>">&nbsp;<b><font size="4"><?php echo _ZOOM_MEDIAMGR;?></font></b></td>
	</tr>
	</table>
	<br />
	<center>
	<form enctype="multipart/form-data" name="catselect" method="post" action="index<?php echo ($zoom->_isBackend) ? "2" : "";?>.php?option=com_zoom&page=mediamgr&catid=<?php echo $catid;?>&Itemid=<?php echo $Itemid;?>">
	<?php
	// display gallery selection form... 
	echo $zoom->createCatDropdown('catid', '<OPTION value="">---&nbsp;'._ZOOM_PICK.'&nbsp;---</OPTION>', 1, $catid);
	?>
	</form>
	<?php
	if(!empty($catid)){
		// display form containing editable media from the specified gallery...
		$zoom->createCheckAllScript();
		?>
		<script language="Javasript" type="text/javascript">
	      <!--
	      function submitForm(theTask){
	        document.mediamgr.elements['task'].value = theTask;
	        document.mediamgr.submit();
	        return false;
	      }
	      //-->
	    </script>
		<table width="80%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right">
				<div align="right">
				<?php if($zoom->_isAdmin || $zoom->_CONFIG['allowUserEdit']){ ?>
				  <a href="<?php echo "index".$backend.".php?option=com_zoom&page=upload&return=mediamgr&catid=".$catid."&Itemid=".$Itemid;?>" onmouseover="return overlib('<?php echo _ZOOM_UPLOAD;?>');" onmouseout="return nd();"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/new.png" border="0" onmouseover="MM_swapImage('new','','<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/new_f2.png',1);" onmouseout="MM_swapImgRestore();" name="new"></a>
	              <a href="javascript:submitForm('edit');" onmouseover="return overlib('<?php  echo _ZOOM_BUTTON_EDIT;?>');" onmouseout="return nd();"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/edit.png" border="0" onmouseover="MM_swapImage('edit','','<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/edit_f2.png',1);" onmouseout="MM_swapImgRestore();" name="edit"></a>
	            <?php } if($zoom->_isAdmin || $zoom->_CONFIG['allowUserDel']){ ?>
	              <a href="javascript:submitForm('delete');" onmouseover="return overlib('<?php echo _ZOOM_DELETE;?>');" onmouseout="return nd();"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/delete.png" border="0" onmouseover="MM_swapImage('delete','','<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/delete_f2.png',1);" onmouseout="MM_swapImgRestore();" name="delete"></a>
	            <?php } ?>
	            </div>
			</td>
		</tr>
		</table>
		<form  name="mediamgr" action="index<?php echo ($zoom->_isBackend) ? "2" : "";?>.php?option=com_zoom&page=mediamgr&Itemid=<?php echo $Itemid;?>" method="POST">
	    <input type="hidden" name="task" value="" />
	    <input type="hidden" name="return" value="mediamgr" />
	    <input type="hidden" name="catid" value="<?php echo $zoom->_gallery->_id;?>" />
		<table width="80%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center">
				<div align="center">
				<?php echo $zoom->createMediaEditForm(); ?>
				</div>
			<td>
		</tr>
		</table>
		</form>
		</center>
		<?php
	}
}
?>
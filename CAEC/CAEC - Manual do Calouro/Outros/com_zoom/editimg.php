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
| Filename: editimg.php                                               |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$submita = mosGetParam($_REQUEST,'submita');
$return = mosGetParam($_REQUEST,'return');
if ($page == "mediamgr") {
	$return = "mediamgr";
}
$keys = mosGetParam($_REQUEST,'keys');
if(!empty($keys) || $keys == 0){
	if(is_array($keys)) {
		$key = $keys[0];
	} else {
		$key = $keys;
	}
}
if (!isset($key)) {
	$key = mosGetParam($_REQUEST,'key');
}
if ($submita){
	$newname = mosGetParam($_REQUEST,'newname');
	$newkeywords = mosGetParam($_REQUEST,'keywords');
	$newdescr = mosGetParam($_REQUEST,'newdescr');
	$catimg = mosGetParam($_REQUEST,'catimg');
	$parentimg = mosGetParam($_REQUEST,'parentimg');
	$published = mosGetParam($_REQUEST,'published');
    $selections = mosGetParam($_REQUEST,'selections');
	//Save in database
    if(empty($selections))
        $selections = 1;
    else
        $selections = implode(',', $selections);
    $newkeywords = trim(ereg_replace(" ", "", $newkeywords));
	$zoom->_gallery->_images[$key]->setImgInfo($newname, $newkeywords, $newdescr, $catimg, $parentimg, $published, $selections);
	if ($zoom->_isBackend) {
		$index = "index2.php";
	}else{
		$index = "index.php";
	}
	if(!isset($return) || empty($return)){
		$loc = "$index?option=com_zoom&Itemid=$Itemid&catid=$catid&PageNo=$PageNo";
	}else{
		$loc = "$index?option=com_zoom&Itemid=$Itemid&catid=$catid&page=$return";
	}
	?>
	<script language="javascript" type="text/javascript">
		<!--
		alert("<?php echo html_entity_decode(_ZOOM_ALERT_EDITIMG);?>");
		location = "<?php echo $loc;?>";
		//-->
	</script>
	<?php
}else{
    //Laat scherm zien met beschrijving van afbeelding
    $zoom->_gallery->_images[$key]->getInfo();
	?>
    <center>
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    <?php
    if(!isset($return) || empty($return)){
    ?>
	<tr>
		<td align="left">
           <a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid;?>">
			<img src="components/com_zoom/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN;?>" border="0">&nbsp;&nbsp;<?php echo _ZOOM_MAINSCREEN;?>
			</a>
			<?php
			if ($zoom->_gallery->_pos==0) echo " > ";
			elseif ($zoom->_gallery->_pos==1) echo " > <a href=\"index.php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_subcat_id."\">".$parent."</a> > ";
			elseif ($zoom->_gallery->_pos>=2) echo " >..> <a href=\"index.php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_subcat_id."\">".$parent."</a> > ";
			echo "<a href=\"index.php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo."\">".$zoom->_gallery->_name."</a>";
			if(!$zoom->_gallery->_published)
				echo " <font color=\"red\">(unpublished)</font>";
			if($zoom->_isAdmin){
            	print ' | <a href="index.php?option=com_zoom&Itemid=' .$Itemid. '&page=admin">'._ZOOM_ADMINSYSTEM.'</a>';
			}else if($zoom->_isUser && $zoom->_CONFIG['allowUserUpload']){
				print ' | <a href="index.php?option=com_zoom&Itemid=' .$Itemid. '&page=admin">'._ZOOM_USERSYSTEM.'</a>';
			}
	   		?>
		</td>
		<td>&nbsp;</td>
	</tr>
	<?php
    }else{
    ?>
    <tr>
		<td align="center" width="100%"><a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=admin";?>">
			<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?echo _ZOOM_MAINSCREEN;?>" border="0">&nbsp;&nbsp;<?echo _ZOOM_MAINSCREEN;?>
		</a>&nbsp; | &nbsp;
		<a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&catid=".$catid."&page=mediamgr";?>">
			<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/back.png" alt="<?echo _ZOOM_BACK;?>" border="0">&nbsp;&nbsp;<?php echo _ZOOM_BACK;?>
		</a>
		</td>
	</tr>
    <?php
    }
    ?>
	<tr>
		<td colspan="2" align="center"><h3><?php echo _ZOOM_EDITPIC;?></h3></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<img src="<?php echo $zoom->_gallery->_images[$key]->_thumbnail;?>" alt="" border="1" width="75">
		</td>
	</tr>
    </table>
    <table cellspacing="0" cellpadding="4" border="0" width="100%">
    <tr>
		<td width="85%" class="tabpadding" align="center">
			<a href="javascript:document.forms.editimg.submit();" onmouseout="MM_swapImgRestore();return nd();"  onmouseover="MM_swapImage('save','','images/save_f2.png',1);return overlib('<?php echo _ZOOM_SAVE;?>');"><img src="images/save.png" alt="<?php echo _ZOOM_SAVE;?>" border="0" name="save" /></a>
			<a href="javascript:document.forms.editimg.reset();" onmouseout="MM_swapImgRestore();return nd();"  onmouseover="MM_swapImage('cancel','','images/cancel_f2.png',1);return overlib('<?php echo _ZOOM_RESET;?>');"><img src="images/cancel.png" alt="<?php echo _ZOOM_RESET;?>" border="0" name="cancel" /></a>
		</td>
	</tr>
	</table>
	<form method="post" name="editimg" action="index<?php echo ($zoom->_isBackend) ? "2" : "";?>.php?option=com_zoom&page=editpic&Itemid=<?php echo $Itemid; echo (isset($return)) ? "&return=".$return : "";?>">
	<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="<?php echo $mosConfig_live_site;?>/components/com_zoom/tabs/tabpane.css" />
	<script language="javascript" type="text/javascript" src="<?php echo $mosConfig_live_site;?>/components/com_zoom/tabs/tabpane.js"></script>
	<div class="tab-page" id="modules-cpanel">
		<script language="javascript" type="text/javascript">
			<!--
			var tabPane1 = new WebFXTabPane( document.getElementById( "modules-cpanel" ), 0 )
			//-->
		</script>
		<div class="tab-page" id="module19">
			<h2 class="tab"><?php echo _ZOOM_ITEMEDIT_TAB1;?></h2>
			<script language="javascript" type="text/javascript">
				<!--
				tabPane1.addTabPage( document.getElementById( "module19" ) );
				//-->
			</script>
			<table border="0" width="400">
			<tr>
				<td><?php echo _ZOOM_FILENAME;?>: </td>
				<td><strong><?php echo $zoom->_gallery->_images[$key]->_filename;?></strong></td>
			</tr>
			<tr>
				<td><?php echo _ZOOM_IMGNAME;?>: </td>
				<td>
		        	<input type="textbox" name="newname" value="<?php echo $zoom->_gallery->_images[$key]->_name;?>" size="50" maxlength="50" class="inputbox">
				</td>
			</tr>
			<tr>
				<td><?php echo _ZOOM_KEYWORDS;?>: </td>
				<td valign="center"><input type="text" name="keywords" size="50" value="<?php echo $zoom->_gallery->_images[$key]->_keywords;?>" class="inputbox"></td>
			</tr>
			<tr>
				<td valign="top"><?php echo _ZOOM_DESCRIPTION;?>: </td>
				<td valign="top"><textarea cols="50" rows="5" name="newdescr" class="inputbox"><?php echo $zoom->_gallery->_images[$key]->_descr;?></textarea></td>
			</tr>
			</table>
			<table border="0" width="400">
			<tr>
				<td align="left" valign="top"><?php echo _ZOOM_SETCATIMG;?>: </td>
				<td align="left" valign="top">
					<input type="checkbox" name="catimg" value="1"<?php if($zoom->_gallery->_cat_img==$zoom->_gallery->_images[$key]->_id) echo " checked";?>>
				</td>
				</tr>
				<tr>
					<td align="left" valign="top"><?php echo _ZOOM_SETPARENTIMG;?>:</td>
					<td align="left" valign="top">			
						<input type="checkbox" name="parentimg" value="1"<?php if($zoom->_gallery->_images[$key]->isParentImage($zoom->_gallery->_subcat_id)) echo " checked";?>>
					</td>
				</tr>
				<tr>
					<td align="left"><?php echo _ZOOM_PUBLISHED;?>: </td>
					<td align="left">
						<input type="checkbox" name="published" value="1"<?php if($zoom->_gallery->_images[$key]->isPublished()) echo " checked";?>>
					</td>
				</tr>
		       </table>
	       </div>
	       <div class="tab-page" id="module20">
				<h2 class="tab"><?php echo _ZOOM_ITEMEDIT_TAB2;?></h2>
				<script language="javascript" type="text/javascript">
					<!--
					tabPane1.addTabPage( document.getElementById( "module20" ) );
					//-->
				</script>
		       <table border="0" width="300">
		       <tr>
		           <td>
		           <?php
		           $userlist = $zoom->getUsersList($zoom->_gallery->_images[$key]->_members);
		           foreach($userlist as $item){
		               echo $item."\n";
		           }
		           ?>
		           </td>
		       </tr>
		       </table>
	       </div>
		</div>
	</center>
	<input type="hidden" name="catid" value="<?php echo $zoom->_gallery->_id;?>">
	<input type="hidden" name="key" value="<?php echo $key;?>">
	<input type="hidden" name="return" value="<?php echo $return;?>" />
	<input type="hidden" name="PageNo" value="<?php echo $PageNo;?>">
	<input type="hidden" name="submita" value="submita">
	</form><br />
	<?php
}
?>

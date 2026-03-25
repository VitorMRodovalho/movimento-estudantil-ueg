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
| Filename: editcat.php                                               |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$newname = mosGetParam($_REQUEST,'newname');
if(array_key_exists('newname', $_REQUEST)){
	$newdescr = mosGetParam($_REQUEST,'newdescr');
	$catpass = mosGetParam($_REQUEST,'catpass');
	$keywords = mosGetParam($_REQUEST,'keywords');
	$hidemsg = mosGetParam($_REQUEST,'hidemsg');
	$shared = mosGetParam($_REQUEST,'shared');
	$published = mosGetParam($_REQUEST,'published');
	$selections = mosGetParam($_REQUEST,'selections');
	if (isset($newname)){
		//Save changes
		if(!isset($hidemsg)){
			$hidemsg = 0;
		}
		if($catpass != "" || !empty($catpass)){
			$catpass = md5($catpass);
		}else{
			$catpass = "";
		}
	    if(empty($selections))
	        $selections = 1;
	    else
	        $selections = implode(',', $selections);
	    // replace space-character with 'air'...or nothing!
	    $keywords = trim(ereg_replace(" ", "", $keywords));
		$database->setQuery("UPDATE #__zoom SET catname='".mysql_escape_string($newname)."', catdescr='".mysql_escape_string($newdescr)."', catpassword='".mysql_escape_string($catpass)."', catkeywords='".mysql_escape_string($keywords)."', hideMsg='$hidemsg', shared = '$shared', published='$published', catmembers='$selections' WHERE catid=".mysql_escape_string($catid));
		$database->query();
		//Unpublish/ publish the images of a gallery too...
		//Check if there are ANY images in the gallery...
		$database->setQuery("SELECT imgid FROM #__zoomfiles WHERE catid=$catid");
		$result = $database->query();
		if(mysql_num_rows($result) != 0){
			while($row = mysql_fetch_object($result)){
				if($published == 0)
					$database->setQuery("UPDATE #__zoomfiles SET published = 0 WHERE imgid = ".$row->imgid);
				else
					$database->setQuery("UPDATE #__zoomfiles SET published = 1 WHERE imgid = ".$row->imgid);
				$database->query();
			}
		}
		?>
		<script language="javascript" type="text/javascript">
			<!--
			alert('<?php echo html_entity_decode(_ZOOM_ALERT_EDITOK);?>');
			location = "<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=catsmgr";?>";
			//-->
		</SCRIPT>
		<?php
	}
}
?>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td align="center" width="100%">
			<a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=admin";?>">
				<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?echo _ZOOM_MAINSCREEN;?>" border="0">&nbsp;&nbsp;<?echo _ZOOM_MAINSCREEN;?>
			</a>&nbsp; | &nbsp;
			<a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=catsmgr";?>">
				<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/back.png" alt="<?echo _ZOOM_BACK;?>" border="0">&nbsp;&nbsp;<?php echo _ZOOM_BACK;?>
			</a>
		</td>
	</tr>
	<tr>
		<td align="left"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/edit_f2.png" border="0" alt="<?php echo _ZOOM_EDIT;?>">&nbsp;<b><font size="4"><?php echo _ZOOM_EDIT;?></font></b></td>
	</tr>
</table>
<br />
<center>
<table cellspacing="0" cellpadding="4" border="0" width="100%">
<tr>
     <td width="85%" class="tabpadding" align="center">
     	<a href="javascript:document.forms.edit_cat.submit();" onmouseout="MM_swapImgRestore();return nd();"  onmouseover="MM_swapImage('save','','images/save_f2.png',1);return overlib('<?php echo _ZOOM_SAVE;?>');"><img src="images/save.png" alt="<?php echo _ZOOM_BUTTON_CREATE;?>" border="0" name="save" /></a>
     	<a href="javascript:document.forms.edit_cat.reset();" onmouseout="MM_swapImgRestore();return nd();"  onmouseover="MM_swapImage('cancel','','images/cancel_f2.png',1);return overlib('<?php echo _ZOOM_RESET;?>');"><img src="images/cancel.png" alt="<?php echo _ZOOM_RESET;?>" border="0" name="cancel" /></a>
     </td>
</tr>
</table>
<form name="edit_cat" action="index<?php echo ($zoom->_isBackend) ? "2" : "";?>.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=catsmgr&task=edit" method="POST">
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
		<script type="text/javascript">
			tabPane1.addTabPage( document.getElementById( "module19" ) );
		</script>
		<input type="hidden" name="catid" value="<?php echo $zoom->_gallery->_id; ?>" />
		<table border="0" cellspacing="1" cellpadding="4">
			<tr>
				<td><?php echo _ZOOM_HD_HIDEMSG;?>:</td>
				<td>
				<input type="checkbox" name="hidemsg" value="1"<?php echo ($zoom->_gallery->_hideMsg) ? " checked" : "";?>>
				</td>
			</tr>
			<tr>
				<td width="100" align="left"><?php echo _ZOOM_HD_NAME;?>: </td>
				<td align="left"><input type="text" name="newname" size="50" value="<? echo $zoom->_gallery->_name;?>" class="inputbox"></td>
			</tr>
			<tr>
		      <td align="left"><?php echo _ZOOM_PASS;?>:</td>
		      <td align="left"><input class="inputbox" type="password" name="catpass" value="" onClick="javascript:this.form.catpass.focus();this.form.catpass.select();" size="50"></td>
		    </tr>
		    <tr>
		        <td><?php echo _ZOOM_KEYWORDS;?>: </td>
		        <td valign="center"><input type="text" name="keywords" size="50" value="<?php echo $zoom->_gallery->_keywords;?>" class="inputbox"></td>
		    </tr>
			<tr>
				<td align="left"><?echo _ZOOM_DESCRIPTION;?>: </td>
				<td align="left"><textarea class="inputbox" name="newdescr" rows="5" cols="50"><? echo $zoom->_gallery->_descr;?></textarea></td>
			</tr>
			<tr>
				<td align="left">
					<?php echo _ZOOM_PUBLISHED;?>:
				</td>
				<td align="left">
					<input type="checkbox" name="published" value="1"<?php if($zoom->_gallery->isPublished()) echo " checked";?>>
					<?php echo _ZOOM_HD_SHARE;?>: <input type="checkbox" name="shared" value="1"<?php if($zoom->_gallery->isShared()) echo " checked";?>>	
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
		    $userlist = $zoom->getUsersList($zoom->_gallery->_members);
		    foreach($userlist as $item){
		        echo $item."\n";
		    }
		    ?>
		    </td>
		</tr>
		</table>
	</div>
</div>
</form><br />

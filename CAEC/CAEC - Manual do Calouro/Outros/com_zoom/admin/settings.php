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
| Filename: settings.php                                              |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$submit = mosGetParam($_REQUEST,'s01');
if($submit){
	// write new settings to database...
	if ($zoom->saveConfig()) {
		// rewrite the css-file...
		$css = trim(stripslashes(mosGetParam($_REQUEST,'s18')));
		$css_file = $mosConfig_absolute_path.'/components/com_zoom/zoom.css';
		@chmod ($css_file, 0766);
		$permission = is_writable($css_file);
		if (!$permission) {
			echo sprintf( _ZOOM_A_ERROR_CSS_NOT_WRITEABLE, $css_file );
			exit();
		}
	    $zoom->writefile($css_file, $css);
	    if(strlen($zoom->_CONFIG['safemodeversion']) > 0){
	    	// rewrite the ftp-configuration file...
	    	$ftp_server = trim(mosGetParam($_REQUEST,'s47'));
	    	$ftp_username = trim(mosGetParam($_REQUEST,'s48'));
	    	$ftp_pass = trim(mosGetParam($_REQUEST,'s49'));
	    	$ftp_hostdir = trim(mosGetParam($_REQUEST,'s52'));
	    	$ftp_cfg = "<?php\n";
	    	$ftp_cfg .= "defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );\n";
	    	$ftp_cfg .= "\$ftp_server = \"{$ftp_server}\";\n";
	    	$ftp_cfg .= "\$ftp_username = \"{$ftp_username}\";\n";
	    	$ftp_cfg .= "\$ftp_pass = \"{$ftp_pass}\";\n";
	    	$ftp_cfg .= "\$ftp_hostdir = \"{$ftp_hostdir}\";\n";
	    	$ftp_cfg .= "?>";
	    	$ftp_file = $mosConfig_absolute_path.'/components/com_zoom/safemode.php';
			@chmod ($ftp_file, 0766);
			$permission = is_writable($ftp_file);
			if (!$permission) {
				echo sprintf( _ZOOM_A_ERROR_FTP_NOT_WRITEABLE, $ftp_file );
				exit();
			}
		    $zoom->writefile($ftp_file, $ftp_cfg);
	    }
	    ?>
		<script language="javascript" type="text/javascript">
			<!--
			alert("<?php echo html_entity_decode(_ZOOM_SETTINGS_SUCCESS);?>");
			location = "<?php echo "index".$backend.".php?option=com_zoom&page=admin&Itemid=".$Itemid;?>";
			//-->
		</SCRIPT>
		<?php
	}else{
		?>
		<script language="javascript" type="text/javascript">
			<!--
			alert("<?php echo html_entity_decode( _ZOOM_A_ERROR_CONFTODB ); ?>");
			location = "<?php echo "index".$backend.".php?option=com_zoom&page=settings&Itemid=".$Itemid;?>";
			//-->
		</SCRIPT>
		<?php
	}
}
// HTML starts here.
// First, do autoDetect on Image Libraries...
$imageLibs = $zoom->_toolbox->getImageLibs();
?>
<style type="text/css" media="screen">
	<!--
	.settingsupper   { border-right: 1px dashed #CCCCCC; border-top: 1px dashed #CCCCCC; }
	hr { border: solid 2px black }
	-->
</style>
<!-- Begin header -->
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td align="center" width="100%"><a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=admin";?>">
		<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?echo _ZOOM_MAINSCREEN;?>" border="0">&nbsp;&nbsp;<?echo _ZOOM_MAINSCREEN;?></a>&nbsp; | &nbsp;
		</td>
	</tr>
</table>
<table border="0" cellspacing="0" cellpadding="5">
<tr>
	<td align="left" valign="bottom">
		<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/settings_f2.png" border="0" alt="<?php echo _ZOOM_SETTINGS;?>">
		&nbsp;<b><font size="4"><?php echo _ZOOM_SETTINGS;?> : </font>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td align="left" valign="bottom">
		<b>configuration file is : <br />				
		stylesheet file is : <br />				
		FTP configuration file is :	<b>			
	</td>
	<td align="left" valign="bottom">
		<b><?php echo (is_writable($mosConfig_absolute_path."/components/com_zoom/zoom_config.php") ? "<font color=\"green\">Writable</font>" : "<font color=\"red\">Not Writable</font>");?><br />
		<?php echo (is_writable($mosConfig_absolute_path."/components/com_zoom/zoom.css") ? "<font color=\"green\">Writable</font>" : "<font color=\"red\">Not Writable</font>");?><br />
		<?php echo (is_writable($mosConfig_absolute_path."/components/com_zoom/safemode.php") ? "<font color=\"green\">Writable</font>" : "<font color=\"red\">Not Writable</font>");?></b>
	</td>
</tr>
</table>
<!-- End header -->
<table cellspacing="0" cellpadding="4" border="0" width="100%">
  <tr>
    <td width="85%" class="tabpadding" align="center">
    	<a href="javascript:document.forms.settings.submit();" onmouseout="MM_swapImgRestore();return nd();"  onmouseover="MM_swapImage('save','','images/save_f2.png',1);return overlib('<?php echo _ZOOM_SAVE;?>');"><img src="images/save.png" alt="<?php echo _ZOOM_SAVE;?>" border="0" name="save" /></a>
    	<a href="javascript:document.forms.settings.reset();" onmouseout="MM_swapImgRestore();return nd();"  onmouseover="MM_swapImage('cancel','','images/cancel_f2.png',1);return overlib('<?php echo _ZOOM_RESET;?>');"><img src="images/cancel.png" alt="<?php echo _ZOOM_RESET;?>" border="0" name="cancel" /></a>
    </td>
  </tr>
</table>
<form name="settings" method="post" action="index<?php echo ($zoom->_isBackend) ? "2" : "";?>.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=settings">
<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="<?php echo $mosConfig_live_site;?>/components/com_zoom/tabs/tabpane.css" />
<script language="javascript" type="text/javascript" src="<?php echo $mosConfig_live_site;?>/components/com_zoom/tabs/tabpane.js"></script>
<div class="tab-page" id="modules-cpanel">
	<script language="javascript" type="text/javascript">
		<!--
		var tabPane1 = new WebFXTabPane( document.getElementById( "modules-cpanel" ), 0 )
		//-->
	</script>
	<div class="tab-page" id="module19">
		<h2 class="tab"><?php echo _ZOOM_SETTINGS_TAB1;?></h2>
		<script language="javascript" type="text/javascript">
			<!--
			tabPane1.addTabPage( document.getElementById( "module19" ) );
			//-->
		</script>
		<table border="0" cellspacing="0" cellpadding="5" width="100%" class="adminform">
		<tr>
			<td width="20">&nbsp;</td>
			<td colspan="3">
				<strong><?php echo _ZOOM_SETTINGS_CONVSETTINGS;?></strong>
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_SETTINGS_IMGPATH;?>
			</td>
			<td colspan="2">
				<input type="text" name="s02" value="<?php echo $zoom->_CONFIG['imagepath'];?>" size="40" class="inputbox">
				<a href="#" onmouseover="return overlib('<?php echo _ZOOM_SETTINGS_TTIMGPATH; echo (is_writable($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'])) ? "<b>Writable</b>" : "<b>Unwritable</b>";?>', CAPTION, '<?php echo _ZOOM_TOOLTIP;?>');" onmouseout="return nd();">
					&nbsp;<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/tooltip.png" border="0" />
				</a>
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_SETTINGS_IMPATH;?>
			</td>
			<td colspan="2">
				<input type="text" name="s03" value="<?php echo (array_key_exists('imagemagick',$imageLibs)) ? 'auto' : $zoom->_CONFIG['IM_path'];?>" size="40" class="inputbox">
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_SETTINGS_NETPBMPATH;?>
			</td>
			<td colspan="2">
				<input type="text" name="s04" value="<?php echo (array_key_exists('netpbm',$imageLibs)) ? 'auto' : $zoom->_CONFIG['NETPBM_path'];?>" size="40" class="inputbox">
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_SETTINGS_FFMPEGPATH;?>: 
			</td>
			<td colspan="2">
				<input type="text" name="s36" value="<?php echo (array_key_exists('ffmpeg',$imageLibs)) ? 'auto' : $zoom->_CONFIG['FFMPEG_path'];?>" size="40" class="inputbox">
				<a href="#" onmouseover="return overlib('<?php echo _ZOOM_SETTINGS_FFMPEGTOOLTIP.implode(", ", $zoom->thumbnailableMovieList());?>', CAPTION, '<?php echo _ZOOM_TOOLTIP;?>');" onmouseout="return nd();">
					&nbsp;<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/tooltip.png" border="0" />
				</a>
				<?php
				if( array_key_exists( 'ffmpeg', $imageLibs ) ){
					echo '<strong><font color="green">'._ZOOM_SETTINGS_AUTODET.$imageLibs['ffmpeg'].'</font></strong>';
				}else{
					echo '<font color="red">' . _ZOOM_A_ERROR_NOFFMPEG . '</font>';
				} ?>
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_SETTINGS_PDFTOTEXTPATH;?>:
			</td>
			<td colspan="2">
				<input type="text" name="s45" value="<?php echo (array_key_exists('pdftotext',$imageLibs)) ? 'auto' : $zoom->_CONFIG['PDF_path'];?>" size="40" class="inputbox">
				<a href="#" onmouseover="return overlib('<?php echo _ZOOM_SETTINGS_XPDFTOOLTIP;?>', CAPTION, '<?php echo _ZOOM_TOOLTIP;?>');" onmouseout="return nd();">
					&nbsp;<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/tooltip.png" border="0" />
				</a>
				<?php
				if(array_key_exists('pdftotext',$imageLibs)){
					echo '<strong><font color="green">'._ZOOM_SETTINGS_AUTODET.$imageLibs['pdftotext'].'</font></strong>';
				}else{
					echo '<font color="red">' . _ZOOM_A_ERROR_NOPDFTOTEXT . '</font>';
				} ?>
			</td>
		<tr>
			<td width="20">&nbsp;</td>
			<td colspan="3" style="border-bottom: 1px dashed #CCCCCC;">&nbsp;</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td valign="top">
				<strong><?php echo _ZOOM_SETTINGS_CONVTYPE;?></strong>
			</td>
			<td valign="top">
				<select name="s01" size="1" class="inputbox">
					<option value="1" <?php if($zoom->_CONFIG['conversiontype']==1) echo "selected";?>>1</option>
					<option value="2" <?php if($zoom->_CONFIG['conversiontype']==2) echo "selected";?>>2</option>
					<option value="3" <?php if($zoom->_CONFIG['conversiontype']==3) echo "selected";?>>3</option>
					<option value="4" <?php if($zoom->_CONFIG['conversiontype']==4) echo "selected";?>>4</option>
				</select>
			</td>
			<td valign="top">
				1 = <a href="http://www.imagemagick.org" target=_blank>ImageMagick</a>&nbsp;&nbsp;
					<?php if(array_key_exists('imagemagick',$imageLibs)) echo '<strong><font color="green">'._ZOOM_SETTINGS_AUTODET.$imageLibs['imagemagick'].'</font></strong>'; else echo '<strong><font color="red">' . _ZOOM_A_ERROR_NOTINSTALLED . '</strong></font>'; ?>
					<br />
				2 = <a href="http://sourceforge.net/projects/netpbm" target=_blank>NetPBM</a>&nbsp;&nbsp;
					<?php if(array_key_exists('netpbm',$imageLibs)) echo '<strong><font color="green">'._ZOOM_SETTINGS_AUTODET.$imageLibs['netpbm'].'</font></strong>'; else echo '<strong><font color="red">' . _ZOOM_A_ERROR_NOTINSTALLED . '</strong></font>'; ?>
					<br />
				3 = GD1 library 
					<?php if(array_key_exists('gd1',$imageLibs['gd'])) echo '&nbsp;&nbsp;<strong><font color="green">'._ZOOM_SETTINGS_AUTODET.$imageLibs['gd']['gd1'].'</font></strong>'; else echo '<strong><font color="red">' . _ZOOM_A_ERROR_NOTINSTALLED . '</strong></font>'; ?>
					<br />
				4 = GD2 library 
					<?php if(array_key_exists('gd2',$imageLibs['gd'])) echo '&nbsp;&nbsp;<strong><font color="green">'._ZOOM_SETTINGS_AUTODET.$imageLibs['gd']['gd2'].'</font></strong>'; else echo '<strong><font color="red">' . _ZOOM_A_ERROR_NOTINSTALLED . '</strong></font>'; ?>

			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td colspan="3" style="border-bottom: 1px dashed #CCCCCC;">&nbsp;</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<strong><?php echo _ZOOM_SETTINGS_MAXSIZE;?></strong>
			</td>
			<td colspan="2">
				<input type="text" name="s26" value="<?php echo $zoom->_CONFIG['maxsize'];?>" size="5" maxlength="4" class="inputbox">px
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td colspan="3" style="border-bottom: 1px dashed #CCCCCC;">&nbsp;</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td colspan="3">
				<strong><?php echo _ZOOM_SETTINGS_THUMBSETTINGS;?></strong>
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_SETTINGS_QUALITY;?>
			</td>
			<td colspan="2">
				<input type="text" name="s05" value="<?php echo $zoom->_CONFIG['JPEGquality'];?>" size="5" maxlength="3" class="inputbox">%
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_SETTINGS_SIZE;?>
			</td>
			<td colspan="2">
				<input type="text" name="s06" value="<?php echo $zoom->_CONFIG['size'];?>" size="5" maxlength="3" class="inputbox">px
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_SETTINGS_TEMPNAME;?>
			</td>
			<td colspan="2">
				<input type="text" name="s20" value="<?php echo $zoom->_CONFIG['tempName'];?>" size="40" class="inputbox"> 
				<input type="checkbox" name="s21" value="1"<?php echo ($zoom->_CONFIG['autonumber']) ? ' checked' : '';?>> <?php echo _ZOOM_SETTINGS_AUTONUMBER;?>
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_SETTINGS_TEMPDESCR;?>
			</td>
			<td colspan="2">
				<input type="text" name="s16" value="<?php echo $zoom->_CONFIG['tempDescr'];?>" size="40" class="inputbox">
			</td>
		</tr>
		</table>
	</div>
	<div class="tab-page" id="module20">
		<h2 class="tab"><?php echo _ZOOM_SETTINGS_TAB2;?></h2>
		<script type="text/javascript">
			tabPane1.addTabPage( document.getElementById( "module20" ) );
		</script>
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="adminform">
		<tr>
			<td width="20"></td>
			<td>
				<table border="0" cellspacing="3" cellpadding="3">
					<tr>
						<td class="settingsupper" colspan="2" valign="bottom">
							<?php echo _ZOOM_SETTINGS_TITLE;?><br />
							<input type="text" name="s28" size="30" maxlength="50" value="<?php echo $zoom->_CONFIG['zoom_title'];?>" class="inputbox">
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_SUBCATSPG;?><br />
							<input type="text" name="s23" size="5" maxlength="2" value="<?php echo $zoom->_CONFIG['catcolsno'];?>" class="inputbox">
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_COLUMNS;?><br />
							<input type="text" name="s07" size="5" maxlength="2" value="<?php echo $zoom->_CONFIG['columnsno'];?>" class="inputbox">
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_THUMBSPG;?><br />
							<input type="text" name="s08" size="5" maxlength="2" value="<?php echo $zoom->_CONFIG['PageSize'];?>" class="inputbox">
						</td>
					</tr>
					<tr>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_CMTLENGTH;?><br />
							<input type="text" name="s44" size="7" maxlength="4" value="<?php echo $zoom->_CONFIG['cmtLength'];?>" class="inputbox">
							&nbsp; <?php echo _ZOOM_SETTINGS_CHARS;?>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_GALLERYPREFIX;?><br />
							<input type="text" name="s50" size="10" maxlength="7" value="<?php echo $zoom->_CONFIG['galleryPrefix'];?>" class="inputbox">
						</td>
						<td class="settingsupper" colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_COMMENTS;?><br />
							<select name="s09" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['commentsOn']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['commentsOn']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_POPUP;?><br />
							<select name="s10" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['popUpImages']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['popUpImages']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_CATIMG;?><br />
							<select name="s11" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['catImg']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['catImg']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_SLIDESHOW;?><br />
							<select name="s12" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['slideshow']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['slideshow']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_ZOOMLOGO;?><br />
							<select name="s13" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['displaylogo']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['displaylogo']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_SHOWHITS;?><br />
							<select name="s22" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['showHits']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['showHits']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_READEXIF;?><br />
							<select name="s14" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['readEXIF']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['readEXIF']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
							<a href="#" onmouseover="return overlib('<?php echo _ZOOM_SETTINGS_EXIFTOOLTIP;?>', CAPTION, '<?php echo _ZOOM_TOOLTIP;?>');" onmouseout="return nd();">
								<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/tooltip.png" border="0" />
							</a>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_READID3;?><br />
							<select name="s58" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['readID3']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['readID3']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
							<a href="#" onmouseover="return overlib('<?php echo _ZOOM_SETTINGS_ID3TOOLTIP;?>', CAPTION, '<?php echo _ZOOM_TOOLTIP;?>');" onmouseout="return nd();">
								<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/tooltip.png" border="0" />
							</a>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_RATING;?><br />
							<select name="s17" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['ratingOn']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['ratingOn']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_ZOOMING;?><br />
							<select name="s19" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['zoomOn']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['zoomOn']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
					</tr>
	                <tr>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_LIGHTBOX;?><br />
							<select name="s25" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['lightbox']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['lightbox']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
							<a href="#" onmouseover="return overlib('<?php echo _ZOOM_SETTINGS_LBTOOLTIP;?>', CAPTION, '<?php echo _ZOOM_TOOLTIP;?>');" onmouseout="return nd();">
								<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/tooltip.png" border="0" />
							</a>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_SHOWNAME;?><br />
							<select name="s38" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['showName']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['showName']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_SHOWDESCR;?><br />
							<select name="s39" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['showDescr']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['showDescr']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_SHOWKEYWORDS;?><br />
							<select name="s40" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['showKeywords']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['showKeywords']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_SHOWDATE;?><br />
							<select name="s41" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['showDate']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['showDate']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_SHOWFILENAME;?><br />
							<select name="s42" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['showFilename']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['showFilename']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_METABOX;?><br />
							<select name="s43" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['showMetaBox']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['showMetaBox']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
							<a href="#" onmouseover="return overlib('<?php echo _ZOOM_SETTINGS_METABOXTOOLTIP;?>', CAPTION, '<?php echo _ZOOM_TOOLTIP;?>');" onmouseout="return nd();">
								<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/tooltip.png" border="0" />
							</a>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_ECARDS;?><br />
							<select name="s34" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['ecards']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['ecards']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper" valign="bottom">
							<?php echo _ZOOM_SETTINGS_ECARDS_LIFETIME;?><br />
							<select name="s35" size="1" class="inputbox">
								<option value="7" <?php if($zoom->_CONFIG['ecards_lifetime'] == 7) echo "selected";?>><?php echo _ZOOM_SETTINGS_ECARDS_ONEWEEK;?></option>
								<option value="14" <?php if($zoom->_CONFIG['ecards_lifetime'] == 14) echo "selected";?>><?php echo _ZOOM_SETTINGS_ECARDS_TWOWEEKS;?></option>
								<option value="1" <?php if($zoom->_CONFIG['ecards_lifetime'] == 1) echo "selected";?>><?php echo _ZOOM_SETTINGS_ECARDS_ONEMONTH;?></option>
								<option value="3" <?php if($zoom->_CONFIG['ecards_lifetime'] == 3) echo "selected";?>><?php echo _ZOOM_SETTINGS_ECARDS_THREEMONTHS;?></option>
							</select>
						</td>
						<td class="settingsupper">
							<?php echo _ZOOM_SETTINGS_SHOWSEARCH;?><br />
							<select name="s37" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['showSearch']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['showSearch']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
					</tr>
					<tr>
						<?php
						$css = fread(fs_fopen($mosConfig_absolute_path."/components/com_zoom/zoom.css", "r"), fs_filesize($mosConfig_absolute_path."/components/com_zoom/zoom.css"));
						?>
						<td class="settingsupper"><?php echo _ZOOM_SETTINGS_CSS;?></td>
						<td class="settingsupper" colspan="4">
							<textarea name="s18" rows="10" cols="50" class="inputbox"><?php echo stripslashes($css);?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php echo _ZOOM_SETTINGS_ORDERBY;?></td>
						<td colspan="4">
							<select name="s24" size="1" class="inputbox">
								<option value="1"<?php if($zoom->_CONFIG['orderMethod']==1) echo " selected";?>><?php echo _ZOOM_SETTINGS_DATE_ASC;?></option>
								<option value="2"<?php if($zoom->_CONFIG['orderMethod']==2) echo " selected";?>><?php echo _ZOOM_SETTINGS_DATE_DESC;?></option>
								<option value="3"<?php if($zoom->_CONFIG['orderMethod']==3) echo " selected";?>><?php echo _ZOOM_SETTINGS_FLNM_ASC;?></option>
								<option value="4"<?php if($zoom->_CONFIG['orderMethod']==4) echo " selected";?>><?php echo _ZOOM_SETTINGS_FLNM_DESC;?></option>
								<option value="5"<?php if($zoom->_CONFIG['orderMethod']==5) echo " selected";?>><?php echo _ZOOM_SETTINGS_NAME_ASC;?></option>
								<option value="6"<?php if($zoom->_CONFIG['orderMethod']==6) echo " selected";?>><?php echo _ZOOM_SETTINGS_NAME_DESC;?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo _ZOOM_SETTINGS_CATORDERBY;?></td>
						<td colspan="4">
							<select name="s51" size="1" class="inputbox">
								<option value="1"<?php if($zoom->_CONFIG['catOrderMethod']==1) echo " selected";?>><?php echo _ZOOM_SETTINGS_DATE_ASC;?></option>
								<option value="2"<?php if($zoom->_CONFIG['catOrderMethod']==2) echo " selected";?>><?php echo _ZOOM_SETTINGS_DATE_DESC;?></option>
								<option value="3"<?php if($zoom->_CONFIG['catOrderMethod']==3) echo " selected";?>><?php echo _ZOOM_SETTINGS_NAME_ASC;?></option>
								<option value="4"<?php if($zoom->_CONFIG['catOrderMethod']==4) echo " selected";?>><?php echo _ZOOM_SETTINGS_NAME_DESC;?></option>
							</select>
						</td>
					</tr>
				</table> 
			</td>
		</tr>
		<tr>
			<td align="right" valign="middle" colspan="2">
				Powered by: <img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/attilas_id3logo.png" border="0" /><br />
			</td>
		</tr>
		</table>
	</div>
	<!-- zoom module settings -->
	<div class="tab-page" id="module21">
		<h2 class="tab"><?php echo _ZOOM_M_config;?></h2>
		<script language="javascript" type="text/javascript">
			<!--
			tabPane1.addTabPage( document.getElementById( "module21" ) );
			//-->
		</script>
		<table border="0" cellspacing="0" cellpadding="5" width="100%" class="adminform">
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_M_method;?>
			</td>
			<td>
				<select name="s29" size="" class="inputbox">
					<option value="1" <?php if($zoom->_CONFIG['zoomModule'] == 1) echo "selected";?>><?php echo _ZOOM_M_all;?></option>
					<option value="2" <?php if($zoom->_CONFIG['zoomModule'] == 2) echo "selected";?>><?php echo _ZOOM_M_random;?></option>
					<option value="3" <?php if($zoom->_CONFIG['zoomModule'] == 3) echo "selected";?>><?php echo _ZOOM_M_newest;?></option>
					<option value="4" <?php if($zoom->_CONFIG['zoomModule'] == 4) echo "selected";?>><?php echo _ZOOM_M_hits;?></option>
					<option value="5" <?php if($zoom->_CONFIG['zoomModule'] == 5) echo "selected";?>><?php echo _ZOOM_M_votes;?></option>
				</select>&nbsp;
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td colspan="3" style="border-bottom: 1px dashed #CCCCCC;">&nbsp;</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_M_admin_count;?>
			</td>
			<td>
				<select name="s53" size="1" class="inputbox">
					<option value="2" <?php if($zoom->_CONFIG['mod_showcount'] == 2) echo "selected";?>><?php echo _ZOOM_YES;?></option>
					<option value="1" <?php if($zoom->_CONFIG['mod_showcount'] == 1) echo "selected";?>><?php echo _ZOOM_NO;?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_M_admin_lastup;?>
			</td>
			<td>
				<select name="s54" size="1" class="inputbox">
					<option value="2" <?php if($zoom->_CONFIG['mod_showupdate'] == 2) echo "selected";?>><?php echo _ZOOM_YES;?></option>
					<option value="1" <?php if($zoom->_CONFIG['mod_showupdate'] == 1) echo "selected";?>><?php echo _ZOOM_NO;?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_M_admin_cats;?>
			</td>
			<td>
				<select name="s55" size="1" class="inputbox">
					<option value="2" <?php if($zoom->_CONFIG['mod_showcatnames'] == 2) echo "selected";?>><?php echo _ZOOM_YES;?></option>
					<option value="1" <?php if($zoom->_CONFIG['mod_showcatnames'] == 1) echo "selected";?>><?php echo _ZOOM_NO;?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_M_admin_meth;?>
			</td>
			<td>
				<select name="s56" size="1" class="inputbox">
					<option value="2" <?php if($zoom->_CONFIG['mod_showmeth'] == 2) echo "selected";?>><?php echo _ZOOM_YES;?></option>
					<option value="1" <?php if($zoom->_CONFIG['mod_showmeth'] == 1) echo "selected";?>><?php echo _ZOOM_NO;?></option>
				</select>
			</td>				
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td colspan="3" style="border-bottom: 1px dashed #CCCCCC;">&nbsp;</td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				<?php echo _ZOOM_M_admin_df;?>
			</td>
			<td>
				<input type="text" name="s57" value="<?php echo $zoom->_CONFIG['mod_dateformat'];?>" size="40" class="inputbox">
			</td>
		</tr>
		</table>
	</div>
	<?php
	if(strlen($zoom->_CONFIG['safemodeversion']) > 0){
	?>
		<div class="tab-page" id="module22" align="center">
			<h2 class="tab"><?php echo _ZOOM_SETTINGS_TAB5;?></h2>
			<script language="javascript" type="text/javascript">
				<!--
				tabPane1.addTabPage( document.getElementById( "module22" ) );
				//-->
			</script>
			<table border="0" cellspacing="0" cellpadding="5" width="80%" class="adminform">
			<?php
			// if php safe_mode restriction is in use, warn the user! -> added by mic
			if( ini_get( 'safe_mode' ) == 1 ){ ?>
				<tr>
					<td>&nbsp;</td>
					<td><strong><font color="red"><?php echo _ZOOM_A_MESS_SAFEMODE2; ?></font></strong></td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<?php
			} ?>
			<tr>
				<td>
					<?php echo _ZOOM_SETTINGS_USEFTP;?>
				</td>
				<td>
					<select name="s46" size="1" class="inputbox">
						<option value="1" <?php if($zoom->_CONFIG['safemodeON']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
						<option value="0" <?php if(!$zoom->_CONFIG['safemodeON']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo _ZOOM_SETTINGS_FTPHOST;?>:
				</td>
				<td>
					<input type="text" name="s47" size="30" value="<?php echo $zoom->_CONFIG['ftp_server'];?>" class="inputbox">
				</td>
			</tr>
			<tr>
				<td>
					<?php echo _ZOOM_SETTINGS_FTPUNAME;?>:
				</td>
				<td>
					<input type="text" name="s48" size="30" value="<?php echo $zoom->_CONFIG['ftp_username'];?>" class="inputbox">
				</td>
			</tr>
			<tr>
				<td>
					<?php echo _ZOOM_SETTINGS_FTPPASS;?>:
				</td>
				<td>
					<input type="password" name="s49" size="30" maxlength="50" value="<?php echo $zoom->_CONFIG['ftp_pass'];?>" class="inputbox">
					&nbsp;<a href="#" onmouseover="return overlib('<?php echo _ZOOM_SETTINGS_FTPWARNING;?>', CAPTION, '<?php echo _ZOOM_WARNING;?>');" onmouseout="return nd();">
						<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/warning.png" border="0" />
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo _ZOOM_SETTINGS_FTPHOSTDIR;?>:
				</td>
				<td>
					<input type="text" name="s52" size="30" value="<?php echo $zoom->_CONFIG['ftp_hostdir'];?>" class="inputbox">
					&nbsp;<a href="#" onmouseover="return overlib('<?php echo _ZOOM_SETTINGS_MESS_FTPHOSTDIR;?>', CAPTION, '<?php echo _ZOOM_TOOLTIP;?>');" onmouseout="return nd();">
						<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/tooltip.png" border="0" />
					</a>
				</td>
			</tr>
			</table>
		</div>
		<?php
	}			
	?>
	<div class="tab-page" id="module23">
		<h2 class="tab"><?php echo _ZOOM_SETTINGS_TAB4;?></h2>
		<script language="javascript" type="text/javascript">
			<!--
			tabPane1.addTabPage( document.getElementById( "module23" ) );
			//-->
		</script>
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="adminform">
		<tr>
			<td width="20"></td>
			<td>
				<table border="0" cellspacing="3" cellpadding="3">
					<tr valign="top">
						<td align="right">
						<?php echo _ZOOM_SETTINGS_USERUPL;?>
						</td>
						<td align="left">
						<select name="s15" size="1" class="inputbox">
							<option value="1" <?php if($zoom->_CONFIG['allowUserUpload']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
							<option value="0" <?php if(!$zoom->_CONFIG['allowUserUpload']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
						</select>&nbsp;
						</td>
						<td align="right">
						<?php echo _ZOOM_SETTINGS_ACCESSLVL;?>
						</td>
						<td align="left">
						<?php echo $zoom->createACLgroupList(); ?>
						</td>
					</tr>
				</table>
				<table border="0" cellspacing="3" cellpadding="3">
					<tr>
						<td class="settingsupper">
							<?php echo _ZOOM_SETTINGS_ALLOWCREATE;?><br />
							<select name="s30" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['allowUserCreate']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['allowUserCreate']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>	
						</td>
						<td class="settingsupper" colspan="2">
							<?php echo _ZOOM_SETTINGS_ALLOWDEL;?><br />
							<select name="s31" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['allowUserDel']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['allowUserDel']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper">
							<?php echo _ZOOM_SETTINGS_ALLOWEDIT;?><br />
							<select name="s32" size="1" class="inputbox">
								<option value="1" <?php if($zoom->_CONFIG['allowUserEdit']) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->_CONFIG['allowUserEdit']) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
						<td class="settingsupper">
							<?php echo _ZOOM_SETTINGS_SETMENUOPTION;?><br />
							<select name="s33" size="1" class="inputbox">
								<option value="1" <?php if($zoom->issetUserMenu()) echo "selected";?>><?php echo _ZOOM_YES;?></option>
								<option value="0" <?php if(!$zoom->issetUserMenu()) echo "selected";?>><?php echo _ZOOM_NO;?></option>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		</table>
	</div>
</div>
</form><br />

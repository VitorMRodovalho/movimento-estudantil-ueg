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
| Filename: upl_multiple.php                                          |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$submit1 = mosGetParam($_REQUEST,'submit1');
if (isset($submit1) && !empty($submit1)) {
	if (!$catid){
		?>
		<SCRIPT>
			alert("<?echo _ZOOM_NOCAT;?>");
			location = '<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=upload&formtype=multiple";?>';
		</SCRIPT>
		<?
	}
	// counter:
	$i = 0;
	foreach ($userfile as $image){
		if(is_array($userfile_name))
			$filename = array_shift($userfile_name);
		else
			$filename = $userfile_name;
		$theName = $zoom->getRequestShift('imgname');
		$keywords = $zoom->getRequestShift('keywords');
	  	$rotate = $zoom->getRequestShift('rotate');
		if($rotate[$img]){
	        $rotate = true;
	        $key = "rotate$i";
	        $degrees = mosGetParam($_REQUEST,$key);
	    }
		if (!empty($usercaption) && is_array($usercaption)){
	    	$caption = $zoom->removeTags(array_shift($usercaption));
		}else{
			$caption = $zoom->removeTags($usercaption);
		}
	    if($zoom->_toolbox->processImage($image, $filename, $keywords, $theName, $caption, $rotate, $degrees))
	        $i++;
	} // end of while-loop
	if($zoom->_toolbox->_err_num >= 0)
		$zoom->_toolbox->displayErrors($err_num, $err_names, $err_types);
	echo "<br /><center><h4>".$i." "._ZOOM_ALERT_UPLOADSOK."</h4></center><br /><br />";
	$formtype = 'multiple';
}else{
	$boxes = mosGetParam($_REQUEST,'boxes');
	if (!$boxes) {
		$boxes = 5;
	}
	?>
	<form enctype="multipart/form-data" name="count_form" method="POST" action="index<?php echo $zoom->_isBackend ? "2" : "";?>.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=upload&catid=<?php echo $catid;?>&formtype=multiple">
	<table width="90%" border="0" cellpadding="3" cellspacing="3">
		<tr><td colspan="2">&nbsp;</td></tr>
		<?php
		// if php safe_mode restriction is in use, warn the user! -> added by mic
		if( ini_get( 'safe_mode' ) == 1 ){ ?>
			<tr>
				<td>&nbsp;</td>
		 		<td><strong><font color="red"><?php echo _ZOOM_A_MESS_SAFEMODE1; ?></font></strong></td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<?php
		} ?>
		<tr>
			<td width="60%" align="left"><strong><?php echo _ZOOM_UPLOAD_STEP1;?><strong>&nbsp;</td>
			<td>
				<select name="boxes" onChange="submitCount()" class="inputbox">
					<?php 
					for( $i = 1; $i <= 10; $i++ ) {
						echo "<option ";
						if ($i == $boxes) {
							echo "selected ";
						}
						echo "value=\"$i\">$i\n";

					} ?>
				</select>
			</td>
		</tr>
	</table>	
	</form>
	<form enctype="multipart/form-data" name="upload_form" method="POST" action="index<?php echo $zoom->_isBackend ? "2" : "";?>.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=upload&formtype=save">
	<table width="90%" border="0" cellpadding="3" cellspacing="3">
		<tr><td style="border-bottom: 1px dashed #CCCCCC;" colspan="2">&nbsp;</td></tr>
		<tr>
			<td width="60%"><strong><?php echo _ZOOM_UPLOAD_STEP2;?></strong></td>
			<td>
				<?php
				echo $zoom->createCatDropdown('catid', '<OPTION value="">---&nbsp;'._ZOOM_PICK.'&nbsp;---</OPTION>', 0, $catid); ?>
			</td>
		</tr>
		<tr><td style="border-bottom: 1px dashed #CCCCCC;" colspan="2">&nbsp;</td></tr>
		<tr>
			<td><strong><?php echo _ZOOM_UPLOAD_STEP3;?></strong>&nbsp;</td>
			<td>
				<input type="checkbox" name="setFilename" value="1"<?php if( $zoom->_CONFIG['autonumber'] ) echo " checked";?>>&nbsp;<?php echo _ZOOM_FORM_SETFILENAME;?>
			</td>
		</tr>
	</table>	
	<table border="0" cellpadding="0" cellspacing="0">
		<?php
		$tabcnt=1;
		for ($i = 0; $i < $boxes;  $i++) { ?>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr class="<?php echo $zoom->_tabclass[$tabcnt]; ?>">
				<td><?php echo _ZOOM_FORM_IMAGEFILE;?>:&nbsp;</td>
				<td>
					<input class="inputbox" type="file" name="userfile[]" size="50" />
				</td>
			</tr>			
			<tr class="<?php echo $zoom->_tabclass[$tabcnt]; ?>">
				<td>&nbsp;</td>
				<td>
					<input type="checkbox" name="rotate[]" value="1" /><?php echo _ZOOM_ROTATE;?>
					<input type="radio" name="rotate<?php echo $i;?>" value="90" /><?php echo _ZOOM_CLOCKWISE;?>
					<input type="radio" name="rotate<?php echo $i;?>" value="-90" /><?php echo _ZOOM_CCLOCKWISE;?>
				</td>
			</tr>			
			<tr class="<?php echo $zoom->_tabclass[$tabcnt];?>">
				<td valign="top"><?php echo _ZOOM_NAME;?>: </td>
				<td valign="top">
					<input type="text" name="imgname[]" size="50" value="<?php echo $zoom->_CONFIG['tempName'];?>" class="inputbox" />
				</td>
			</tr>
			<tr class="<?php echo $zoom->_tabclass[$tabcnt];?>">
				<td valign="top"><?php echo _ZOOM_KEYWORDS;?>:&nbsp;</td>
				<td valign="top">
					<input type="text" name="keywords[]" size="50" value="" class="inputbox" />
				</td>
			</tr>
			<tr class="<?php echo $zoom->_tabclass[$tabcnt]; ?>">
				<td valign="top"><?php echo _ZOOM_DESCRIPTION;?>:&nbsp;</td>
				<td valign="top">
					<textarea class="inputbox" cols="50" rows="5" name="usercaption[]"><?php echo $zoom->_CONFIG['tempDescr'];?></textarea>
				</td>
			</tr>
			<tr><td style="border-bottom: 1px dashed #CCCCCC;" colspan="2">&nbsp;</td></tr>
			<?php
			if ($tabcnt == 1){
				$tabcnt = 0;
			} else {
				$tabcnt++;
			}
		} ?>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value="<?php echo _ZOOM_BUTTON_UPLOAD; ?>" name="submit1" class="button" />
			</td>
		</tr>
	</table>
	</form>
	<?php
}
?>
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
| Filename: upl_single.php                                            |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$single_submit = mosGetParam($_REQUEST,'single_submit');
$single_submit2 = mosGetParam($_REQUEST,'single_submit2');
$single_submit3 = mosGetParam($_REQUEST,'single_submit3'); 
if ($single_submit){
	if (!$catid){
		?>
		<script language="javascript" type="text/javascript">
			<!--
			alert("<?php echo html_entity_decode(_ZOOM_NOCAT);?>");
			location = '<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=upload&formtype=single";?>';
			//-->
		</SCRIPT>
		<?php
		break;
	}
	// $image_name is an PHP auto-generated variable which holds the name of the uploaded file ($single_image).
	if ($single_image_name != ""){
		$tag = ereg_replace(".*\.([^\.]*)$", "\\1", $single_image_name);
		$tag = strtolower($tag);
		$setFilename = $_POST['setFilename'];
		if($setFilename)
			$name = $single_image_name;
		else
        	$name = $_POST['single_name'];
        $keywords = $_POST['single_keywords'];
        $descr = $_POST['single_descr'];
		//Check for right format
		if ($zoom->acceptableFormat($tag) || ($tag == "zip")){
			$imagepath = $zoom->_CONFIG['imagepath'];
			$catdir = $zoom->_gallery->getDir();
			$filename = urldecode($single_image_name);
			// if your platform is Windows, then the filename will be corrected (if necessary)...
			fs_import_filename($filename);
			// replace every space-character with a single "_"
			$filename = ereg_replace(" ", "_", $filename);
			// Get rid of extra underscores
			$filename = ereg_replace("_+", "_", $filename);
			$filename = ereg_replace("(^_|_$)", "", $filename);
			if (!isset($descr))
				$descr = $zoom->_CONFIG['tempDescr'];
			if ($tag == "zip"){
				// reset script execution time limit (as set in MAX_EXECUTION_TIME ini directive)...
				// requires SAFE MODE to be OFF!
				if( ini_get( 'safe_mode' ) != 1 ){
					set_time_limit(0);
				}
				// Extract functions
				$base_Dir = $mosConfig_absolute_path."/media/";
				if (move_uploaded_file("$single_image", "$base_Dir$filename")){
					$tmpdir = uniqid("zoom_");
					$extractdir = "media/".$tmpdir;
					$archivename = $base_Dir.$filename;
					$zoom->createdir($extractdir, 0777);
					@chmod($extractdir, 0777);
					if ($zoom->extractArchive($extractdir, $archivename)){
						// Extraction success, now scan the directory...
						$extr_images = $zoom->_toolbox->parseDir($extractdir, 0);
						// Create selection list in HTML
						$zoom->createCheckAllScript();
						?>
						<h2><?php echo _ZOOM_SCAN_STEP2;?></h2>
						<form name="select_img" method="post"action="index<?php echo ($zoom->_isBackend) ? "2" : "";?>.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=upload&formtype=single">
						<?php
						$zoom->createFileList($extr_images, $extractdir);
						?>
						<br />
						<center><input class="button" type="submit" value="<?echo _ZOOM_UPLOAD;?>" name="single_submit2"></center>
						<input type="hidden" name="catid" value="<?php echo $catid;?>">
						<?php
						$zoom->_counter = 0;
						foreach ($extr_images as $image){
							$name = $userfile_name[$zoom->_counter];
							?>
							<input type="hidden" name="images[]" value="<?php echo $image;?>">
							<input type="hidden" name="images_name[]" value="<?php echo $image;?>">
							<?php
							$zoom->_counter++;
						}
						echo ("<input type=\"hidden\" name=\"extractdir\" value=\"".$extractdir."\">\n"
						 . "<input type=\"hidden\" name=\"archivename\" value=\"".$archivename."\">\n"
						 . "<input type=\"hidden\" name=\"descr\" value=\"".$descr."\">\n"
                         . "<input type=\"hidden\" name=\"keywords\" value=\"".$keywords."\">\n"
                         . "<input type=\"hidden\" name=\"name\" value=\"".$name."\">\n"
                         . "<input type=\"hidden\" name=\"setFilename\" value=\"".$setFilename."\">\n"
						 . "<input type=\"hidden\" name=\"catdir\" value=\"".$catdir."\">\n");
						echo "</form>";
					}else{
						?>
						<script language="javascript" type="text/javascript">
							<!--
							alert("<?php echo html_entity_decode(_ZOOM_ALERT_PCLZIPERROR);?>");
							location = '<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=upload&formtype=single";?>';
							//-->
						</SCRIPT>
						<?
					}
				}
			// file isn't a zip-file, so look for other usable formats...
			}elseif($zoom->acceptableFormat($tag)){
                if($rotate[$img]){
                    $rotate = true;
                    $degrees = $_POST['degrees'];
                }
                $zoom->_toolbox->processImage($single_image, $filename, $keywords, $name, $descr, $rotate, $degrees);
                if($zoom->_toolbox->_err_num > 0){
                    $zoom->_toolbox->displayErrors($err_num, $err_names, $err_types);
                }else{
                	echo "<br /><center><h4>"._ZOOM_ALERT_UPLOADOK."</h4></center><br /><br />";
                }
			}else{
		      //Not the right format, back to uploadscreen
    			?>
    			<script language="javascript" type="text/javascript">
    				<!--
    				alert("<?php echo html_entity_decode(_ZOOM_ALERT_WRONGFORMAT);?>");
    				location = '<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=upload&formtype=single";?>';
    				//-->
    			</SCRIPT>
    			<?
            }
        }
	}else{
		?>
		<script language="javascript" type="text/javascript">
			<!--
			alert("<?php echo html_entity_decode(_ZOOM_ALERT_NOPICSELECTED);?>");
			location = '<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=upload&formtype=single";?>';
			//-->
		</SCRIPT>
		<?
	}
}elseif($single_submit2){
	// reset script execution time limit (as set in MAX_EXECUTION_TIME ini directive)...
	// requires SAFE MODE to be OFF!
	$edir = mosGetParam($_REQUEST,'extractdir');
	$extractdir = $mosConfig_absolute_path."/".$edir;
	$archivename = mosGetParam($_REQUEST,'archivename');
	$catdir = mosGetParam($_REQUEST,'catdir');
	$name = mosGetParam($_REQUEST,'name');
    $setFilename = mosGetParam($_REQUEST,'setFilename');
	$keywords = mosGetParam($_REQUEST,'keywords');
	$descr = mosGetParam($_REQUEST,'descr');
	$imagepath = $zoom->_CONFIG['imagepath'];
	$rotate = mosGetParam($_REQUEST,'rotate');
	$success = 0; //counts number of successfully processed files...
	foreach($scannedimg as $img){
		// this is the production code (for ZIP-files)...
		$theImage = $images[$img];
		$image_name = $images_name[$img];
		if (isset($setFilename))
			$name = $image_name;
        if($rotate[$img]){
            $rotate = true;
            $key = "rotate$success";
            $degrees = $_REQUEST[$key];
        }
        $zoom->_toolbox->processImage("$extractdir/$theImage", $image_name, $keywords, $name, $descr, $rotate, $degrees, 2);
        $success++;
	}
	if($zoom->_toolbox->_err_num >= 0){
		$zoom->_toolbox->displayErrors($err_num, $err_names, $err_types);
    }
	echo "<br /><center><h4>".$success." "._ZOOM_ALERT_UPLOADSOK."</h4></center><br /><br />";
	$retval = $zoom->deldir("$extractdir");
	// if the deldir function wasn't able to delete the dir, inform user
	if ($retval == false){
		echo "Could not delete the directory ".$extractdir." !";
	}
	@fs_unlink($archivename);
}else{
	//Show upload screen
	?>
	<form enctype="multipart/form-data" name="single_form" method="post" action="index<?php echo ($zoom->_isBackend) ? "2" : "";?>.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=upload&formtype=single">
	<table border="0" cellpadding="0" cellspacing="0">
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
		<td><?php echo _ZOOM_FORM_IMAGEFILE;?>:&nbsp;</td>
		<td align="left">
			<input class="inputbox" type="file" name="single_image" size="50" />
		</td>
	</tr>	
	<tr><td colspan="2">&nbsp;</td></tr>	
	<tr>
		<td><?php echo _ZOOM_FORM_INGALLERY;?>:&nbsp;</td>
		<td>
			<?php
			echo $zoom->createCatDropdown('catid','<OPTION value="">---&nbsp;'._ZOOM_PICK.'&nbsp;---</OPTION>', 0, $catid);
			?>
		</td>
	</tr>	
	<tr><td colspan="2">&nbsp;</td></tr>	
	<tr>
		<td><?php echo _ZOOM_NAME;?>:&nbsp;</td>
		<td>
			<input type="text" name="single_name" size="50" value="<?php echo $zoom->_CONFIG['tempName'];?>" class="inputbox" />
		</td>
	</tr>	
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="checkbox" name="setFilename" value="1"<?php if($zoom->_CONFIG['autonumber']) echo " checked";?> /> <?php echo _ZOOM_FORM_SETFILENAME;?>
		</td>
	</tr>	
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td><?php echo _ZOOM_KEYWORDS;?>:&nbsp;</td>
		<td>
			<input type="text" name="single_keywords" size="50" value="" class="inputbox" />
		</td>
	</tr>	
	<tr><td colspan="2">&nbsp;</td></tr>	
	<tr>
		<td valign="top"><?php echo _ZOOM_DESCRIPTION;?>:&nbsp;</td>
		<td>
			<textarea class="inputbox" cols="50" rows="5" name="single_descr"><?php echo $zoom->_CONFIG['tempDescr'];?></textarea>
		</td>
	</tr>	
	<tr><td colspan="2">&nbsp;</td></tr>	
	<tr>
		<td colspan="2" align="center">
			<input class="button" type="submit" name="single_submit" value="<?php echo _ZOOM_BUTTON_UPLOAD;?>" />
		</td>
	</tr>
	</table>
	</form>
	<?
	}
?>

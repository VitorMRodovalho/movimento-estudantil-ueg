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
| Filename: new.php                                                   |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
// retrieve all variables from the form...
if (mosGetParam($_REQUEST,'formsubmit')){
	$catname = mosGetParam($_REQUEST,'catname');
	$catdir = mosGetParam($_REQUEST, 'catdir');
	$after = mosGetParam($_REQUEST,'after');
	$hidemsg = mosGetParam($_REQUEST,'hidemsg');
	$catdescr = mosGetParam($_REQUEST,'catdescr');
	$catpass = mosGetParam($_REQUEST,'catpass');
	$keywords = mosGetParam($_REQUEST,'keywords');
	$shared = mosGetParam($_REQUEST,'shared');
	$published = mosGetParam($_REQUEST,'published');
	$selections = mosGetParam($_REQUEST,'selections');
	if (trim($catname)){
		//Create directory
		$zoom->checkDuplicate($catdir, 'directory');
		$mkdir = $zoom->_tempname;
        $html_file = "<html><body bgcolor=\"#FFFFFF\"></body></html>";
		if ($zoom->createdir($zoom->_CONFIG['imagepath'].$mkdir, 0777)){
			$zoom->createdir($zoom->_CONFIG['imagepath'].$mkdir."/thumbs", 0777);
			$zoom->createdir($zoom->_CONFIG['imagepath'].$mkdir."/viewsize", 0777);
            $zoom->writefile($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$mkdir."/index.html", $html_file);
            $zoom->writefile($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$mkdir."/thumbs/index.html", $html_file);
            $zoom->writefile($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$mkdir."/viewsize/index.html", $html_file);
			$descr = $zoom->cleanString($catdescr);
			//Save data in the database
			$database->setQuery("SELECT pos FROM #__zoom WHERE catid=$after");
			$result1 = $database->query();
			$row = mysql_fetch_object($result1);
			$pos = $row->pos;
			if($after==0){
				$pos = 0;
			}else{
				$pos++;
			}
			if(!isset($hidemsg)){
				$hidemsg = 0;
			}
			if($catpass != "" || !empty($catpass)){
				//hash the password with md5 encryption...
				$catpass = md5($catpass);
			}else{
				$catpass = "";
			}
			$uid = $zoom->_CurrUID;
            if(empty($selections))
                $selections = 1;
            else
                $selections = implode(',', $selections);
            // replace space-character with 'air'...or nothing!
            $keywords = ereg_replace(" ", "", $keywords);
			$database->setQuery("INSERT INTO #__zoom (catname,catdescr,catdir,catpassword,catkeywords,subcat_id,pos,hideMsg,shared,published,uid,catmembers) VALUES ('".mysql_escape_string($catname)."','".mysql_escape_string($catdescr)."','".mysql_escape_string($mkdir)."','".mysql_escape_string($catpass)."','".mysql_escape_string($keywords)."','$after','$pos', '$hidemsg','$shared','$published','$uid','$selections')");
			$database->query();
			?>
			<script language="javascript" type="text/javascript">
				<!--
				alert('<?php echo html_entity_decode(_ZOOM_ALERT_NEWGALLERY);?>');
				location = '<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=catsmgr";?>';
				//-->
			</SCRIPT>
			<?php
		}else{
			?>
			<script language="javascript" type="text/javascript">
				<!--
				alert('<?php echo "Error creating directory!";?>');
				location = "<?php echo "index".$backend.".php?option=com_zoom&page=catsmgr&Itemid=".$Itemid;?>";
				//-->
			</SCRIPT>
			<?
		}
	}else{
		//Back to new gallery page
		?>
		<script language="javascript" type="text/javascript">
			<!--
			alert("<?php echo html_entity_decode(_ZOOM_NONAME);?>");
			location = "<?php echo "index".$backend.".php?option=com_zoom&page=new&Itemid=".$Itemid;?>";
			//-->
		</SCRIPT>
		<?
	}
}else{
	//Show form
	?>
	<center>
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=admin";?>">
				<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN;?>" border="0" />&nbsp;&nbsp;<?php echo _ZOOM_MAINSCREEN;?>
			</a>&nbsp; | &nbsp;
			<a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=catsmgr";?>">
				<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/back.png" alt="<?echo _ZOOM_BACK;?>" border="0" />&nbsp;&nbsp;<?php echo _ZOOM_BACK;?>
			</a>
			</td>
		</tr>
		<tr>
			<td align="left">
				<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/new_f2.png" border="0" alt="<?php echo _ZOOM_HD_NEW;?>">
				&nbsp;<b><font size="4"><?php echo _ZOOM_HD_NEW;?></font></b>
			</td>
		</tr>
	</table>
	<br />
     <table cellspacing="0" cellpadding="4" border="0" width="100%">
     <tr>
         <td width="85%" class="tabpadding" align="center">
         <a href="javascript:document.forms.newcat.submit();" onmouseout="MM_swapImgRestore();return nd();"  onmouseover="MM_swapImage('save','','images/save_f2.png',1);return overlib('<?php echo _ZOOM_SAVE;?>');">
         	<img src="images/save.png" alt="<?php echo _ZOOM_BUTTON_CREATE;?>" border="0" name="save" />
         </a>
         <a href="javascript:document.forms.newcat.reset();" onmouseout="MM_swapImgRestore();return nd();"  onmouseover="MM_swapImage('cancel','','images/cancel_f2.png',1);return overlib('<?php echo _ZOOM_RESET;?>');">
         	<img src="images/cancel.png" alt="<?php echo _ZOOM_RESET;?>" border="0" name="cancel" />
         </a>
         </td>
     </tr>
     </table>
	<form method="post" name="newcat" action="index<?php echo ($zoom->_isBackend) ? "2" : "";?>.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=new">
	<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="<?php echo $mosConfig_live_site;?>/components/com_zoom/tabs/tabpane.css" />
	<script language="javascript" type="text/javascript" src="<?php echo $mosConfig_live_site;?>/components/com_zoom/tabs/tabpane.js"></script>
	<div class="tab-page" id="modules-cpanel">
		<script language="javascript" type="text/javascript">
			<!--
			var tabPane1 = new WebFXTabPane( document.getElementById( "modules-cpanel" ), 0 )
			//-->
		</script>
		<input type="hidden" name="formsubmit" value="submit">
    	<div class="tab-page" id="module19">
			<h2 class="tab"><?php echo _ZOOM_ITEMEDIT_TAB1;?></h2>
			<script language="javascript" type="text/javascript">
				<!--
				tabPane1.addTabPage( document.getElementById( "module19" ) );
				//-->
			</script>
			<table border="0" width="300">
				<tr>
					<td><?php echo _ZOOM_HD_AFTER;?>: </td>
					<td>
					<?php echo $zoom->createCatDropdown('after','<option value="0" selected>> '._ZOOM_TOPLEVEL.'</option>\n', 0, 0);?>
					</td>
				</tr>
				<tr>
					<td><?php echo _ZOOM_HD_HIDEMSG;?>:</td>
					<td>
						<input type="checkbox" name="hidemsg" value="1">
					</td>
				</tr>
				<tr>
					<td><?php echo _ZOOM_HD_NAME;?>:</td>
					<td>
						<input class="inputbox" type="text" name="catname" value="" size="50">
					</td>
				</tr>
				<tr>
					<td><?php echo _ZOOM_HD_DIR;?>:</td>
					<td>
						<input class="inputbox" type="text" name="catdir" value="<?php echo $zoom->newdir();?>" size="50">
					</td>
				</tr>
				<tr>
		        	<td><?php echo _ZOOM_PASS;?>:</td>
		        	<td>
		        		<input class="inputbox" type="password" name="catpass" value="" size="50">
		        	</td>
		      	</tr>
		        <tr>
		            <td><?php echo _ZOOM_KEYWORDS;?>: </td>
		            <td valign="center">
		            	<input type="text" name="keywords" size="50" value="" class="inputbox">
		            </td>
		        </tr>
				<tr>
					<td><?php echo _ZOOM_DESCRIPTION;?>:</td>
					<td>
						<textarea class="inputbox" name="catdescr" rows="5" cols="50"></textarea>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo _ZOOM_PUBLISHED;?>:
					</td>
					<td>
						<input type="checkbox" name="published" value="1" checked>
						<?php echo _ZOOM_HD_SHARE;?>: <input type="checkbox" name="shared" value="1">
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
		        $userlist = $zoom->getUsersList();
		        foreach($userlist as $item){
		            echo $item."\n";
		        }
		        ?>
		        </td>
		    </tr>
		    </table>
    	</div>
    </div>
	</form>
    </center><br />
	<?
}
?>

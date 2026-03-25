<?php
//zOOm Gallery//
/** 
-----------------------------------------------------------------------
|  zOOm Image Gallery! by Mike de Boer - a multi-gallery component    |
-----------------------------------------------------------------------

-----------------------------------------------------------------------
|                                                                     |
| Date: May, 2004                                                     |
| Author: Mike de Boer, <http://www.mikedeboer.nl>                    |
| Copyright: copyright (C) 2004 by Mike de Boer                       |
| Description: zOOm Image Gallery, a multi-gallery component for      |
|              Mambo based on RSGallery by Ronald Smit. It's the most |
|              feature-rich gallery component for Mambo!              |
| Filename: view.php                                                  |
| Version: 2.1                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// Delete overdue records of eCards from the database, before anyone can see them...
$database->setQuery("DELETE FROM #__zoom_ecards WHERE end_date >= now()");
$result = $database->query();

// Get the posted variables...
if(array_key_exists('task', $_REQUEST))
	$task = $_REQUEST['task'];
else
	$task = '';
if(array_key_exists('key', $_REQUEST)){
	$key = $_REQUEST['key'];
	// Get the image with the corresponding key...
	$zoom->_gallery->_images[$key]->getInfo();	
}

if($task == 'send' && !empty($submit)){
	// Save data to dbase & send an e-mail to the friend...
	$to_name = $_REQUEST['to_name'];
	$from_name = $_REQUEST['from_name'];
	$to_email = $_REQUEST['to_email'];
	$from_email = $_REQUEST['from_email'];
	$message = $_REQUEST['message'];
	$imgid = $zoom->_gallery->_images[$key]->_id;
	$zoom->setEcard();
	?>
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td width="30" class="<?php echo $zoom->_tabclass[1]; ?>">&nbsp;</td>
	    <td class="<?php echo $zoom->_tabclass[1]; ?>" align="left" valign="top">
			<a href="index.php?option=com_zoom&Itemid=<?php echo $Itemid;?>">
			<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?echo _ZOOM_MAINSCREEN;?>" border="0">&nbsp;&nbsp;<?php echo _ZOOM_MAINSCREEN;?>
			</a> > <a href="index.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&catid=<?php echo $zoom->_gallery->_id;?>"><?php echo $zoom->_gallery->_name;?>
			</a> > <strong><a href="index.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=view&catid=<?php echo $zoom->_gallery->_id;?>&key=<?php echo $key;?>"><?php echo $zoom->_gallery->_images[$key]->_filename;?></a></strong>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="left"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/ecard_big.png" border="0" alt="<?php echo _ZOOM_ECARD_SENDAS;?>">&nbsp;<b><font size="4"><?php echo _ZOOM_ECARD_SENDAS;?></font></b></td>
	</tr>
	</table>
	<br /><br /><center><h5>
	<?php
	if($zoom->_ecard->save($imgid, $to_name, $from_name, $to_email, $from_email, $message)){
		if($zoom->_ecard->send()){
			echo _ZOOM_ECARD_SUCCESS.'<br />';
			echo "<a href=\"index.php?option=com_zoom&Itemid=".$Itemid."&page=ecard&task=viewcard&ecdid=".$zoom->_ecard->_id."\"> "._ZOOM_ECARD_CLICKHERE."</a>";
		}else{
			echo _ZOOM_ECARD_ERROR.$to_email."!";
		}
	}else{
		echo _ZOOM_ECARD_ERROR.$to_email."!";
	}
	echo "</h5></center>";
}elseif($task == 'viewcard'){
	if(array_key_exists('ecdid', $_REQUEST))
		$ecdid = $_REQUEST['ecdid'];
	if(array_key_exists('back', $_REQUEST))
		$back = true;
	else
		$back = false;
	$zoom->setEcard($ecdid);
	$zoom->_ecard->_image->getInfo();
	$zoom->setGallery($zoom->_ecard->_image->_catid);
	?>
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td width="30" class="<?php echo $zoom->_tabclass[1]; ?>">&nbsp;</td>
	    <td class="<?php echo $zoom->_tabclass[1]; ?>" align="left" valign="top">
			<a href="index.php?option=com_zoom&Itemid=<?php echo $Itemid;?>">
			<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN;?>" border="0">&nbsp;&nbsp;<?php echo _ZOOM_MAINSCREEN;?></a>
			<a href="index.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&catid=<?php echo $zoom->_gallery->_id."\">".$zoom->_gallery->_name;?></a>
		</td>
	</tr>
	</table>
	<center>
	<?php
	if($back){
		// begin BACK HTML...
		?>
		<center>
			<a href="index.php?option=com_zoom&Itemid=<?php echo $Itemid."&page=ecard&task=viewcard&ecdid=".$ecdid;?>" onmouseover="return overlib('<?php echo _ZOOM_ECARD_TURN2;?>');" onmouseout="return nd();">
			<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/ecard_turn.png" border="0">
		</center>
		<table width="400" border="0" cellspacing="0" cellpadding="0" background="components/com_zoom/images/ecard_back.png" height="250">
			<tr height="250">
				<td align="center" valign="middle" width="198" height="250">
					<?php echo $zoom->_ecard->_message;?>
				</td>
				<td width="16" height="250"></td>
				<td height="250">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" height="250">
						<tr height="69">
							<td align="center" valign="middle" height="69"></td>
						</tr>
						<tr height="55">
							<td align="center" valign="middle" height="55">
								<?php echo _ZOOM_ECARD_SENDER;?>
							</td>
						</tr>
						<tr height="25">
							<td align="center" valign="middle" height="25">
								<?php echo $zoom->_ecard->_from_name;?>
							</td>
						</tr>
						<tr height="30">
							<td align="center" valign="bottom" height="30">
								<?php echo $zoom->_ecard->_from_email;?>
							</td>
						</tr>
						<tr>
							<td></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php
	}else{
		$img_path = $mosConfig_live_site."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$zoom->_ecard->_image->_filename;
		// begin FRONT HTML...
		echo ("<a href=\"index.php?option=com_zoom&Itemid=".$Itemid."&page=ecard&task=viewcard&ecdid=".$ecdid."&back=1\" onmouseover=\"return overlib('"._ZOOM_ECARD_TURN."');\" onmouseout=\"return nd();\">\n"
		 . "\t<img src=\"".$mosConfig_live_site."/components/com_zoom/images/ecard_turn.png\" border=\"0\" alt=\"\" />\n"
		 . "</a><br />\n");
		  if($zoom->isImage($zoom->_ecard->_image->_type)){
		  	if (isset($destWidth) && isset($destHeight)){
		  		?>
		  		<img src="<?php echo $img_path;?>" alt="" border="1" name="zImage" width="<?php echo $destWidth;?>" height="<?php echo $destHeight;?>">
		  		<?php
		  	}else{
		  		?>
		  		<img src="<?php echo $img_path;?>" alt="" border="1" name="zImage">
		  		<?php
		  	}
		  }elseif($zoom->isDocument($zoom->_ecard->_image->_type)){
		  	?>
		  	<img src="<?php echo $zoom->_ecard->_image->_thumbnail;?>" alt="" border="1" name="zImage">
		  	<?php
		  }elseif($zoom->isMovie($zoom->_ecard->_image->_type)){
		  	if($zoom->isRealMedia($zoom->_ecard->_image->_type)){
		  		?>
		  		<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" height="304" width="320">
		  			<param name="controls" value="ImageWindow">
		  			<param name="autostart" value="true">
		  			<param name="src" value="<?php echo $img_path;?>">
		  			<embed height="320" src="<?php echo $img_path;?>" type="audio/x-pn-realaudio-plugin" width="304" controls="ImageWindow" autostart="true"> 
		  		</object>
		  		<?php
		  	}elseif($zoom->isQuicktime($zoom->_ecard->_image->_type)){
		  		?>
		  		<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="304" width="320">
		  			<param name="src" value="<?php echo $img_path;?>">
		  			<param name="autoplay" value="true">
		  			<param name="controller" value="false">
		  			<embed height="304" pluginspage="http://www.apple.com/quicktime/download/" src="<?php echo $img_path;?>" type="video/quicktime" width="320" controller="false" autoplay="true"> 
		  		</object>
		  		<?php
		  	}else{
		  		?>
		  		<object id="MediaPlayer1" width="320" height="304" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" type="application/x-oleobject">
		  			<param name="URL" value="<?php echo $img_path;?>" />
		  			<embed src="<?php echo $img_path;?>" height="304" width="320" border="0" type="application/x-mplayer2"/></embed>
		  		</object>
		  		<?php
		  	}
		  }
	}
}else{
	// Display form with image and userfields...
	$img_path = $mosConfig_live_site."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$zoom->_gallery->_images[$key]->_filename;
	?>
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
       	<td width="30" class="<?php echo $zoom->_tabclass[1]; ?>">&nbsp;</td>
       	<td class="<?php echo $zoom->_tabclass[1]; ?>" align="left" valign="top">
			<a href="index.php?option=com_zoom&Itemid=<?php echo $Itemid;?>">
			<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?echo _ZOOM_MAINSCREEN;?>" border="0">&nbsp;&nbsp;<?echo _ZOOM_MAINSCREEN;?>
			</a> > <a href="index.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&catid=<?php echo $zoom->_gallery->_id;?>"><?echo $zoom->_gallery->_name;?>
			</a> > <strong><a href="index.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=view&catid=<?php echo $zoom->_gallery->_id;?>&key=<?php echo $key;?>"><?php echo $zoom->_gallery->_images[$key]->_filename;?></a></strong>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="left"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/ecard_big.png" border="0" alt="<?php echo _ZOOM_ECARD_SENDAS;?>">&nbsp;<b><font size="4"><?php echo _ZOOM_ECARD_SENDAS;?></font></b></td>
	</tr>
	</table>
	<center>
	<br />
	<?php
	  if($zoom->isImage($zoom->_gallery->_images[$key]->_type)){
	  	if (isset($destWidth) && isset($destHeight)){
	  		?>
	  		<img src="<?php echo $img_path;?>" alt="" border="1" name="zImage" width="<?php echo $destWidth;?>" height="<?php echo $destHeight;?>">
	  		<?php
	  	}else{
	  		?>
	  		<img src="<?php echo $img_path;?>" alt="" border="1" name="zImage">
	  		<?php
	  	}
	  }elseif($zoom->isDocument($zoom->_gallery->_images[$key]->_type)){
	  	?>
	  	<img src="<?php echo $zoom->_gallery->_images[$key]->_thumbnail;?>" alt="" border="1" name="zImage">
	  	<?php
	  }elseif($zoom->isMovie($zoom->_gallery->_images[$key]->_type)){
	  	if($zoom->isRealMedia($zoom->_gallery->_images[$key]->_type)){
	  		?>
	  		<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" height="304" width="320">
	  			<param name="controls" value="ImageWindow">
	  			<param name="autostart" value="true">
	  			<param name="src" value="<?php echo $img_path;?>">
	  			<embed height="320" src="<?php echo $img_path;?>" type="audio/x-pn-realaudio-plugin" width="304" controls="ImageWindow" autostart="true"> 
	  		</object>
	  		<?php
	  	}elseif($zoom->isQuicktime($zoom->_gallery->_images[$key]->_type)){
	  		?>
	  		<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="304" width="320">
	  			<param name="src" value="<?php echo $img_path;?>">
	  			<param name="autoplay" value="true">
	  			<param name="controller" value="false">
	  			<embed height="304" pluginspage="http://www.apple.com/quicktime/download/" src="<?php echo $img_path;?>" type="video/quicktime" width="320" controller="false" autoplay="true"> 
	  		</object>
	  		<?php
	  	}else{
	  		?>
	  		<object id="MediaPlayer1" width="320" height="304" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" type="application/x-oleobject">
	  			<param name="URL" value="<?php echo $img_path;?>" />
	  			<embed src="<?php echo $img_path;?>" height="304" width="320" border="0" type="application/x-mplayer2"/></embed>
	  		</object>
	  		<?php
	  	}
	  }
	?>
	<br />
	<form name="ecard" method="post" action="index.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=ecard&task=send&catid=<?php echo $zoom->_gallery->_id;?>&key=<?php echo $key;?>" onSubmit="return validateCard(this)">
		<table border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td	nowrap><?php echo _ZOOM_ECARD_YOURNAME;?>:</td>
			<td><input name="from_name"	type="text"	class="inputbox"></td>
		</tr>
		<tr>
			<td	nowrap><?php echo _ZOOM_ECARD_YOUREMAIL;?>:</td>
			<td><input name="from_email" type="text" class="inputbox"></td>
		</tr>
		<tr>
			<td	nowrap><?php echo _ZOOM_ECARD_FRIENDSNAME;?>:</td>
			<td><input name="to_name" type="text" class="inputbox"></td>
		</tr>
		<tr>
			<td	nowrap><?php echo _ZOOM_ECARD_FRIENDSEMAIL;?>:</td>
			<td><input name="to_email" type="text" class="inputbox"></td>
		</tr>
		<tr>
			<td	nowrap valign="top"><?php echo _ZOOM_ECARD_MESSAGE;?>:</td>
			<td><textarea name="message" id="message" class="inputbox" rows=3 cols=25></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" name="submit" value="<?php echo _ZOOM_ECARD_SENDCARD;?>" class="button">
			</td>
		</tr>
		</table>
	</form>
	</center>
	<?php
}
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
| Filename: galleryshow.php                                           |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
if (!isset($catid)){
	//No gallery selected, show main screen
	$zoom->createSubmitScript('browse');
	?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left" class="componentheader" width="30%"%"><h3><?php echo $zoom->_CONFIG['zoom_title'];?></h3></td>
		<?php
		if($zoom->_CONFIG['showSearch'] && $zoom->_CONFIG['showKeywords']){
		?>
		<td align="right" valign="bottom" class="componentheader">
			<div align="right">
			<form action="index.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=search&type=quicksearch" method="POST" name="browse">
			<?php
			echo $zoom->createKeywordsDropdown('sstring', '<option value="">>>'._ZOOM_SEARCH_KEYWORD.'<<</option>', 1);
			?>
			</form>
			</div>
		</td>
		<?php
		}
		?>
		<td align="right" valign="bottom" class="componenentheader" width="200">
			<div align="right">
			<?php if ($zoom->_CONFIG['displaylogo']){ ?>
				<a href="http://zoom.ummagumma.nl" target="_blank"><img src="components/com_zoom/images/zoom_logo_small.gif" border="0" alt=""></a>
			<?php
			}
			if($zoom->_CONFIG['showSearch']){
			?>
			<form name="searchzoom" action="index.php" target=_top method="post">
			<input type="hidden" name="option" value="com_zoom">
			<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>">
			<input type="hidden" name="page" value="search">
			<input type="hidden" name="type" value="quicksearch">
			<input type="hidden" name="sorting" value="3">
			<input type="text" name="sstring" onBlur="if(this.value=='') this.value='<?php echo _ZOOM_SEARCH_BOX;?>';" onFocus="if(this.value=='<?php echo _ZOOM_SEARCH_BOX;?>') this.value='';" VALUE="<?php echo _ZOOM_SEARCH_BOX;?>" class="inputbox">
			<a href="javascript:document.forms.searchzoom.submit();">&nbsp;<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/find.png" border="0" width="16" height="16"></a>
			</form>
			</div>
			<?php
			}
			?>
		</td>
	</tr>
	</table>
	<table border="0" width="100%" cellspacing="4" cellpadding="5">
	<tr><td width="6%">&nbsp;</td>
	<?php
	//Get every category from the database and echo it on the screen
	$zoom->_counter = 0;
	$orderMethod = $zoom->getCatOrderMethod();
	if($zoom->_isAdmin)
		$database->setQuery("SELECT catid FROM #__zoom WHERE subcat_id=0 AND pos=0 ORDER BY ".$orderMethod);
	else
		$database->setQuery("SELECT catid FROM #__zoom WHERE subcat_id=0 AND pos=0 AND published=1 ORDER BY ".$orderMethod);
	$zoom->_result = $database->query();
	while($row = mysql_fetch_object($zoom->_result)){
	 $zoom->setGallery($row->catid, true);
	 if ($zoom->_CONFIG['catImg'] && $zoom->_gallery->isMember())
	 	$zoom->_gallery->setCatImg();
	 //select the first image from the gallery handled by the loop...
	 if ($zoom->_CONFIG['showMetaBox']) {
	  	 // display category info, including image...
		 $img_num = $zoom->_gallery->getNumOfImages();
		 $subcat_num = $zoom->_gallery->getNumOfSubCats();
		 $subcat_html = ($subcat_num <= 0) ? "" : ", ".$subcat_num." "._ZOOM_SUBGALLERIES;
	 }	 
	 if ($zoom->_counter >= $zoom->_CONFIG['catcolsno']){
	    echo "</tr><tr><td>&nbsp;</td>";
	    $zoom->_counter = 0;
	 }
	 if ($zoom->_CONFIG['catImg'] && $zoom->_gallery->isMember()){
		?>
 		<td align=left valign="top" width="47%"><a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_id);?>" <?php echo ($zoom->_CONFIG['showMetaBox']) ? "onmouseover=\"return overlib('".$img_num." "._ZOOM_IMAGES.$subcat_html."', CAPTION, '".$zoom->_gallery->_name."');\" onmouseout=\"return nd();\"" : "";?>>
 		<br><img border="0" hspace="0" src="<?php echo (empty($zoom->_gallery->_cat_img->_thumbnail)) ? 'components/com_zoom/images/noimg.gif' : $zoom->_gallery->_cat_img->_thumbnail;?>">
 		<?php
	 }else{
	 	?>
	 	<td align=left valign="top" width="50%"><a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_id);?>" <?php echo ($zoom->_CONFIG['showMetaBox']) ? "onmouseover=\"return overlib('".$img_num." "._ZOOM_IMAGES.$subcat_html."', CAPTION, '".$zoom->_gallery->_name."');\" onmouseout=\"return nd();\"" : "";?>>
	 	<?php
	 }
	 echo "<br>".$zoom->_CONFIG['galleryPrefix'].$zoom->_gallery->_name."</a><br />".$zoom->_gallery->_descr;
	 if(!$zoom->_gallery->_published)
	 	echo "<br /><font color=\"red\">(unpublished)</font>";
	 echo "</td>\n";
	 $zoom->_counter++;
	}
    ?>
	</tr>
	</table>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<?php
	    if($zoom->_isAdmin){
      		print '<td align="left" class="'.$zoom->_tabclass[1].'"><a href="'.sefRelToAbs('index.php?option=com_zoom&Itemid=' .$Itemid. '&page=admin').'"><img src="images/M_images/arrow.png" border="0"> '._ZOOM_ADMINSYSTEM.'</a></td>';
		}elseif($zoom->_isUser && $zoom->_CONFIG['allowUserUpload']){
	    	print '<td align="left" class="'.$zoom->_tabclass[1].'"><a href="'.sefRelToAbs('index.php?option=com_zoom&Itemid=' .$Itemid. '&page=admin').'"><img src="images/M_images/arrow.png" border="0"> '._ZOOM_USERSYSTEM.'</a></td>';
		}
	    ?>
		<td align="right" colspan="3" class="<?php echo $zoom->_tabclass[1];?>">
		<div align="right">
		<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=special&sorting=0");?>"><?php echo _ZOOM_TOPTEN;?></a> |&nbsp;
		<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=special&sorting=1");?>"><?php echo _ZOOM_LASTSUBM;?></a>
		<?php if($zoom->_CONFIG['commentsOn']){?>
			 |&nbsp;<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=special&sorting=2");?>"><?php echo _ZOOM_LASTCOMM;?></a>
		<?php }if($zoom->_CONFIG['ratingOn']){?>
			 |&nbsp;<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=special&sorting=4");?>"><?php echo _ZOOM_TOPRATED;?></a>
		<?php } ?>
		</div>
		</td>
	</tr>
	</table>
	<?php
}else{
	if(!isset($catpass) && strlen($zoom->_gallery->_password) > 0 && !$zoom->_isAdmin && !$zoom->_EditMon->isEdited($zoom->_gallery->_id, 'pass')){
		?>
		<center>
		<form name="form1" method="post" action="index.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&catid=<?php echo $catid;?>">
		<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td class="sectiontableheader" colspan="2">
				<?php echo _ZOOM_PASS_REQUIRED;?>
			</td>
		</tr>
		<tr height="50">
			<td class="<?php echo $zoom->_tabclass[0];?>">
				<?php echo _ZOOM_PASS;?>:
			</td>
			<td class="<?php echo $zoom->_tabclass[0];?>">
       			<input name="catpass" type="password" size="10">
			</td
		</tr>
		<tr>
			<td class="<?php echo $zoom->_tabclass[1];?>" colspan="2" align="center">
				<div align="center">
				<input type="submit" name="submit" value="<?php echo _ZOOM_PASS_BUTTON; ?>" class="button">
				<script language="javascript" type="text/javascript">
					<!--
					 form1.catpass.focus();
					 form1.catpass.select();
					 //-->
				</script>
				</div>
			</td>
		</tr>
		</table>
		</form>
		</center>
		<?php
	}elseif(isset($catpass)){
		if($zoom->_gallery->checkPassword($catpass)){
			$valid = true;
		}else{
			?>
			<script language="javascript" type="text/javascript">
				<!--
				alert('<?php echo html_entity_decode(_ZOOM_PASS_INNCORRECT);?>');
				location = '<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_subcat_id);?>';
				//-->
			</SCRIPT>
			<?php
		}
	}else{
		$valid = true;
	}
	if($valid && $zoom->_gallery->isMember()){
	$imagedir = $zoom->_gallery->_dir;
	$parent = $zoom->_gallery->getSubcatName();
	?>
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
    	<td width="30" class="sectiontableheader"></td>
        <td class="sectiontableheader">
			<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid);?>">
			<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN;?>" border="0">&nbsp;&nbsp;<?php echo _ZOOM_MAINSCREEN;?>
			</a>
			<?php
			if ($zoom->_gallery->_pos==0) echo " > ";
			elseif ($zoom->_gallery->_pos==1) echo " > <a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_subcat_id)."\">".$parent."</a> > ";
			elseif ($zoom->_gallery->_pos>=2) echo " >..> <a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_subcat_id)."\">".$parent."</a> > "; 
			echo $zoom->_gallery->_name;
			if(!$zoom->_gallery->_published)
				echo " <font color=\"red\">(unpublished)</font>";
			if($zoom->_isAdmin){
            	print ' | <a href="'.sefRelToAbs('index.php?option=com_zoom&Itemid=' .$Itemid. '&page=admin').'">'._ZOOM_ADMINSYSTEM.'</a>';
			}else if($zoom->_isUser && $zoom->_CONFIG['allowUserUpload']){
				print ' | <a href="'.sefRelToAbs('index.php?option=com_zoom&Itemid=' .$Itemid. '&page=admin').'">'._ZOOM_USERSYSTEM.'</a>';
			}
	   		?>
		</td>
		<?php
		if($zoom->_CONFIG['lightbox'] && $zoom->_gallery->getNumOfImages() > 0){
			?>
			<td align="right" class="sectiontableheader">
				<div align="right">
				<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&action=add&catid=".$zoom->_gallery->_id);?>" onmouseover="return overlib('<?php echo _ZOOM_LIGHTBOX_GALLERY;?>', CAPTION, '<?php echo _ZOOM_LIGHTBOX;?>');" onmouseout="return nd();"><img src="components/com_zoom/images/lightbox.png" border="0" name="lightbox"></a>
				</div>
			</td>
			<?php
		}
		?>
	</tr>
	</table>
	<center>
	<table border="0" cellspacing="4" cellpadding="5" width="80%">
	<tr>
		<?php
		$zoom->_counter = 0;
		foreach($zoom->_gallery->_subcats as $subcat){
			array_shift($zoom->_gallery->_subcats);
			if ($zoom->_counter >= $zoom->_CONFIG['catcolsno']){
				echo "<td>&nbsp;</td></tr><tr>\n";
				$zoom->_counter = 0;
			}
			if ($zoom->_CONFIG['catImg']){
				$subcat->setCatImg();
			}
			if ($zoom->_CONFIG['showMetaBox']) {
				$img_num = $subcat->getNumOfImages();
				$subcat_num = $subcat->getNumOfSubCats();
				$subcat_html = ($subcat_num <= 0) ? "" : ", ".$subcat_num." "._ZOOM_SUBGALLERIES;
			}			
			if ($zoom->_CONFIG['catImg'] && $subcat->isMember()){
				?>
				<td valign="top" align="left">
					<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&catid=".$subcat->_id);?>" <?php echo ($zoom->_CONFIG['showMetaBox']) ? "onmouseover=\"return overlib('".$img_num." "._ZOOM_IMAGES.$subcat_html."', CAPTION, '".$subcat->_name."');\" onmouseout=\"return nd();\"" : "";?>>
					<br><img src="<?php echo ($subcat->_cat_img->_thumbnail == "") ? 'components/com_zoom/images/folder.png' : $subcat->_cat_img->_thumbnail;?>" border="0">
				<?php
			}else{
				?>
				<td align="left" valign="top" width="50%"><a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&catid=".$subcat->_id);?>" <?php echo ($zoom->_CONFIG['showMetaBox']) ? "onmouseover=\"return overlib('".$img_num." "._ZOOM_IMAGES.$subcat_html."', CAPTION, '".$subcat->_name."');\" onmouseout=\"return nd();\"" : "";?>>
				<?php
			}
			echo "<br>".$zoom->_CONFIG['galleryPrefix'].$subcat->_name."</a><br />".$subcat->_descr;
			if(!$subcat->_published)
				echo "<br /><font color=\"red\">(unpublished)</font>";
			echo "</td>\n";
			$zoom->_counter++;
		}
		?>
	</tr>
	</table>
	<br />
	<?php
	$i = 1;
	$zoom->_counter = 0;
	$startRow = 0;

	//Set the page no
	if(empty($_REQUEST['PageNo'])){
	    if($startRow == 0){
	        $PageNo = $startRow + 1;
	    }
	}else{
	    $PageNo = $_REQUEST['PageNo'];
	    $startRow = ($PageNo - 1) * $zoom->_CONFIG['PageSize'];
	}	
 	//Total of record
 	$RecordCount = $zoom->_gallery->getNumOfImages();//Number of files in gallery
	$endRow = $startRow + $zoom->_CONFIG['PageSize'] -1;
	if ($endRow >= $RecordCount)
		$endRow = $RecordCount - 1;
 	//Set Maximum Page
 	$MaxPage = ceil($RecordCount % $zoom->_CONFIG['PageSize']);
 	if($RecordCount % $zoom->_['PageSize'] == 0){
    	$MaxPage = ceil($RecordCount / $zoom->_CONFIG['PageSize']);
 	}else{
    	$MaxPage = ceil($RecordCount / $zoom->_CONFIG['PageSize']);
 	}
 	//Set the counter start
	$CounterStart = 1;
	//Counter End
	$CounterEnd = $MaxPage;	
 	
 	$inforow = "";
 	?>
 	<table border="0" cellpadding="3" cellspacing="5" width="400">
	<tr>
		<td colspan="<?php echo $zoom->_CONFIG['columnsno']?>" align="center">
		<div align="center">
			<H2><?php echo $zoom->_gallery->_name;?></H2>
			<?php
			if ($RecordCount != 0){
				echo $RecordCount." "._ZOOM_IMAGES." "._ZOOM_IMGFOUND." ".$PageNo." "._ZOOM_IMGFOUND2." ".$MaxPage;
			}elseif(!$zoom->_gallery->_hideMsg){
				echo "<span class=\"small\">"._ZOOM_NOPICS."</span>";
			}
			?>
		</div>
		<br /><br />
		</td>
	</tr>
	<tr>
	<?php
	$columnwidth = round(100 / $zoom->_CONFIG['columnsno']);
	for($counter = $startRow; $counter <= $endRow; $counter++){
		$image = $zoom->_gallery->_images[$counter];
		$image->getInfo();
		// if($zoom->_CONFIG['viewtype'] == 1){ viewtype is going to implemented later on (with CSS support)...
		if ($image->isMember()) {
			// Basic and original multi-column compact style layout...
			$features =  "\t\t<td align=\"center\" valign=\"bottom\" width=\"".$columnwidth."%\">\n";
			if ($zoom->isImage($image->_type)) {
				$size= getimagesize($zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$image->_filename);
			}
			if($zoom->_CONFIG['lightbox']){
	  			$features .= ("\t\t<a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&action=add&catid=".$zoom->_gallery->_id."&key=".$counter."&PageNo=".$PageNo)."\" onmouseover=\"return overlib('"._ZOOM_LIGHTBOX_ITEM."', CAPTION, '"._ZOOM_LIGHTBOX."');\" onmouseout=\"return nd();\">\n"
				 . "\t\t<img src=\"components/com_zoom/images/lb_small.png\" border=\"0\" name=\"lb_small".$counter."\"></a>&nbsp;\n");
	        }
			if ($zoom->_isAdmin || ($zoom->_isUser && $image->_uid == $zoom->_CurrUID)){
				$features .= ("\t\t<a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&action=delimg&catid=".$zoom->_gallery->_id."&key=".$counter."&PageNo=".$PageNo)."\" onmouseover=\"return overlib('"._ZOOM_DELETE."', CAPTION, '"._ZOOM_ACTION."');\" onmouseout=\"return nd();\"><img src=\"components/com_zoom/images/delete.png\" onClick=\"return confirm('"._ZOOM_CONFIRM_DELMEDIUM."');\" border=\"0\" name=\"delimg".$counter."\"></a>\n"
				 . "&nbsp;<a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&page=editpic&catid=".$zoom->_gallery->_id."&key=".$counter."&PageNo=".$PageNo)."\" onmouseover=\"return overlib('"._ZOOM_BUTTON_EDIT."', CAPTION, '"._ZOOM_ACTION."');\" onmouseout=\"return nd();\"><img src=\"components/com_zoom/images/edit.png\" border=\"0\" name=\"editimg".$counter."\"></a>\n");
			}
			if($zoom->_CONFIG['lightbox'] || $zoom->_isAdmin || ($zoom->_isUser && $image->_uid == $zoom->_CurrUID))
				$features .= "<br />\n";
			echo $features;
			$descr = $zoom->removeTags($image->_descr);
			if ($zoom->_CONFIG['showMetaBox']) {
				$link = "<a onmouseover=\"return overlib('".$descr."', CAPTION, '".$image->_name."');\" onmouseout=\"return nd();\"";
			}else{
				$link = "<a";
			}
			if (!$zoom->_CONFIG['popUpImages']){
				$link .= " href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&page=view&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo."&key=".$counter."&hit=1")."\">\n";
			}else{
				$link .= " href=\"javascript:void(0)\" onClick=\"window.open('components/com_zoom/view.php?popup=1&catid=".$zoom->_gallery->_id."&key=".$counter."&isAdmin=".$zoom->_isAdmin."&hit=1', 'win1', 'width=";
				if($size[0]<550){
					$link .= "550";
				}elseif($size[0]>$zoom->_CONFIG['maxsize']){
					$link .= $zoom->_CONFIG['maxsize'] + 50;
				}else{
					$link .= $size[0] + 40;
				}
				$link .= ", height=";
				if($size[1]<550){
					$link .= "550";
				}elseif($size[1]>$zoom->_CONFIG['maxsize']){
					$link .= $zoom->_CONFIG['maxsize'] + 50;
				}else{
					$link .= $size[1] + 100;
				}
				$link .= ", scrollbars=1').focus()\">\n";
			}
			$link .= "<img border=\"1\" src=\"".$image->_thumbnail."\" />\n<br /></a>\n</td>\n";
			echo $link;
			// begin inforow here...
			$inforow .= "\t\t<td align=\"center\" valign=\"top\" width=\"".$columnwidth."%\">\n\t\t";
			if ($zoom->_CONFIG['showName']) {
				$inforow .= (empty($image->_name)) ? $image->_filename : $image->_name;
				$inforow .= "<br />\n";
			}
			if($zoom->_CONFIG['commentsOn']){
				// Adding comment-notification, eg. show a pic with last comment-author and date as alt-text.
				if($mycom = $image->_comments[0]){
					$inforow .= "\t\t<img border=\"0\" src=\"components/com_zoom/images/comment.gif\" onmouseover=\"return overlib('".$mycom->_name.": ".$mycom->_date."', CAPTION, '"._ZOOM_COMMENTS."');\" onmouseout=\"return nd();\">= ".$image->getNumOfComments();
					if ($zoom->_CONFIG['showHits']) {
						$inforow .= ", ";
					}
				}
			}
			if ($zoom->_CONFIG['showHits'])
				$inforow .= $image->_hits . 'x ' . _ZOOM_HITS . "\n";
			$inforow .= "\t\t</td>\n";
			//Counter to count the number of rows...
			$zoom->_counter++;
			$i++;
			if ($zoom->_counter % $zoom->_CONFIG['columnsno'] == 0){ 
				echo "</tr><tr>\n";
				$inforow .= "\t\t</tr><tr>\n";
				echo $inforow;
				$inforow = "";
			}elseif($counter == $endRow && $zoom->_counter % $zoom->_CONFIG['columnsno'] != 0){
				$remainder = $zoom->_CONFIG['columnsno'] - ($zoom->_counter % $zoom->_CONFIG['columnsno']);
				for($x = 0; $x < $remainder; $x++){
					echo "<td>&nbsp;</td>\n";
					$inforow .= "<td>&nbsp;</td>\n";
				}
				$inforow .= "\t\t</tr><tr>\n";
				echo "</tr><tr>\n";
				echo $inforow;
			}
		}// END if isMember()
		/**
		}elseif ($zoom->_CONFIG['viewtype'] == 2){
			// flat style (simple table layout...)
			
		}
		**/
	}// END for loop images.
	?>
	</tr>
	<tr>
		<td colspan="<?php echo $zoom->_CONFIG['columnsno']; ?>" align="center">
		<br />
		<div align="center">
		<?php
        //Print First & Previous Link if necessary
        if($PageNo != 1){
            $PrevStart = $PageNo - 1;
            echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&catid=$catid&PageNo=1")."\">"._ZOOM_FIRST." </a>: ";
            echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&catid=$catid&PageNo=$PrevStart")."\">"._ZOOM_PREVIOUS." << </a>";
        }
        $c = 0;
        //Print Page No
        for($c=$CounterStart;$c<=$CounterEnd;$c++){
            if($c < $MaxPage){
                if($c == $PageNo){
                    if($c % $RecordCount == 0){
                        echo "<u><strong>$c</strong></u> ";
                    }else{
                        echo "<u><strong>$c</strong></u> | ";
                    }
                }elseif($c % $RecordCount == 0){
                    echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&catid=$catid&PageNo=$c")."\"><strong>$c</strong></a> ";
                }else{
                    echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&catid=$catid&PageNo=$c")."\"><strong>$c</strong></a> | ";
                }//END IF
            }else{
                if($PageNo == $MaxPage){
                    echo "<u><strong>$c</strong></u> ";
                }else{
                    echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&catid=$catid&PageNo=$c")."\"><strong>$c</strong></a> ";
                }
            }
       }
      if($PageNo < $MaxPage){
          $NextPage = $PageNo + 1;
          echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&catid=$catid&PageNo=$NextPage")."\">>> "._ZOOM_NEXT."</a>";
          echo " : <a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&catid=$catid&PageNo=$MaxPage")."\">"._ZOOM_LAST."</a>";
      }
      ?>
      </div>
      <br />
	  </td>
	</tr>
	</table>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<?php
		if($zoom->_CONFIG['showSearch'] && $zoom->_CONFIG['showKeywords']){
			$zoom->createSubmitScript('browse');
		?>
		<td align="left" valign="top" class="sectiontableheader">
			<form action="index.php?option=com_zoom&Itemid=<?php echo $Itemid;?>&page=search&type=quicksearch" method="POST" name="browse">
			<?php echo $zoom->createKeywordsDropdown('sstring', '<option value="">>>'._ZOOM_SEARCH_KEYWORD.'<<</option>', 1);?>
			&nbsp;
			</form>
		</td>
		<td align="left" valign="top" class="sectiontableheader">
            <form name="searchzoom" action="index.php" target=_top method="post">
			<input type="hidden" name="option" value="com_zoom">
			<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>">
			<input type="hidden" name="page" value="search">
			<input type="hidden" name="type" value="quicksearch">
			<input type="hidden" name="sorting" value="3">
			<input type="text" name="sstring" onBlur="if(this.value=='') this.value='<?php echo _ZOOM_SEARCH_BOX;?>';" onFocus="if(this.value=='<?php echo _ZOOM_SEARCH_BOX;?>') this.value='';" VALUE="<?php echo _ZOOM_SEARCH_BOX;?>" class="inputbox">
			<a href="javascript:document.forms.searchzoom.submit();">
				<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/find.png" border="0" width="16" height="16"></a>
			</form>
		</td>
		<?php
		} 
		if($zoom->_CONFIG['lightbox']){ 
		?>
		<td align="right" valign="top" class="sectiontableheader">
			<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$catid."&PageNo=".$PageNo);?>">
				<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/lb_view.png" border="0" /><?php echo _ZOOM_LIGHTBOX_VIEW;?>
			</a>
		</td>
		<?php
		} ?>
	</tr>
	</table>
	<?php
	}else{
		echo "Access not allowed. If you continue to experience this problem, please contact the administrator.";
	}
}
?>

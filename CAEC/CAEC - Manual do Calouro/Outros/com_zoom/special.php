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
| Filename: special.php                                               |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
/**
 There are three special image-display formats:
 0: Top ten viewed images (most hits)
 1: Ten last submitted images (last id's)
 2: Ten last commented images
 4: Top rated
**/
$sorting = mosGetParam($_REQUEST, 'sorting');
switch($sorting){
  case 0:
    $database->setQuery("SELECT DISTINCT img.imgid AS id, img.catid AS gallery_id FROM #__zoomfiles AS img LEFT JOIN #__zoom AS cats ON img.catid = cats.catid WHERE imghits > 0 AND cats.catpassword = '' AND img.published = 1 ORDER BY imghits DESC LIMIT 10");
  break;

  case 1:
    $database->setQuery("SELECT DISTINCT img.imgid AS id, img.catid AS gallery_id FROM #__zoomfiles AS img LEFT JOIN #__zoom AS cats ON img.catid = cats.catid WHERE cats.catpassword = '' AND img.published = 1 ORDER BY img.id DESC LIMIT 10");
  break;

  case 2:
    $database->setQuery("SELECT DISTINCT com.imgid, img.imgid AS id, img.catid AS gallery_id, max(com.cmtid) as maxcmt FROM #__zoomfiles AS img, #__zoom_comments AS com WHERE com.imgid = img.imgid AND img.published = 1 Group By id ORDER BY maxcmt desc LIMIT 10");
  break;
  
  case 4:
    $database->setQuery("SELECT DISTINCT img.imgid AS id, img.catid AS gallery_id, img.votenum, (img.votesum/img.votenum) AS rating FROM #__zoomfiles AS img LEFT JOIN #__zoom AS cats ON img.catid = cats.catid WHERE img.votesum > 0 AND img.votenum > 0 AND cats.catpassword = '' AND img.published = 1 ORDER BY rating desc, img.votenum DESC LIMIT 10");
  break;
  
  default:
    die("You must visit this page the legit way, remember?");
}
// Displaying query-results:
$zoom->_result = $database->query();
?>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td width="30" class="<?php echo $zoom->_tabclass[1]; ?>"></td>
    <TD class="<?php echo $zoom->_tabclass[1]; ?>">
      <a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid);?>">
       <img src="components/com_zoom/images/home.gif" alt="<?echo _ZOOM_MAINSCREEN;?>" border="0">&nbsp;&nbsp;<?echo _ZOOM_MAINSCREEN;?></a> > 
      <?php
      if($sorting==0) echo _ZOOM_TOPTEN;
      else if($sorting==1) echo _ZOOM_LASTSUBM;
      else if($sorting==2) echo _ZOOM_LASTCOMM;
      else if($sorting==4) echo _ZOOM_TOPRATED; ?>
    </td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<?php
$i = 0;
$imgcnt = 0;
$tabcnt = 0;
while($row = mysql_fetch_object($zoom->_result)){
  $imgcnt++;
  $zoom->setGallery($row->gallery_id);
  $zoom->_counter = 0;
  foreach($zoom->_gallery->_images as $image){
    if($image->_id == $row->id){
      $i = $zoom->_counter;
    }
    $zoom->_counter++;
  }
  $zoom->_gallery->_images[$i]->getInfo();
  echo '<tr class='.$zoom->_tabclass[$tabcnt].'><td width="20">&nbsp; '.$imgcnt.' &nbsp;</td>';
  $size = getimagesize($zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$zoom->_gallery->_images[$i]->_filename);
  if (!$zoom->_CONFIG['popUpImages']){ ?>
    <td align="left" width="<?php echo $zoom->_CONFIG['size'];?>">
    <a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=view&catid=".$zoom->_gallery->_id."&key=".$i."&hit=1");?>">
    <?php
  }else{ ?>
    <td align="left" width="<?php echo $zoom->_CONFIG['size'];?>">
    <a href="#" onClick="window.open('components/com_zoom/view.php?popup=1&catid=<?php echo $zoom->_gallery->_id;?>&key=<?php echo $i;?>&isAdmin=<?php echo $zoom->_isAdmin;?>&hit=1', 'win1', 'width=<?php if($size[0]<450){ echo "450";}elseif($size[0]>$zoom->_CONFIG['maxsize']){ echo $zoom->_CONFIG['maxsize'] + 50;}else{ echo $size[0] + 40;} ?>, height=<?php if($size[1]<350){ echo "350";}elseif($size[1]>$zoom->_CONFIG['maxsize']){ echo $zoom->_CONFIG['maxsize'] + 50;}else{ echo $size[1] + 100;} ?>,scrollbars=1').focus()">
    <?php
  }
  echo '<img src="'.$zoom->_gallery->_images[$i]->_thumbnail.'" border="0"></td><td width="10"></td>';
  echo '<td align="left"><b>'.$zoom->_gallery->_images[$i]->_filename.'</b><br />';
  if ($zoom->_CONFIG['showHits'])
    echo 'hits = '.$zoom->_gallery->_images[$i]->_hits.'<br />';
  if($zoom->_CONFIG['ratingOn']){
    if($zoom->_gallery->_images[$i]->_votenum!=0){
      if($zoom->_gallery->_images[$i]->_votesum!=0){
        $rating = round($zoom->_gallery->_images[$i]->_votesum / $zoom->_gallery->_images[$i]->_votenum);
      }else{
        $rating = 0;
      } ?>
      <img src="components/com_zoom/images/rating/rating<?php echo $rating;?>.gif" border="0"> (<?php echo $zoom->_gallery->_images[$i]->_votenum;?>
      <?php
      if($zoom->_gallery->_images[$i]->_votenum==1)
        echo _ZOOM_VOTE.")<br />";
      else
        echo _ZOOM_VOTES.")<br />";
      }else{
        echo _ZOOM_NOTRATED."<br />";
      }
    }
    echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&Itemid=".$Itemid."&catid=".$zoom->_gallery->_id)."\">".$zoom->_gallery->getCatVirtPath()."</a></td></tr>";
    if($tabcnt >= 1){
      $tabcnt = 0;
    }else{ $tabcnt++; }
  } ?>
</table> 
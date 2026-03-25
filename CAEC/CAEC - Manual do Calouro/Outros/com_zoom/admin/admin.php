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
| Filename: admin.php                                                 |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$action = mosGetParam($_REQUEST,'action');
if (isset($action)){
    $zoom->optimizeTables();
    ?>
    <script language="javascript" type="text/javascript">
    	<!--
        alert("<?php echo html_entity_decode(_ZOOM_OPTIMIZE_SUCCESS);?>");
        location = '<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=admin";?>';
        //-->
    </script>
    <?php
}
?>
<table width="90%" "border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top">    
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
            <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td align="left" valign="middle"><strong><?php echo ($zoom->_isAdmin) ? _ZOOM_ADMIN_TITLE : _ZOOM_USER_TITLE; ?></strong></td>
                  <td align="right">ver <?php echo $zoom->_CONFIG['version'];?></td>
                </tr>
            </table>
            </td>
        </tr>
        <tr>
            <td background="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/zoom_logo_faded.gif" style="background-repeat:no-repeat; background-position:top right;">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
              <?php if($zoom->_isAdmin || $zoom->_CONFIG['allowUserEdit']){ ?>
              <tr>
                <td width="60"><a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=catsmgr";?>" onmouseover="return overlib('<?php  echo _ZOOM_CATSMGR;?>');" onmouseout="return nd();"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/catsmgr.png" border="0" onmouseover="MM_swapImage('catsmgr','','<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/catsmgr_f2.png',1);" onmouseout="MM_swapImgRestore();" name="catsmgr"></a></td>
                <td valign="center" align="left">&raquo;&nbsp;<?php echo _ZOOM_CATSMGR_DESCR;?><br />
                </td>
              </tr>
              <?php } ?>
              <?php if($zoom->_isAdmin || $zoom->_CONFIG['allowUserUpload']){ ?>
              <tr>
                <td width="60"><a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=mediamgr";?>" onmouseover="return overlib('<?php  echo _ZOOM_MEDIAMGR;?>');" onmouseout="return nd();"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/mediamgr.png" border="0" onmouseover="MM_swapImage('mediamgr','','<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/mediamgr_f2.png',1);" onmouseout="MM_swapImgRestore();" name="mediamgr"></a></td>
                <td valign="center" align="left">&raquo;&nbsp;<?php echo _ZOOM_MEDIAMGR_DESCR;?><br />
                </td>
              </tr>
              <?php } ?>
              <?php if($zoom->_isAdmin){ ?>
              <tr>
                <td width="60"><a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=settings";?>" onmouseover="return overlib('<?php echo _ZOOM_SETTINGS;?>');" onmouseout="return nd();"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/settings.png" border="0" onmouseover="MM_swapImage('settings','','<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/settings_f2.png',1);" onmouseout="MM_swapImgRestore();" name="settings"></a></td>
                <td valign="center" align="left">&raquo;&nbsp;<?php echo _ZOOM_SETTINGS_DESCR;?><br />
                </td>
              </tr>
              <tr>
                <td width="60"><a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=movefiles";?>" onmouseover="return overlib('<?php echo _ZOOM_MOVEFILES;?>');" onmouseout="return nd();"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/move.png" border="0" onmouseover="MM_swapImage('move','','<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/move_f2.png',1);" onmouseout="MM_swapImgRestore();" name="move"></a></td>
                <td valign="center" align="left">&raquo;&nbsp;<?php echo _ZOOM_MOVEFILES_DESCR;?><br />
                </td>
              </tr>
              <tr>
                <td width="60"><a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=admin&action=optimize";?>" onmouseover="return overlib('<?php  echo _ZOOM_OPTIMIZE;?>');" onmouseout="return nd();"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/tables.png" border="0" onmouseover="MM_swapImage('tables','','<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/tables_f2.png',1);" onmouseout="MM_swapImgRestore();" name="tables"></a></td>
                <td valign="center" align="left">&raquo;&nbsp;<?php echo _ZOOM_OPTIMIZE_DESCR;?>
                </td>
              </tr>
              <tr>
                <td width="60"><a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=update";?>" onmouseover="return overlib('<?php echo _ZOOM_UPDATE;?>');" onmouseout="return nd();"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/update.png" border="0" onmouseover="MM_swapImage('update','','<?php echo $mosConfig_live_site;?>/components/com_zoom/images/admin/update_f2.png',1);" onmouseout="MM_swapImgRestore();" name="update"></a></td>
                <td valign="center" align="left">&raquo;&nbsp;<?php echo _ZOOM_UPDATE_DESCR;?>
                </td>
              </tr>
              <?php } ?>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <?php
                if (!$zoom->_isBackend){
                ?>
                <td align="left">
                  <a href="<?php echo "index.php?option=com_zoom&Itemid=".$Itemid;?>">
                  <img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?php echo _ZOOM_BACKTOGALLERY;?>" border="0">&nbsp;&nbsp;<?php echo _ZOOM_BACKTOGALLERY;?>
                  </a>
                </td>
                <?php
                }
                ?>
                <td align="right">
                  <a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=credits";?>">
                  <img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/credits.gif" alt="<?php echo _ZOOM_CREDITS;?>" border="0">&nbsp;&nbsp;<?php echo _ZOOM_CREDITS;?>
                  </a>
                </td>
              </tr>
            </table>
            </td>
        </tr>
        </table>
    </td>
  </tr>
</table>

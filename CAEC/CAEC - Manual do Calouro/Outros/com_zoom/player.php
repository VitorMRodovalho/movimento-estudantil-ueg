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
| Filename: player.php                                                |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// Load MOS configuration file...
include('../../configuration.php');
echo("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n" 
 . "<html><head><title>zOOm Player</title></head>\n"
 . "<body \"(MB=(ZeroMargins, 0, 0, 0, 0), L=(HomeLayout, 370, 175))\" bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#0033CC\" vlink=\"#990099\" alink=\"#FF0000\" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>\n"
 . "<table border=0 cellspacing=0 cellpadding=0 width=367 LY>\n"
 . "<tr valign=top align=left>\n"
 . "\t<td height=171 width=367>\n"
 . "\t\t<object classid=\"CLSID:D27CDB6E-AE6D-11cf-96B8-444553540000\"\n"
 . "\t\tcodebase=\"http://active.macromedia.com/flash2/cabs/swflash.cab#version=6,0,0,0\" width=\"400\" height=\"175\">\n"
 . "\t\t <param name=\"movie\" VALUE=\"".$mosConfig_live_site."/components/com_zoom/zoomplayer.swf\">\n"
 . "\t\t <param name=\"quality\" value=\"high\">\n"
 . "\t\t <param name=\"scale\" value=\"exactfit\">\n"
 . "\t\t <param name=\"menu\" value=\"true\">\n"
 . "\t\t <param name=\"bgcolor\" value=\"#ffffff\">\n"
 . "\t\t <embed src=\"".$mosConfig_live_site."/components/com_zoom/zoomplayer.swf\" quality=\"high\" scale=\"exactfit\" menu=\"false\" bgcolor=\"#ffffff\" width=\"400\" height=\"175\" swLiveConnect=\"false\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\"></embed>\n"
 . "\t\t</object>\n"
 . "\t</td>\n"
 . "</tr>\n"
 . "</table>\n"
 . "</body>\n"
 . "</html>\n");
?>
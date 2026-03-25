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
| Filename: credits.php                                               |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
?>
<center>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td align="center" width="100%">
			<a href="<?php echo "index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=admin";?>">
			<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN;?>" border="0" />
			&nbsp;&nbsp;<?php echo _ZOOM_MAINSCREEN;?>
			</a>
		&nbsp; | &nbsp;
		</td>
	</tr>
</table>
<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/images/zoom_logo.gif" border="0" alt=""><br /><br />
<br /><br />
<script language="JavaScript1.2" type="text/javascript">
<!--
/*
Fading Scroller- By DynamicDrive.com
For full source code, and usage terms, visit http://www.dynamicdrive.com
This notice MUST stay intact for use
*/

var delay=1200 //set delay between message change (in miliseconds)
var fcontent=new Array()
begintag='' //set opening tag, such as font declarations
fcontent[0]="<h3>zOOm Media Gallery <?php echo $zoom->_CONFIG['version'];?> is written by<br /></h3>Mike de Boer, Rotterdam - The Netherlands"
fcontent[1]="<h3>website:<br /></h3><a href=\"http://www.ummagumma.nl/mikedeboer\">zoom.ummagumma.nl</a><br />or<br /><a href=\"http://www.mikedeboer.nl\">www.mikedeboer.nl</a>"
fcontent[2]="<h3>Icons used by zOOm Media Gallery<br /></h3>are made by FOOOD at <a href=\"http://www.foood.net\">FOOOD.net</a><p>All icons are commercial software and NOT available for public use or (re)distribution!</p>"
fcontent[3]="<h3>Andrea Melzi</h3><br /><br />for the Italian translation and being my first fan!"
fcontent[4]="<h3>Chris Marquette</h3><br /><br />for his help on the gallery-sorting and imagesize problem(s)."
fcontent[5]="<h3>D. Gentile</h3><br /><br />for his useful addition to zOOm 2.1, see his work on <a href=\"http://www.ronin.to\">www.ronin.to</a>"
fcontent[6]="<h3>Max Faber</h3><br /><br />for his input on the development of the user-system"
fcontent[7]="<h3>Chrixo ITA</h3><br /><br />for fixing the navigation issue!"
fcontent[7]="<h3>Per Lasse Baasch (a.k.a. freindler)</h3><br /><br />for the german translation and his work on the zOOm Module!<br />His positive support and feedback helped me a lot. Thanx!"
fcontent[8]="<h3>Rob aka. Xirtam</h3><br /><br />for making the zOOm website possible!<br />He's the webmaster and maintainer of the site!"
fcontent[9]="<h3>David Bates</h3><br /><br />for his offer to co-develop zOOm Media Gallery!<br />He already made some helpful suggestions. Thanx!"
fcontent[9]="<h3>Mic</h3><br /><br />for being the biggest contributor to zOOm to this date!<br />Thank you so much and I hope we keep in touch."
fcontent[10]="<h3>The Translation Team</h3><br /><br />Consisting of: <br />Jimmy Halldin, Helvécio Mafra, setup, Tom HAN, Lars Hørmann, Per Lasse Baasch, Andrea Melzi, Phillippe Lenzi and Federico Almada!"
closetag=''

var fwidth='250px' //set scroller width
var fheight='150px' //set scroller height

var fadescheme=0 //set 0 to fade text color from (white to black), 1 for (black to white)
var fadelinks=0 //should links inside scroller content also fade like text? 0 for no, 1 for yes.

///No need to edit below this line/////////////////

var hex=(fadescheme==0)? 255 : 0
var startcolor=(fadescheme==0)? "rgb(255,255,255)" : "rgb(0,0,0)"
var endcolor=(fadescheme==0)? "rgb(0,0,0)" : "rgb(255,255,255)"

var ie4=document.all&&!document.getElementById
var ns4=document.layers
var DOM2=document.getElementById
var faderdelay=0
var index=0

if (DOM2)
faderdelay=3000

//function to change content
function changecontent(){
if (index>=fcontent.length)
index=0
if (DOM2){
document.getElementById("fscroller").style.color=startcolor
document.getElementById("fscroller").innerHTML=begintag+fcontent[index]+closetag
linksobj=document.getElementById("fscroller").getElementsByTagName("A")
if (fadelinks)
linkcolorchange(linksobj)
colorfade()
}
else if (ie4)
document.all.fscroller.innerHTML=begintag+fcontent[index]+closetag
else if (ns4){
document.fscrollerns.document.fscrollerns_sub.document.write(begintag+fcontent[index]+closetag)
document.fscrollerns.document.fscrollerns_sub.document.close()
}

index++
setTimeout("changecontent()",delay+faderdelay)
}

// colorfade() partially by Marcio Galli for Netscape Communications.  ////////////
// Modified by Dynamicdrive.com

frame=20;

function linkcolorchange(obj){
if (obj.length>0){
for (i=0;i<obj.length;i++)
obj[i].style.color="rgb("+hex+","+hex+","+hex+")"
}
}

function colorfade() {
// 20 frames fading process
if(frame>0) {
hex=(fadescheme==0)? hex-12 : hex+12 // increase or decrease color value depd on fadescheme
document.getElementById("fscroller").style.color="rgb("+hex+","+hex+","+hex+")"; // Set color value.
if (fadelinks)
linkcolorchange(linksobj)
frame--;
setTimeout("colorfade()",20);
}

else{
document.getElementById("fscroller").style.color=endcolor;
frame=20;
hex=(fadescheme==0)? 255 : 0
}
}

if (ie4||DOM2)
document.write('<div id="fscroller" style="border:0px solid black;width:'+fwidth+';height:'+fheight+';padding:2px"></div>')

window.onload=changecontent
</script>

<ilayer id="fscrollerns" width=&{fwidth}; height=&{fheight};>
	<layer id="fscrollerns_sub" width=&{fwidth}; height=&{fheight}; left=0 top=0></layer>
</ilayer>
<br /><br />
</center>
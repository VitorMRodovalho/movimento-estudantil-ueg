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
| Filename: install.zoom.php                                          |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
function com_install(){
	global $mosConfig_live_site, $mosConfig_absolute_path, $mosConfig_lang;
		// get language file
	if (file_exists($mosConfig_absolute_path."/components/com_zoom/language/".$mosConfig_lang.".php")){
		include($mosConfig_absolute_path."/components/com_zoom/language/".$mosConfig_lang.".php");
	}else{
		include($mosConfig_absolute_path."/components/com_zoom/language/english.php");
	}
	//end language
	echo '<p>'._ZOOM_INSTALL_CREATE_DIR;
	chdir("../");
	if(	@mkdir( "images/zoom", 0777 ) &&
	@chmod ("images/zoom", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/audiolist.php", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/safemode.php", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/zoom_config.php", 0777) &&
	// help the updater to work properly in the future...
	@chmod ($mosConfig_absolute_path."/components/com_zoom/images", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/com_zoom/admin", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/com_zoom/classes", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/com_zoom/images", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/com_zoom/images/admin", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/com_zoom/images/filetypes", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/com_zoom/images/rating", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/com_zoom/images/smilies", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/com_zoom/language", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/com_zoom/tabs", 0777) &&
	@chmod ($mosConfig_absolute_path."/components/com_zoom/tabs", 0555)) {
		echo '<font color="green">' . '&nbsp;' . _ZOOM_INSTALL_CREATE_DIR_SUCC . '</font></p>';
		echo '<table border="0" cellspacing="0" cellpadding="0" background="' . $mosConfig_live_site . '/components/com_zoom/images/zoom_logo_faded.gif" style="background-repeat:no-repeat; background-position:top right;" width="75%">';
	 	echo '<tr><td align="center">';
	 	echo '<p>' . _ZOOM_INSTALL_MESS1 . '</p>';
	 	echo '<p><strong>' . _ZOOM_INSTALL_MESS2 . '</strong></p>';
	 	echo '<p>' . _ZOOM_INSTALL_MESS3 . '</p>';
	 	echo '<p>' . _ZOOM_INSTALL_MESS4 . '</p>';
	 	echo '</td></tr></table>';
	}else{
		echo '<font color="red"><strong>' . '&nbsp;' . _ZOOM_INSTALL_CREATE_DIR_FAIL . '</strong></font></p>'
		. '<table border="0" cellspacing="0" cellpadding="0" background="' . $mosConfig_live_site . '/components/com_zoom/images/zoom_logo_faded.gif" style="background-repeat:no-repeat; background-position:top right;" width="75%">';
		echo '<tr><td align="left">';
		echo '<p><strong>' . _ZOOM_INSTALL_MESS_FAIL1 . '</strong></p>';
		echo '<p>' . _ZOOM_INSTALL_MESS_FAIL2 . '</p>';
		echo '<p>' . _ZOOM_INSTALL_MESS_FAIL3 . '</p';
		echo '</td></tr></table>';
	}
}
?>
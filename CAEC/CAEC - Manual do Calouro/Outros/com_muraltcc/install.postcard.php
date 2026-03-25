<?
############################################
# Products Component				       #
# Copyright (C) 2004  by  GLOBODIGITAL.net #
# Homepage   : www.globodigital.net        #
# Version    : 1.0 beta 1                  #
############################################

function com_install() {
	global $database, $mosConfig_absolute_path;
	
	chmod($mosConfig_absolute_path."/components/com_postcard/postcards", 0755);

	$content = '';
	$content .= '<center>';
	$content .= '<table width="100%" border="0">';
	$content .= '  <tr>';
	$content .= '    <td><img src="components/com_postcard/images/logo.png"></td>';
	$content .= '    <td>';
	$content .= '      <strong>Postcard Component</strong><br/>';
	$content .= '      <font class="small">&copy; Copyright 2004 by GLOBODIGITAL.net</font><br/>';
	$content .= '    </td>';
	$content .= '  </tr>';
	$content .= '</table>';
	$content .= '</center>';

	return $content;
}
?>
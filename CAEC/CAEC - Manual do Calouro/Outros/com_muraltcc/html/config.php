<?php
// $Id: admin.banners.html.php,v 1.20 2003/12/26 20:43:49 prazgod Exp $
/**
* Banner admin HTML
* @package Mambo Open Source
* @Copyright (C) 2000 - 2003 Miro International Pty Ltd
* @ All rights reserved
* @ Mambo Open Source is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.20 $
**/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_config {
	function configForm( &$_row, $_option, &$_lists ) { ?>
		<script language="javascript">
		<!--
			function submitbutton(pressbutton) {
				var form = document.adminForm;
<?php			getEditorContents( 'editor1', 'footertext' ); ?>
				submitform( pressbutton );
			}
		//-->
		</script>

		<table cellpadding="4" cellspacing="0" border="0" width="100%">
			<tr>
				<td width="100%"><span class="sectionname">Configuration</span></td>
			</tr>
		</table>
		<form action="index2.php" method="POST" name="adminForm">
		<table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
			<tr>
				<td valign="top">Postcard Footer Text:</td>
				<td align="left"><?php editorArea( 'editor1', str_replace('&','&amp;',$_row->footertext), 'footertext', 400, 200, 70, 15 ); ?></td>
			</tr>
			<tr>
				<td valign="top">View Postal in New Window:</td>
				<td align="left"><?php echo $_lists['view_in_template']; ?></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $_option; ?>">
		<input type="hidden" name="id" value="<?php echo $_row->id; ?>">
		<input type="hidden" name="task" value="">
		</form>
<?php
	}
}
?>
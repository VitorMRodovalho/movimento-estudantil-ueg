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

class HTML_category {
	function showCategory( &$rows, &$pageNav, $option ) {
		global $database; ?>

		<form action="index2.php" method="POST" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
			<tr>
				
		  <td width="100%"><span class="sectionname">Postcard Categories Manager</span></td>
				<td nowrap>Display #</td>
				<td> <?php echo $pageNav->writeLimitBox(); ?> </td>
			</tr>
		</table>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
				<th width="20">#</th>
				<th width="20"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
				<th align="left" nowrap>Name</th>
				<th width="50" align="center" nowrap>Postcards</th>
				<th width="50" colspan="2">Reorder</th>
			</tr>
<?php		$k = 0;
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row = &$rows[$i]; ?>
				<tr class="<?php echo "row$k"; ?>">
					<td width="20" align="center"><?php echo $i+$pageNav->limitstart+1;?></td>
					<td width="20">
						<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onClick="isChecked(this.checked);" />
					</td>
					<td align="left"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','category_edit')"><?php echo $row->cname; ?></a></td>
					<td width="50" align="center">
<?php					$database->setQuery( "SELECT COUNT(*) FROM #__postcard WHERE id_category='".$row->id."'" ); 
						$postcards = $database->loadResult(); 
						echo $postcards; ?>
					</td>
					<td width="25">
<?php				if ($j > 0 || ($j+$pageNav->limitstart > 0)) { ?>
						<a href="#category_orderup" onclick="return listItemTask('cb<?php echo $j;?>','category_orderup')">
							<img src="images/uparrow.png" width="12" height="12" border="0" alt="Move Up">
						</a>
<?php				} else { ?>
						&nbsp;
<?php				} ?>
					</td>
					<td width="25">
<?php				if ($j < $n-1 || $j+$pageNav->limitstart < $pageNav->total-1) { ?>
						<a href="#category_orderdown" onclick="return listItemTask('cb<?php echo $j;?>','category_orderdown')">
							<img src="images/downarrow.png" width="12" height="12" border="0" alt="Move Down">
						</a>
<?php				} else { ?>
						&nbsp;
<?php				} ?>
					</td>
		<?php		$k = 1 - $k;
					$j++; ?>
				</tr>
	<?php	}
	?>
			<tr>
				<th align="center" colspan="7"> <?php echo $pageNav->writePagesLinks(); ?></th>
			</tr>
			<tr>
				<td align="center" colspan="7"> <?php echo $pageNav->writePagesCounter(); ?></td>
			</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="task" value="category">
		<input type="hidden" name="boxchecked" value="0">
		</form>
<?php
	}

	function categoryForm( &$_row, $_option, $lists ) {
		global $mosConfig_live_site; ?>
		<script language="javascript">
		<!--
			function submitbutton(pressbutton) {
				var form = document.adminForm;
<?php			getEditorContents( 'editor1', 'description' ); ?>
				submitform( pressbutton );
			}
		//-->
		</script>

		<table cellpadding="4" cellspacing="0" border="0" width="100%">
			<tr>
				<td width="100%"><span class="sectionname"><?php echo $_row->id ? 'Edit' : 'Add';?> Category</span></td>
			</tr>
		</table>
		<form action="index2.php" method="POST" name="adminForm">
		<table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
			<tr>
				<td width="20%">Name:</td>
				<td width="80%"><input class="inputbox" type="text" name="cname" value="<?php echo $_row->cname;?>" size="50" maxlength="100"></td>
			</tr>
			<tr>
				<td valign="top">Category Description:</td>
				<td align="left"><?php editorArea( 'editor1', str_replace('&','&amp;',$_row->description), 'description', 400, 200, 70, 15 ); ?></td>
			</tr>
			<tr>
				<td width="20%">Style:</td>
				<td width="80%"><?php echo $lists['style']; ?></td>
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
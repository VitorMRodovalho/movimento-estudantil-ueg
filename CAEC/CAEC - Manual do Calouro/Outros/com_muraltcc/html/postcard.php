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

class HTML_postcard {
	function showPostcards( &$rows, &$pageNav, $option, &$_categorylist ) { 
		global $database; ?>
		
		<script>
		function SubmitForm() {
			document.adminForm.submit()
		}
		</script>

		<form action="index2.php" method="POST" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="100%"><span class="sectionname">Postcards</span></td>
			<td nowrap>Category</td>
			<td nowrap>
				<?php echo $_categorylist; ?>
			</td>
			<td nowrap>Display #</td>
			<td> <?php echo $pageNav->writeLimitBox(); ?> </td>
		</tr>
		</table>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
				<th width="20">#</th>
				<th width="20"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
				<th align="left" nowrap>Name</th>
				<th align="left" nowrap>Filename</th>
				<th align="left" nowrap>Category</th>
				<th width="80" align="center" nowrap>Times Sent</th>
			</tr>
	<?php
			$k = 0;
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row = &$rows[$i]; ?>
				<tr class="<?php echo "row$k"; ?>">
					<td width="20" align="center"><?php echo $i+$pageNav->limitstart+1;?></td>
					<td width="20">
						<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onClick="isChecked(this.checked);" />
					</td>
					<td align="left"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','manage_edit')"><?php echo $row->pname; ?></a></td>
					<td align="left"><?php echo $row->filename; ?></td>
					<td align="left">
<?php					$database->setQuery( "SELECT cname FROM #__postcard_category WHERE id='".$row->id_category."'" ); 
						$category = $database->loadResult(); 
						echo $category; ?>
					</td>
					<td width="80" align="center">
<?php					$database->setQuery( "SELECT COUNT(*) FROM #__postcard_send WHERE id_postcard='".$row->id."'" ); 
						$sent = $database->loadResult(); 
						echo $sent; ?>
					</td>
				</tr>
	<?php	}
	?>
			<tr>
				<th align="center" colspan="6"> <?php echo $pageNav->writePagesLinks(); ?></th>
			</tr>
			<tr>
				<td align="center" colspan="6"> <?php echo $pageNav->writePagesCounter(); ?></td>
			</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="task" value="manage">
		<input type="hidden" name="boxchecked" value="0">
		</form>
<?php
	}

	function postcardForm( $option, &$_row, &$lists ) { ?>
		<script>
		function MM_findObj(n, d) { //v4.01
			var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
			d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
			if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
			for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
			if(!x && d.getElementById) x=d.getElementById(n); return x;
		}

		function MM_showHideLayers() { //v6.0
			var i,p,v,obj,args=MM_showHideLayers.arguments;
			for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
				if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
				obj.visibility=v; }
		}

		function ShowProgress() {
			middle_width = screen.width / 2;
			middle_height = screen.height / 2;
			
			var obj = document.getElementById('Layer1');

			obj.style.left = middle_width - 100;
			obj.style.top = middle_height - 250;

			MM_showHideLayers('Layer1','','show');
		}
		</script>
		
		<div id="Layer1" style="position: absolute; margin-left: auto; margin-right: auto; width: 200px; height: 130px; z-index: 1; visibility: hidden; background-color: #CCCCCC; layer-background-color: #FF0000; border: 1px solid #99989D;">
			<table width="100%">
			<tr>
				<td bgcolor="#330099" height="25">
					&nbsp;<font color="#FFFFFF">Upload...</font>
				</td>
			</tr>
			<tr>
				<td align="center" valign="middle">
					<p>File is uploading</p>
					<p><img src="components/com_postcard/images/upload.png"></p>
					<p>Please wait!</p>
				</td>
			</tr>
			</table>
		</div>

		<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="100%" colspan="2">
				<span class="sectionname"><?php echo $_row->id ? 'Edit' : 'Add';?> Postcard</span>
			</td>
		</tr>
		<form name="adminForm" method="post" enctype="multipart/form-data" onSubmit="ShowProgress();">
		<tr>
			<td>Name</td>
			<td><input type="text" size="50" class="inputbox" size="50" name="pname" value="<?php echo $_row->pname; ?>"></td>
		</tr>
		<tr>
			<td>Category</td>
			<td><?php echo $lists['category']; ?></td>
		</tr>
		<tr>
			<td>File</td>
			<td><input type="file" name="imagefile" class="inputbox" size="50"></td>
		</tr>
<?php	if($_row->id > 0) { ?>
			<tr>
				<td>&nbsp;</td>
				<td><img src="../components/com_postcard/postcards/<?php echo $_row->filename; ?>"></td>
				<input type="hidden" name="filename" value="<?php echo $_row->filename; ?>">
			</tr>
<?php	} ?>
		</table>
		<input type="hidden" name="id" value="<?php echo $_row->id; ?>">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="task" value="">
		</form>
<?php
	}
}
?>
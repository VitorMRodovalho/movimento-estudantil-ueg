<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

function ShowSend1( $option, $Itemid, $postcard_info, $id, $filename, $your_name, $your_mail, $to_name, $to_mail, $subject, $postmessage ) { 
	global $mosConfig_lang;

	if(file_exists("components/com_postcard/language/$mosConfig_lang.php")) {
		require("components/com_postcard/language/$mosConfig_lang.php");
	}else{
		require("components/com_postcard/language/english.php");
	} ?>
	
	<table cellpadding="4" cellspacing="1" border="0" width="100%">
	<tr> 
		<td>
			<span class="componentheading"> 
			<?php echo _POSTCARD_TITLE; ?>
			</span> 
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top">&nbsp;</td>
	</tr>
	</table>
	
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="16"></td>
	</tr>
	</table>

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="contentdescription"> 
			<script>
			function ValidateFields() {
				value = document.postcard.your_name.value
				if(value=="") {
					alert("<?php echo _POSTCARD_VALID_1; ?>")
					document.postcard.your_name.focus()
					return false
				}
				value = document.postcard.your_mail.value
				if(value=="") {
					alert("<?php echo _POSTCARD_VALID_2; ?>")
					document.postcard.your_mail.focus()
					return false
				}
				value = document.postcard.to_name.value
				if(value=="") {
					alert("<?php echo _POSTCARD_VALID_3; ?>")
					document.postcard.to_name.focus()
					return false
				}
				value = document.postcard.to_mail.value
				if(value=="") {
					alert("<?php echo _POSTCARD_VALID_4; ?>")
					document.postcard.to_mail.focus()
					return false
				}
				value = document.postcard.subject.value
				if(value=="") {
					alert("<?php echo _POSTCARD_VALID_5; ?>")
					document.postcard.subject.focus()
					return false
				}
				value = document.postcard.postmessage.value
				if(value=="") {
					alert("<?php echo _POSTCARD_VALID_6; ?>")
					document.postcard.postmessage.focus()
					return false
				}
				return true
			}
			</script>
			<form name="postcard" action="index.php" method="post" onSubmit="return ValidateFields();">
			<table width="450" border=0>
				<tr>
					<td valign="top" width="100">
						<?php echo _POSTCARD_YOUR_NAME; ?><br>
						<input type="input" name="your_name" size="20" maxlength="50" class="inputbox" value="<?php echo $your_name; ?>">
					</td>
					<td valign="top" width="100">
						<?php echo _POSTCARD_YOUR_EMAIL; ?><br>
						<input type="input" name="your_mail" size="20" maxlength="50" class="inputbox" value="<?php echo $your_mail; ?>">
					</td>
					<td rowspan="2" valign="top">
						<br><img src="components/com_postcard/images/stamp.png">
					</td>
				</tr>
				<tr>
					<td valign="top">
						<?php echo _POSTCARD_RECIPIENT_NAME; ?><br>
						<input type="input" name="to_name" size="20" maxlength="50" class="inputbox" value="<?php echo $to_name; ?>">
					</td>
					<td valign="top">
						<?php echo _POSTCARD_RECIPIEN_EMAIL; ?><br>
						<input type="input" name="to_mail" size="20" maxlength="50" class="inputbox" value="<?php echo $to_mail; ?>">
					</td>
				</tr>
				<tr>
					<td valign="top" colspan="3">
						<?php echo _POSTCARD_SUBJECT; ?><br>
						<input type="input" name="subject" size="63" maxlength="50" class="inputbox" value="<?php echo $subject; ?>">
					</td>
				</tr>
				<tr>
					<td valign="top" colspan="3">
						<?php echo _POSTCARD_MESSAGE; ?><br>
						<textarea name="postmessage" cols="63" rows="10" class="inputbox"><?php echo $postmessage; ?></textarea>
					</td>
				</tr>
				<tr><td colspan="2" height="20" width="100%"></td></tr>
				<tr>
					<td colspan="3">
						<input type="submit" name="submit" value="<?php echo _POSTCARD_SEND; ?>" class="inputbox">
					</td>
				</tr>
			</table>
			<input type="hidden" name="filename" value="<?php echo $filename; ?>">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
			<input type="hidden" name="task" value="send2">
			<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			</form>
		</td>
	</tr>
	<tr><td height="20" width="100%"></td></tr>
	<tr>
		<td class="contentdescription" width="100%">
			
		</td>
	</tr>
	<tr>
		<td><img src="img/gifTransparent.gif" width="1" height="20"></td>
	</tr>
	<tr>
		<td><?php echo $postcard_info->footertext; ?></td>
	</tr>
	</table> <?php
}


function ShowSend2( $option, $Itemid, $postcard_info, $id, $filename, $your_name, $your_mail, $to_name, $to_mail, $subject, $postmessage ) { 
	global $mosConfig_sitename, $mosConfig_lang;

	if(file_exists("components/com_postcard/language/$mosConfig_lang.php")) {
		require("components/com_postcard/language/$mosConfig_lang.php");
	}else{
		require("components/com_postcard/language/english.php");
	} ?>
	
	<table cellpadding="4" cellspacing="1" border="0" width="100%">
	<tr> 
		<td>
			<span class="componentheading"> 
			<?php echo _POSTCARD_TITLE; ?>
			</span> 
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top">&nbsp;</td>
	</tr>
	</table>

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="16"></td>
	</tr>
	</table>
	
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="35">&nbsp;</td>
		<td><img src="components/com_postcard/postcards/<?php echo $filename; ?>" border="0"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><img src="img/gifTransparent.gif" width="1" height="20"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top" class="contentdescription"> 
			<form name="make_contact" action="index.php" method="post">
			<table width="100%">
				<tr>
					<td valign="top" class="small">
						<?php echo _POSTCARD_PREVIEW_TO; ?> <?php echo $to_name; ?>
					</td>
					<td rowspan="3" valign="top">
						<form action="index.php" method="post">
						<input type="submit" name="submit" value="<?php echo _POSTCARD_BACK; ?>">
						<input type="hidden" name="id" value="<?php echo $id; ?>">
						<input type="hidden" name="filename" value="<?php echo $filename; ?>">
						<input type="hidden" name="option" value="<?php echo $option; ?>">
						<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>">
						<input type="hidden" name="your_name" value="<?php echo $your_name; ?>">
						<input type="hidden" name="your_mail" value="<?php echo $your_mail; ?>">
						<input type="hidden" name="to_name" value="<?php echo $to_name; ?>">
						<input type="hidden" name="to_mail" value="<?php echo $to_mail; ?>">
						<input type="hidden" name="subject" value="<?php echo $subject; ?>">
						<input type="hidden" name="postmessage" value="<?php echo $postmessage; ?>">
						<input type="hidden" name="task" value="send1">
						</form>
						<form action="index.php" method="post">
						<input type="submit" name="submit" value="<?php echo _POSTCARD_SEND; ?>">
						<input type="hidden" name="id" value="<?php echo $id; ?>">
						<input type="hidden" name="filename" value="<?php echo $filename; ?>">
						<input type="hidden" name="option" value="<?php echo $option; ?>">
						<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>">
						<input type="hidden" name="your_name" value="<?php echo $your_name; ?>">
						<input type="hidden" name="your_mail" value="<?php echo $your_mail; ?>">
						<input type="hidden" name="to_name" value="<?php echo $to_name; ?>">
						<input type="hidden" name="to_mail" value="<?php echo $to_mail; ?>">
						<input type="hidden" name="subject" value="<?php echo $subject; ?>">
						<input type="hidden" name="postmessage" value="<?php echo $postmessage; ?>">
						<input type="hidden" name="task" value="send3">
						</form>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<b><?php echo $subject; ?></b>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<?php echo $postmessage; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" class="small">
						<?php echo _POSTCARD_PREVIEW_FROM; ?> <?php echo $your_name; ?>
					</td>
					<td valign="top"></td>
				</tr>
				<tr>
					<td valign="top" colspan="2" height="20"></td>
				</tr>
				<tr>
					<td valign="top">
						<table width="100%">
						<tr>
							<td valign="top" class="small">
								<?php echo $your_mail; ?>
							</td>
							<td valign="top" class="small">
								<?php echo $mosConfig_sitename; ?>
							</td>
						</tr>
						</table>
					</td>
					<td valign="top"></td>
				</tr>
			</table>
			<input type="hidden" name="filename" value="<?php echo $filename; ?>">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
			<input type="hidden" name="task" value="send2">
			<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			</form>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php echo $postcard_info->footertext; ?></td>
	</tr>
	</table> <?php
}


function ShowSend3( $option, $Itemid, $postcard_info, $id, $filename, $your_name, $your_mail, $to_name, $to_mail, $subject, $postmessage ) { 
	global $mosConfig_lang;

	if(file_exists("components/com_postcard/language/$mosConfig_lang.php")) {
		require("components/com_postcard/language/$mosConfig_lang.php");
	}else{
		require("components/com_postcard/language/english.php");
	} ?>
	
	<table cellpadding="4" cellspacing="1" border="0" width="100%">
	<tr> 
		<td>
			<span class="componentheading"> 
			<?php echo _POSTCARD_TITLE; ?>
			</span> 
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top">&nbsp;</td>
	</tr>
	</table>

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="16"></td>
	</tr>
	</table>

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="35">&nbsp;</td>
		<td valign="top" class="contentdescription"> 
			<table width="100%">
				<tr>
					<td valign="top">
						<br>
<?php					$postmessage = _POSTCARD_SEND_MSG; 
						$postmessage = str_replace("{name}", $to_name, $postmessage);
						$postmessage = str_replace("{mail}", $to_mail, $postmessage);
						echo $postmessage;
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php echo $postcard_info->footertext; ?></td>
	</tr>
	</table> <?php
}


function ShowFrontpage( $option, $Itemid, $postcard_info, $rows ) { 
	global $database, $mosConfig_lang;

	if(file_exists("components/com_postcard/language/$mosConfig_lang.php")) {
		require("components/com_postcard/language/$mosConfig_lang.php");
	}else{
		require("components/com_postcard/language/english.php");
	} ?>
	
	<table cellpadding="4" cellspacing="1" border="0" width="100%">
	<tr> 
		<td>
			<span class="componentheading"> 
			<?php echo _POSTCARD_TITLE; ?>
			</span> 
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top">&nbsp;</td>
	</tr>
	</table>

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="img/gifTransparent.gif" width="1" height="16"></td>
	</tr>
	</table>

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="contentdescription"> 
			<table width="100%">
<?php			print '<tr><td>';
				
				if(count($rows) > 0) {
					print '<table width="99%">';
					
					for($i=0, $n=count($rows); $i < $n; $i++ ) {
						$row = &$rows[$i];
						print '<tr>';
						print '<td width="50%" align="left">';
						print '<a href="'.sefRelToAbs("index.php?option=$option&amp;Itemid=$Itemid&amp;task=category&amp;id=".$row->id).'">'.$row->cname.'</a><br/>'.$row->description.'<br/><br/>';
						print '</td>';
						print '</tr>';
					}
					
					print '</table>';
					print '</td></tr>';
				} ?>
			</table>
		</td>
	</tr>
	</table> <?php
}


function ShowFrontpageCategory( $option, $Itemid, $rows, $category_info ) { 
	global $database, $mosConfig_lang;

	if(file_exists("components/com_postcard/language/$mosConfig_lang.php")) {
		require("components/com_postcard/language/$mosConfig_lang.php");
	}else{
		require("components/com_postcard/language/english.php");
	} ?>
	
	<table cellpadding="4" cellspacing="1" border="0" width="100%">
	<tr> 
		<td>
			<span class="componentheading"> 
<?php		if($category_info->style=="1") {	
				echo _POSTCARD_TITLE . " - " . $category_info->cname; 
			} else { 
				echo _POSTCARD_TITLE; 
			} ?>
			</span> 
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top">&nbsp;</td>
	</tr>
	</table>

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<?php
	if($category_info->style==0) { ?>
		<tr>
			<td width="35">&nbsp;</td>
			<td><?php echo $category_info->description; ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td width="1" height="20"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<table width="615"  border="0" cellspacing="0" cellpadding="0">
<?php				print '<tr><td>';
					
					if(count($rows) > 0) {
						$z = 1;
						print '<table width="99%">';
						for($i=0, $n=count($rows); $i < $n; $i++ ) {
							$row = &$rows[$i];
							
							if($z==1) {
								print '<tr>';
							}
							
							print '<td width="50%" align="center">';
							print '<a href="'.sefRelToAbs("index.php?option=$option&amp;Itemid=$Itemid&amp;task=send1&amp;id=".$row->id."&amp;filename=".$row->filename).'"><img src="components/com_postcard/postcards/thumb_'.$row->filename.'" border="0" width="230" height="164"></a><br>'.$row->pname;
							print '</td>';
							
							if($z==2) { //last row item
								print '</tr><td height="20" colspan="'.$z.'"></td></tr>';
							}
							
							$z = $z + 1;
							if($z==3) { $z = 1; } //re-start count
						}
						print '</table>';
					print '</td></tr>';
				} ?>
				</table>
			</td>
		</tr>
<?php
	}else{ ?>
		<tr>
			<td>&nbsp;</td>
			<td>
				<table width="615"  border="0" cellspacing="0" cellpadding="0">
<?php				print '<tr><td>';
					
					if(count($rows) > 0) {
						$z = 1;
						print '<table width="99%" cellspacing="0">';
						for($i=0, $n=count($rows); $i < $n; $i++ ) {
							$row = &$rows[$i];
							
							if($z==1) {
								print '<tr>';
							}
							
							print '<td width="33%" align="center">';
							print '<a href="'.sefRelToAbs("index.php?option=$option&amp;Itemid=$Itemid&amp;task=send1&amp;id=".$row->id."&amp;filename=".$row->filename).'"><img src="components/com_postcard/postcards/thumb_'.$row->filename.'" border="0" width="204" height="140"></a><br>'.$row->pname;
							print '</td>';
							
							if($z==3) { //last row item
								print '</tr>';
							}
							
							$z = $z + 1;
							if($z==4) { $z = 1; } //re-start count
						}
						print '</table>';
					print '</td></tr>';
				} ?>
				</table>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td width="1" height="20"></td>
		</tr>
		<tr>
			<td width="35">&nbsp;</td>
			<td><?php echo $category_info->description; ?></td>
		</tr>
<?php
	} ?>
	</table> <?php
}


function ShowPostal( $option, $Itemid, $row, $filename ) { 
	global $mosConfig_sitename, $mosConfig_lang;

	require("components/com_postcard/language/$mosConfig_lang.php"); ?>
	
	<table cellpadding="4" cellspacing="1" border="0" width="100%">
	<tr> 
		<td>
			<span class="componentheading"> 
			<?php echo _POSTCARD_VIEW; ?>
			</span> 
		</td>
	</tr>
	<tr>
		<td width="100%" height="25"></td>
	</tr>
	<tr>
		<td width="100%"><img src="components/com_postcard/postcards/<?php echo $filename; ?>" border="0"></td>
	</tr>
	<tr>
		<td valign="top" class="contentdescription"> 
			<table width="100%">
				<tr>
					<td valign="top" class="small">
						<?php echo _POSTCARD_PREVIEW_TO; ?> <?php echo $row->to_name; ?>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<b><?php echo $row->subject; ?></b>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<?php echo $row->message; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" class="small">
						<?php echo _POSTCARD_PREVIEW_FROM; ?> <?php echo $row->from_name; ?>
					</td>
					<td valign="top"></td>
				</tr>
				<tr>
					<td valign="top" colspan="2" height="20"></td>
				</tr>
				<tr>
					<td valign="top">
						<table width="100%">
						<tr>
							<td valign="top" class="small">
								<?php echo $row->to_mail; ?>
							</td>
							<td valign="top" class="small">
								<?php echo $mosConfig_sitename; ?>
							</td>
						</tr>
						</table>
					</td>
					<td valign="top"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="100%">
			<?php echo _POSTCARD_VIEW_FOOTER; ?>
		</td>
	</tr>
	</table> <?php
}
?>
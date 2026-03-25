<?php
//online_estagio Admin//


defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_online_estagio_admin {
   function listTemplates( $rows ) {
	   global $option, $my, $cfgjl;
?>
   <form action="index2.php" method="post" name="adminForm">
   <table cellpadding="4" cellspacing="0" border="0" width="100%">
   <tr>
      <td width="100%" class="sectionname"><?php echo _JL_A_LISTTEMPLATES . " : " . $cfgjl['template']; ?></td>
   </tr>
   </table>

   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr>
         <th width="20">
            
         </th>
         <th class="title" align="left"><?php echo _JL_A_TEMPLATE_FILE; ?></th>
      </tr>
      <?php
      $k = 0;
      $i = 0;
      for ($i=0, $n=count( $rows ); $i < $n; $i++) {
		 ?>
            <tr class="row<?php echo $k; ?>">
              <td width="20">
                <input type="radio" id="cb<?php echo $i;?>" name="pid[]" value="<?php echo $rows[$i]; ?>" onClick="isChecked(this.checked);" />
              </td>
              <td><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','edittemplate')"><?php echo $rows[$i]; ?></a></td>
            </tr>
            <?php
               $k = 1 - $k;
         }?>
      </tr>
      </table>
         <input type="hidden" name="option" value="<?php echo $option; ?>">
         <input type="hidden" name="task" value="">
         <input type="hidden" name="boxchecked" value="0">
   </form>


<?php
   }

   function editTemplate( $template, $template_content, $template_path ) {
	   global $option, $my, $mosConfig_editor, $cfgjl;
	   global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang;

?>
<script language="javascript" src="js/dhtml.js"></script>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;

  	        if (pressbutton == 'canceltemplate') {
			    submitform( pressbutton );
			    return;
		    }
		    submitform( pressbutton );

	}
</script>
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
  <tr>
    <td class="sectionname"><?php echo _JL_A_EDITING_TEMPLATE . " : {$cfgjl['template']} / $template"; ?></td>
  </tr>
</table>
<form action="index2.php" method="POST" name="adminForm" enctype="multipart/form-data">

<table cellspacing="0" cellpadding="4" border="0" width="100%" class="adminform">
		<tr>
          <th colspan="4"><?php echo _JL_A_PATH . " components/com_online_estagio/templates/{$cfgjl['template']}/$template.tmpl"; ?>
      		<b><?php echo is_writable( $template_path ) ? '<b><font color="green">
			 - ' . _JL_A_WRITEABLE . '</font></b>' : '<b><font color="red"> - ' . _JL_A_UNWRITEABLE . '</font></b>' ?></th>
		</tr>
  <tr>
    <td valign="top">
      <textarea class="inputbox" name="content" id="content" cols="110" rows="25"><?php echo $template_content; ?></textarea>
    </td>
  </tr>
</table>

<input type="hidden" name="template" value="<?php echo $template; ?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>">
<input type="hidden" name="task" value="">
</form>
<?php
   }

   function listJobPostings( $rows, $pageNav ) {
	   global $option, $my;
?>
   <form action="index2.php" method="post" name="adminForm">
   <table cellpadding="4" cellspacing="0" border="0" width="100%">
   <tr>
      <td width="100%" class="sectionname"><?php echo _JL_A_LISTJOBS; ?></td>
      <td nowrap><?php echo _JL_A_DISPLAY; ?></td>
      <td>
         <?php echo $pageNav->writeLimitBox(); ?>
      </td>
   </tr>
   </table>

   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr>
         <th width="20">#</th>
         <th width="20">
            <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
         </th>
         <th class="title"><?php echo _JL_A_JOBID; ?></th>
         <th align="left"><?php echo _JL_A_TITLE; ?></th>
         <th><?php echo _JL_A_JOB_TYPE; ?></th>
         <th><?php echo _JL_A_DATE_ADDED; ?></th>
         <th><?php echo _JL_A_COMPANY_NAME; ?></th>
         <th><?php echo _JL_A_PUBLISHED; ?></th>
         <th><?php echo _JL_A_CHECKEDOUT; ?></th>
<!--         <th colspan="2"><?php echo _JL_A_REORDER; ?></th> -->
      </tr>
      <?php
      $k = 0;
      $i = 0;
      for ($i=0, $n=count( $rows ); $i < $n; $i++) {
         $row = $rows[$i];
		 ?>
            <tr class="row<?php echo $k; ?>">
            <td width="20" align="right"><?php echo $i+$pageNav->limitstart+1;?></td>
            <td width="20">
        <?php		if ($row->checked_out && $row->checked_out != $my->id) { ?>
        &nbsp;
        <?php		} else { ?>
        <input type="checkbox" id="cb<?php echo $i;?>" name="pid[]" value="<?php echo $row->id; ?>" onClick="isChecked(this.checked);" />
        <?php		} ?>
            </td>
            <td align="right"><?php echo $row->id; ?>&nbsp;</td>
            <td width="40%"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','edit')"><?php echo $row->title; ?></a></td>
            <td width="10%" align="center"><?php echo getJobTypeString($row->jobtype); ?></a></td>
            <td width="10%" align="center"><?php echo mosFormatDate( $row->created, "%m/%d/%Y" ); ?></td>
            <td width="20%" align="center"><?php echo $row->company; ?></td>
<?php
         $task = $row->state ? 'unpublish' : 'publish';
         $img = $row->state ? 'publish_g.png' : 'publish_x.png';
?>
            <td width="5%" align="center"><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a></td>
               <td width="15%" align="center"><?php echo $row->editor;?>&nbsp;</td>
<!--
      <td>
<?php    if ($i > 0 || ($i+$pageNav->limitstart > 0)) { ?>
         <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderup')">
            <img src="images/uparrow.png" width="12" height="12" border="0" alt="<?php echo _JL_A_MOVEUP; ?>">
         </a>
<?php    } ?>
      </td>
      <td>
<?php    if ($i < $n-1 || $i+$pageNav->limitstart < $pageNav->total-1) { ?>
         <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderdown')">
            <img src="images/downarrow.png" width="12" height="12" border="0" alt="<?php echo _JL_A_MOVEDOWN; ?>">
         </a>
<?php    } ?>
      </td>
-->
            </tr>
            <?php
               $k = 1 - $k;
         }?>
      </tr>
      <tr>
         <th align="center" colspan="12">
            <?php echo $pageNav->writePagesLinks(); ?></th>
      </tr>
      <tr>
         <td align="center" colspan="12">
            <?php echo $pageNav->writePagesCounter(); ?></td>
      </tr>
      </table>
         <input type="hidden" name="option" value="<?php echo $option; ?>">
         <input type="hidden" name="task" value="">
         <input type="hidden" name="boxchecked" value="0">
   </form>
<?php
   }

   function listJobQueue( $rows, $pageNav ) {
	   global $option, $my;
?>
   <form action="index2.php" method="post" name="adminForm">
   <table cellpadding="4" cellspacing="0" border="0" width="100%">
   <tr>
      <td width="100%" class="sectionname"><?php echo _JL_A_LISTJOBQUEUE; ?></td>
      <td nowrap><?php echo _JL_A_DISPLAY; ?></td>
      <td>
         <?php echo $pageNav->writeLimitBox(); ?>
      </td>
   </tr>
   </table>

   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr>
         <th width="20">#</th>
         <th width="20">
            <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
         </th>
         <th class="title"><?php echo _JL_A_JOBID; ?></th>
         <th align="left"><?php echo _JL_A_TITLE; ?></th>
         <th><?php echo _JL_A_JOB_TYPE; ?></th>
         <th><?php echo _JL_A_DATE_ADDED; ?></th>
         <th colspan="2"><?php echo _JL_A_FUNCTIONS; ?></th>
      </tr>
      <?php
      $k = 0;
      $i = 0;
      for ($i=0, $n=count( $rows ); $i < $n; $i++) {
         $row = $rows[$i];
		 ?>
            <tr class="row<?php echo $k; ?>">
            <td width="20" align="right"><?php echo $i+$pageNav->limitstart+1;?></td>
            <td width="20">
        <?php		if ($row->checked_out && $row->checked_out != $my->id) { ?>
        &nbsp;
        <?php		} else { ?>
        <input type="checkbox" id="cb<?php echo $i;?>" name="pid[]" value="<?php echo $row->id; ?>" onClick="isChecked(this.checked);" />
        <?php		} ?>
            </td>
            <td align="right"><?php echo $row->id; ?>&nbsp;</td>
            <td width="60%"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','editqueue')"><?php echo $row->title; ?></a></td>
            <td align="center"><?php echo getJobTypeString($row->jobtype); ?></a></td>
            <td align="center"><?php echo $row->created; ?></td>
            <td align="center"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','postjob')"><?php echo _JL_A_POSTJOB; ?></a></td>
            <td align="center"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','rejectjob')"><?php echo _JL_A_REJECTJOB; ?></a></td>
            <?php
               $k = 1 - $k;
         }?>
      </tr>
      <tr>
         <th align="center" colspan="12">
            <?php echo $pageNav->writePagesLinks(); ?></th>
      </tr>
      <tr>
         <td align="center" colspan="12">
            <?php echo $pageNav->writePagesCounter(); ?></td>
      </tr>
      </table>
         <input type="hidden" name="option" value="<?php echo $option; ?>">
         <input type="hidden" name="task" value="">
         <input type="hidden" name="boxchecked" value="0">
   </form>
<?php
   }

   function editJobPosting( $row, $lists, $cur_template, $returnpage="" ) {
	   global $option, $my, $mosConfig_editor, $cfgjl;
	   global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang;
	   mosMakeHtmlSafe( $row );
	   $keysets = getKeywordSets();
	   $extrafields = getExtraFields();
?>
<link rel="stylesheet" type="text/css" media="all" href="../includes/js/calendar/calendar-mos.css" title="green" />
<script language="javascript" src="js/dhtml.js"></script>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;

<?php foreach ( $keysets as $set => $keywords ) { ?>
            // Collect extra keyword information
			var temp = new Array;
			var c = 0;
			obj = document.adminForm.cb_<?php echo cleanString( $set ); ?>;
			for (var i=0; i < <?php echo count( $keywords ); ?>; i++) {
				if ( obj[i].checked ) {
					temp[c] = obj[i].value;
					c++;
				}
			}
			document.adminForm.<?php echo cleanString( $set ); ?>.value = temp.join( '; ' );
<?php } ?>

<?php
// Where editor1 = your areaname and content = the field name
getEditorContents( 'editor1', 'description') ;
?>

			if (pressbutton == 'previewjobposting') {
				window.open('components/<?php echo $option; ?>/previewjobposting.php?t=<?php echo $cur_template; ?>', 'win1', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');
			}
  	        if (pressbutton == 'cancel') {
			    submitform( pressbutton );
			    return;
		    }
		    // do field validation
		    if (form.title.value == ""){
			    alert( "<?php echo _JL_A_NOTITLE; ?>" );
		    } else {
			     submitform( pressbutton );
		    }

	}
</script>
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
  <tr>
    <td class="sectionname"><?php echo ($row->id ? _JL_A_EDITING: _JL_A_ADDING) . " " . _JL_A_JOBPOSTING; ?></td>
  </tr>
</table>
<form action="index2.php" method="POST" name="adminForm" enctype="multipart/form-data">
  <input type="hidden" name="returnpage" value="<?php echo $returnpage; ?>" />

<table cellspacing="0" cellpadding="4" border="0" width="100%">
  <tr>
    <td width="50%" valign="top">

      <table width="100%" class="adminform">
        <tr>
          <th colspan="2">
 	        <?php echo _JL_A_ITEM_DETAILS; ?>
          </th>
        </tr>
        <tr>
          <td width="10%" align="right"><?php echo _JL_A_ENTER_TITLE; ?></td>
          <td width="90%"><input class="inputbox" type="text" name="title" size="40" maxlength="255" value="<?php echo $row->title; ?>" />
          </td>
        </tr>
        <tr>
          <td valign="top" align="right"><?php echo _JL_A_ENTER_JOBREFERENCE; ?></td>
          <td><input class="inputbox" type="text" name="reference" size="40" maxlength="255" value="<?php echo $row->reference; ?>" /></td>
        </tr>

        <tr>
          <td colspan="2" valign="top">
            <?php echo _JL_A_ENTER_DESCRIPTION; ?><br />
<?php
   // parameters : areaname, content, hidden field, width, height, rows, cols
   editorArea( 'editor1',  "$row->description", 'description', '400', '300', '60', '30' );
?>
          </td>
        </tr>
      </table>

    </td>
    <td valign="top">

<table cellspacing="0" cellpadding="4" border="0" width="100%">
  <tr>
    <td width="" class="tabpadding">&nbsp;</td>
    <td id="tab1" class="offtab" onClick="dhtml.cycleTab(this.id)"><?php echo _JL_A_JOBINFORMATION; ?></td>
    <td id="tab2" class="offtab" onClick="dhtml.cycleTab(this.id)"><?php echo _JL_A_EXTRAINFO; ?></td>
    <td id="tab3" class="offtab" onClick="dhtml.cycleTab(this.id)"><?php echo _JL_A_COMPANY_CONTACT_INFO; ?></td>
    <td id="tab4" class="offtab" onClick="dhtml.cycleTab(this.id)"><?php echo _JL_A_PUBLISHING; ?></td>
    <td width="90%" class="tabpadding">&nbsp;</td>
  </tr>
</table>

  <div id="page1" class="pagetext">
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
          <tr>
        <td width="15%" valign="top" align="right"><?php echo _JL_A_ENTER_JOBTYPE; ?></td>
        <td width="85%"><?php echo $lists['jobtype']; ?></td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo _JL_A_ENTER_JOBLOCATION; ?></td>
        <td><input class="inputbox" type="text" name="location" size="40" maxlength="255" value="<?php echo $row->location; ?>" /><br />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo _JL_A_ENTER_QUALIFICATIONS; ?></td>
        <td><textarea class="inputbox" name="qualifications" id="qualifications" cols="40" rows="5"><?php echo $row->qualifications; ?></textarea></td>
      </tr>
      <tr>
        <td width="10%" align="right"><?php echo _JL_A_ENTER_COMPENSATION; ?></td>
        <td><input class="inputbox" type="text" name="compensation" size="40" maxlength="100" value="<?php echo $row->compensation; ?>" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo _JL_A_SHOW_COMPENSATION; ?></td>
        <td><input type='checkbox' name='showcomp' value='1' <?php echo ($row->showcomp == "1") ? "checked='checked'" : ""; ?> /></td>
      </tr>
    </table>
  </div>

  <div id="page2" class="pagetext"> 
    <table cellpadding="2" cellspacing="4" border="0" width="100%" class="adminform">
<?php $fc = 0; ?>
<?php foreach ( $extrafields as $field ) { ?>
 <?php if ( trim( $field ) ) { ?>
  <?php $fc++ ?>
      <tr>
        <td width="15%" valign="top" align="right"><?php echo $field; ?></td>
        <td width="85%"><input class="inputbox" type="text" name="<?php echo cleanString($field); ?>" size="40" maxlength="255" value="<?php echo isset($row->attribs[cleanString($field)]) ? $row->attribs[cleanString($field)] : ""; ?>" /></td>
      </tr>
 <?php } ?>
<?php } ?>

<?php foreach ( $keysets as $set => $keywords ) { ?>
 <?php if ( trim( $set ) ) { ?>
  <?php $fc++ ?>
      <tr>
        <td width="15%" valign="top" align="right"><?php echo $set; ?></td>
        <td width="85%" valign="top">
          <input type="hidden" name="<?php echo cleanString($set); ?>" value="" />
  	      <?php foreach ( $keywords as $kw ) { ?>
		   <input type="checkbox" name="cb_<?php echo cleanString($set); ?>" value="<?php echo $kw; ?>" <?php echo in_array( $kw, $row->keywords[cleanString($set)] ) ? 'checked="checked"' : ''; ?>/> <?php echo $kw; ?><br />
          <?php } ?>
        </td>
      </tr>
 <?php } ?>
<?php } ?>

<?php if ( $fc == 0 ) { ?>
      <tr>
        <td><?php echo _JL_A_NOEXTRAFIELDS; ?></td>
      </tr>
<?php } ?>

    </table>
  </div>

  <div id="page3" class="pagetext"> 
    <table cellpadding="2" cellspacing="4" border="0" width="100%" class="adminform">
      <tr>
        <td width="15%" align="right"><?php echo _JL_A_ENTER_COMPANYNAME; ?></td>
        <td width="85%"><input class="inputbox" type="text" name="company" size="40" maxlength="100" value="<?php echo $row->company; ?>" />
        </td>
      </tr>
      <tr>
        <td align="right"><?php echo _JL_A_ENTER_ADDRESS1; ?></td>
        <td><input class="inputbox" type="text" name="address1" size="40" maxlength="100" value="<?php echo $row->address1; ?>" />
        </td>
      </tr>
      <tr>
        <td align="right"><?php echo _JL_A_ENTER_ADDRESS2; ?></td>
        <td><input class="inputbox" type="text" name="address2" size="40" maxlength="100" value="<?php echo $row->address2; ?>" />
        </td>
      </tr>
      <tr>
        <td align="right"><?php echo _JL_A_ENTER_CITY; ?></td>
        <td><input class="inputbox" type="text" name="city" size="40" maxlength="100" value="<?php echo $row->city; ?>" />
        </td>
      </tr>
      <tr>
        <td align="right"><?php echo _JL_A_ENTER_COMPANYURL; ?></td>
	    <td><input class="inputbox" type="text" name="companyurl" size="40" maxlength="100" value="<?php echo trim( $row->companyurl ) ? $row->companyurl : "http://"; ?>" />
        </td>
      </tr>
      <tr>
        <td align="right"><?php echo _JL_A_ENTER_CONTACTNAME; ?></td>
        <td><input class="inputbox" type="text" name="contactname" size="40" maxlength="100" value="<?php echo $row->contactname; ?>" />
        </td>
      </tr>
      <tr>
        <td align="right"><?php echo _JL_A_ENTER_CONTACTPHONE; ?></td>
        <td><input class="inputbox" type="text" name="contactphone" size="40" maxlength="100" value="<?php echo $row->contactphone; ?>" />
        </td>
      </tr>
      <tr>
        <td align="right"><?php echo _JL_A_ENTER_CONTACTEMAIL; ?></td>
        <td><input class="inputbox" type="text" name="contactemail" size="40" maxlength="100" value="<?php echo $row->contactemail; ?>" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo _JL_A_SHOWCONTACT; ?></td>
        <td><input type='checkbox' name='showcontact' value='1' <?php echo ($row->showcontact == "1") ? "checked='checked'" : ""; ?> /></td>
      </tr>

    </table>
  </div>

  <div id="page4" class="pagetext"> 
    <table cellpadding="2" cellspacing="4" border="0" width="100%" class="adminform">
      <tr>
        <td width="15%" valign="top" align="right"><?php echo _JL_A_ACCESS; ?></td>
        <td width="85%"><?php echo $lists['access']; ?></td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo _JL_A_PUBLISHITEM; ?></td>
        <td><?php echo $lists['published']; ?></td>
      </tr>
    </table>
  </div>

    </td>
  </tr>
</table>

  <input type="hidden" name="created" value="<?php echo $row->created;?>" />
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
  <input type="hidden" name="version" value="<?php echo $row->version; ?>" />
  <input type="hidden" name="task" value="" />
</form>

<script language="javascript" type="text/javascript">
		dhtml.cycleTab('tab1');
</script>
<?php
	}

	function showAdminPages( $pages, $title ) {
		global $option;
		?>
   <table cellpadding="4" cellspacing="0" border="0" width="100%">
      <tr>
         <td><!-- <img src="../components/<?php echo $option; ?>/gtl_small.gif" align="middle" alt="<?php echo $title; ?>" /> --></td>
         <td width="100%" class="sectionname">&nbsp;&nbsp;&nbsp;<?php echo $title; ?></td>
      </tr>
   </table>
<script language="javascript" src="js/dhtml.js"></script>
<table cellpadding="3" cellspacing="0" border="0" width="100%">
  <tr> 
    <td width="" class="tabpadding">&nbsp;</td>
<?php
	for ( $i = 1; $i <= count($pages); $i++ ) {
		?>
    <td id="tab<?php echo $i; ?>" class="offtab" onClick="dhtml.cycleTab(this.id)"><?php echo $pages[$i]['title']; ?></td>
<?php
	} /* end for */
?>
    <td width="90%" class="tabpadding">&nbsp;</td>
  </tr>
</table>

<?php
	for ( $i = 1; $i <= count($pages); $i++ ) {
		?>
  <div id="page<?php echo $i; ?>" class="pagetext"> 
    <table cellpadding="2" cellspacing="4" border="0" width="100%" class="adminform">
      <tr> 
		<td><?php echo $pages[$i]['content']; ?></td>
      </tr>
  </table>
  </div>
<?php
	} /* end for */

?>
<script language="javascript" type="text/javascript">
	dhtml.cycleTab('tab1');
</script>

<?php
	}

	function showConfig( $cfgjl, $cfg, $lists ) {
		global $option;

?>
   <table cellpadding="4" cellspacing="0" border="0" width="100%">
      <tr>
         <td><!-- <img src="../components/<?php echo $option; ?>/mamblog_small.gif" alt="<?php echo _JL_A_CONFIGURATION; ?>" title="<?php echo _JL_A_CONFIGURATION; ?>" /> --></td>
         <td width="100%" class="sectionname">&nbsp;&nbsp;&nbsp;<?php echo _JL_A_CONFIGURATION; ?></td>
      </tr>
   </table>
<script language="javascript" src="js/dhtml.js"></script>
<table cellpadding="3" cellspacing="0" border="0" width="100%">
  <tr> 
    <td width="" class="tabpadding">&nbsp;</td>
    <td id="tab1" class="offtab" onClick="dhtml.cycleTab(this.id)"><?php echo _JL_A_SETTINGS; ?></td>
    <td width="90%" class="tabpadding">&nbsp;</td>
  </tr>
</table>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
			if (pressbutton == 'saveconf') {
				if (confirm ("<?php echo _JL_A_AREYOUSURE; ?>")) {
					submitform( pressbutton );
				}
			} else {
				document.location.href = 'index2.php';
			}
	}
</script>
<form action="index2.php" method="POST" name="adminForm">

  <div id="page1" class="pagetext"> 
    <table cellpadding="2" cellspacing="4" border="0" width="100%" class="adminform">
      <tr align="center" valign="middle">
         <td align="left" valign="top" width="375"><?php echo _JL_A_MAILFROMNAME; ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_mailfromname" value="<?php echo $cfgjl['mailfromname']; ?>" class="inputbox" size="50" maxlength="255" /></td>
      </TR>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_MAILFROMADDRESS; ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_mailfromaddress" value="<?php echo $cfgjl['mailfromaddress']; ?>" class="inputbox" size="50" maxlength="255" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_ITEMLIMIT; ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_item_limit" value="<?php echo $cfgjl['item_limit']; ?>" class="inputbox" size="5" maxlength="5" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_SORTORDER; ?></td>
         <td align="left" valign="top"><?php echo $lists['sortorder']; ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_PUBLISHINGLIMIT; ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_publishinglimit" value="<?php echo $cfgjl['publishinglimit']; ?>" class="inputbox" size="5" maxlength="5" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_TERMSOFSERVICE; ?></td>
         <td align="left" valign="top"><textarea name="cfg_termsofservice" class="inputbox" cols="50" rows="4"><?php echo $cfgjl['termsofservice']; ?></textarea></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_SELECTTEMPLATE; ?></td>
         <td align="left" valign="top"><?php echo $lists['templates']; ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_EMAILAPPLICATION; ?></td>
         <td align="left" valign="top"><?php echo $lists['emailapp']; ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_POSTJOBS; ?></td>
         <td align="left" valign="top"><?php echo $lists['postjobs']; ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_AUTOAPPROVEJOBS; ?></td>
         <td align="left" valign="top"><?php echo $lists['autoapprove']; ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_ITEMSNEWFOR; ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_newdays" value="<?php echo $cfgjl['newdays']; ?>" class="inputbox" size="5" maxlength="5" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_DATEFORMAT; ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_dateformat" value="<?php echo $cfgjl['dateformat']; ?>" class="inputbox" size="20" maxlength="40" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_SELECTEDITOR; ?></td>
         <td align="left" valign="top"><?php echo $lists['editor']; ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_INITIALIZE_HTML_EDITOR; ?></td>
         <td align="left" valign="top"><?php echo $lists['initeditor']; ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_EDITORWIDTH; ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_editorwidth" value="<?php echo $cfgjl['editorwidth']; ?>" class="inputbox" size="5" maxlength="5" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_EDITORHEIGHT; ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_editorheight" value="<?php echo $cfgjl['editorheight']; ?>" class="inputbox" size="5" maxlength="5" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_EXTRAFIELDS; ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_extrafields" value="<?php echo $cfgjl['extrafields']; ?>" class="inputbox" size="80" maxlength="1000" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_KEYWORDCATS; ?></td>
         <td align="left" valign="top"><textarea name="cfg_keywordsets" class="inputbox" cols="50" rows="10"><?php echo $cfgjl['keywordsets']; ?></textarea></td>
      </tr>
<!--
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _JL_A_STARTSTRING; ?></td>
         <td align="left" valign="top"><textarea name="cfg_startstring" class="inputbox" cols="50" rows="4"><?php echo $cfgjl['startstring']; ?></textarea></td>
      </tr>
-->
    </table>
  </div>

  <input type="hidden" name="task" value="" />
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="cfg_version" value="<?php echo $cfgjl['version']; ?>" />
</form>

<script language="javascript" type="text/javascript">
	dhtml.cycleTab('tab1');
</script>
<?php
   }

}
?>
<?php
// $Id$

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_online_estagio {

	function mxTemplate( $template, $vars ) {
		global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang;
		global $cfgjl;

		

	}

	function listJobPostings( $items, $totalRows, $search="", $pageNav="", $link="", $type="", $sort="createdasc" ) {
		global $option, $my, $mosConfig_editor, $cfgjl, $Itemid;
		global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang;
		
		if ( $type == "internships" ) {
			$heading_jobid = _JL_INTERNSHIPID;
			$heading_jobtitle = _JL_INTERNSHIP_TITLE;
			$gotosearch = _JL_GOTOSEARCH_INTERN;
			$postjob = _JL_POSTINTERNSHIP;
		} else {
			$heading_jobid = _JL_JOBID;
			$heading_jobtitle = _JL_JOB_TITLE;
			$gotosearch = _JL_GOTOSEARCH;
			$postjob = _JL_POSTJOB;
		}
		if ( $search ) {
			$title = _JL_SEARCH_RESULTS;
			$description = _JL_SEARCH_DESCRIPTION . $totalRows;
			$linkargs = "task=results&search=$search";
		} else {
			$linkargs = "task=list";
			if ( $type == "internships" ) {
				$title = _JL_LIST_INTERNSHIPS;
				$description = _JL_LIST_INTERNSHIPS_DESC;
			} else {
				$title = _JL_LIST_JOBPOSTINGS;
				$description = _JL_LIST_DESCRIPTION;
			}
			
		}
		$searchlinkempty = "<a class='jl_functionlink' href='" . sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=search") . "'>" . $gotosearch . "</a>";
		$postjoblink = "";
		if ( $cfgjl['postjobs'] <= $my->gid ) {
			$postjoblink = sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=add");
		}

		$searchlink = "";
		$searchlink2 = "";
		if ( trim( $search ) ) {
			$searchlink = '<a class="jl_functionlink" href="' . sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=search&search=$search") . '">' . _JL_BACKTOSEARCH . '</a>';
			$searchlink2 = '  <tr>
    <td valign="top" class="contentdescription">
      <p><a class="jl_functionlink" href="' . sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=search&search=$search") . '">' . _JL_BACKTOSEARCH . '</a></p>
    </td>
  </tr>
';
		}

		$pagenavlimitbox = "";
		$pagenavpageslinks = "";
		$pagenavpagescounter = "";
		if ( $items ) {
			if ($pageNav) {
				ob_start();
				$pageNav->writeLimitBox( $link );
				$pagenavlimitbox = ob_get_contents();
				ob_end_clean();
				#$pagenavlimitbox = obeval( "$pageNav->writeLimitBox( $link );" );
				$pagenavpageslinks = $pageNav->writePagesLinks( $link );
				$pagenavpagescounter = $pageNav->writePagesCounter();
			}
			
			$itemlist = "";
			$tabclass = array("sectiontableentry1", "sectiontableentry2");
			$k = 0;
			$itemtmpl = new mxTemplate( "$mosConfig_absolute_path/components/$option/templates/{$cfgjl['template']}" );
			if ( $itemtmpl->setTemplate( 'listitem' ) ) {
				foreach ($items as $row) {
					unset( $itemvars );
					$itemvars = get_object_vars( $row );
					$itemvars['tabclass'] = $tabclass[$k];
					$itemvars['created_formatted'] = mxFormatDate( $row->created, $cfgjl['dateformat'] );
					if( $row->access <= $my->gid ){
						$itemvars['link'] = sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=view&id=$row->id");
					} else {
						$itemvars['link'] = sefRelToAbs("index.php?option=com_registration&amp;task=register");
					}
					// Check if items has been created in the last $cfgjl['newdays'] days.
					if ( strtotime( $row->created ) > ( time() - intval( $cfgjl['newdays'] ) * 24 * 60 * 60 ) ) {
						$itemvars['newitem'] = "1";
					} else {
						$itemvars['newitem'] = "0";
					}
					$itemtmpl->parseTemplate( $itemvars );
					$itemlist .= $itemtmpl->getOutput();
					$k = 1 - $k;
				}
				$templatename = "list";
			} else {
				$templatename = "listempty";
				showError( _JL_ERRORSETTMPL . ": listitem" );
				return;
			}
		} else {
			$templatename = "listempty";
			$itemlist = "";
		}

		$tmplvars = array(
			'title' => $title,
			'description' => $description,
			'heading_jobid' => $heading_jobid,
			'heading_jobtitle' => $heading_jobtitle,
			'gotosearch' => $gotosearch,
			'postjob' => $postjob,
			'searchlinkempty' => $searchlinkempty,
			'searchlink' => $searchlink,
			'searchlink2' => $searchlink2,
			'postjoblink' => $postjoblink,
			'pagenavlimitbox' => $pagenavlimitbox,
			'pagenavpageslinks' => $pagenavpageslinks,
			'pagenavpagescounter' => $pagenavpagescounter,
			'itemlist' => $itemlist,
			'sort_jobtitle' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&$linkargs&sort=" . getSortArg( "title", $sort ) ),
			'sort_ordering' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&$linkargs&sort=" . getSortArg( "ordering", $sort ) ),
			'sort_jobid' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&$linkargs&sort=" . getSortArg( "jobid", $sort ) ),
			'sort_created' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&$linkargs&sort=" . getSortArg( "created", $sort ) ),
			'sort_company' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&$linkargs&sort=" . getSortArg( "company", $sort ) ),
			'sort_reference' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&$linkargs&sort=" . getSortArg( "reference", $sort ) ),
			'sort_location' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&$linkargs&sort=" . getSortArg( "location", $sort ) ),
			'sort_duration' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&$linkargs&sort=" . getSortArg( "duration", $sort ) ),
			'link_list' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=list"),
			'link_search' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=search"),
			);
		foreach ( $cfgjl as $k => $v ) {
			$tmplvars["config_$k"] = $v;
		}

		$tmpl = new mxTemplate( "$mosConfig_absolute_path/components/$option/templates/{$cfgjl['template']}" );
		if ( $tmpl->setTemplate( $templatename ) ) {
			$tmpl->setVars( $tmplvars );
			$tmpl->parseTemplate();
			print $tmpl->getOutput();
		} else {
			showError( _JL_ERRORSETTMPL . ": $templatename" );
		}
	}

	function showThankYou( $msg="", $id="" ) {
		global $option, $my, $mosConfig_editor, $cfgjl, $Itemid;
		global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang;
		if ( !trim( $msg ) ) {
			$msg = _JL_THANKYOU_MESSAGE;
		}

		$tmplvars = array(
			'message' => $msg,
			'id' => $id,
			'link_job' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=view&id=$id"),
			'link_list' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=list"),
			);

		$tmpl = new mxTemplate( "$mosConfig_absolute_path/components/$option/templates/{$cfgjl['template']}" );
		if ( $tmpl->setTemplate( "thankyou" ) ) {
			$tmpl->setVars( $tmplvars );
			$tmpl->parseTemplate();
			print $tmpl->getOutput();
		} else {
			showError( _JL_ERRORSETTMPL . ": thankyou" );
		}

    }

	function showApplicationForm( $row ) {
		global $option, $my, $mosConfig_editor, $cfgjl, $Itemid;
		global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang;

		$tmplvars = get_object_vars( $row );
		$tmplvars['option'] = $option;
		$tmplvars['Itemid'] = $Itemid;
		foreach ( $cfgjl as $k => $v ) {
			$tmplvars["config_$k"] = $v;
		}

		$tmpl = new mxTemplate( "$mosConfig_absolute_path/components/$option/templates/{$cfgjl['template']}" );
		if ( $tmpl->setTemplate( "applicationform" ) ) {
			$tmpl->setVars( $tmplvars );
			$tmpl->parseTemplate();
			print $tmpl->getOutput();
		} else {
			showError( _JL_ERRORSETTMPL . ": applicationform" );
		}

	}

	function show( $row, $showtype="view" ) {
		global $option, $my, $mosConfig_editor, $cfgjl, $Itemid;
		global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang;

		switch ($showtype) {
			case "preview": $heading = _JL_PREVIEW_JOBPOSTING . ": " . $row->title; $showtypeview = 0; break;
			default: $heading = $row->title; $showtypeview = 1;
		}



		$tmplvars = array(
			'heading' => $heading,
			'jobtypestring' => getJobTypeString( $row->jobtype ),
			'applylink' => $applylink,
			'showtype_view' => $showtypeview,
			'contactphone_view' => ($row->contactphone) ? _JL_CONTACTPHONE . ": " . $row->contactphone . "<br />" : "",
			'contactemail_view' => ($row->contactemail) ? _JL_EMAIL . ": <a href=\"mailto:$row->contactemail\">$row->contactemail</a><br />" : "",
			'link_list' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=list"),
			'link_search' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=search"),
			);
		
		$tmplvars = array_merge( $tmplvars, get_object_vars( $row ) );
		foreach ( $cfgjl as $k => $v ) {
			$tmplvars["config_$k"] = $v;
		}

		// Parse the attrib values for easier manipulation
		if ( trim( $row->attribs ) ) {
			$row->attribs = mosParseParams( $row->attribs );
			$row->attribs = get_object_vars( $row->attribs );
		} else {
			$row->attribs = array();
		}

		// Add the custom fields to the tmplvars array
		$extrafields = getExtraFields();
		foreach ( $extrafields as $f ) {
			$fc = cleanString( $f );
			if ( isset( $row->attribs[$fc] ) && trim( $row->attribs[$fc] ) ) {
				$tmplvars["attribs_$f"] = $row->attribs[$fc];
			} else {
				$tmplvars["attribs_$f"] = "";
			}
		}

		// Add custom keyword sets (groups of checkbox items)
		$keysets = getKeywordSets();
		foreach ( $keysets as $set => $keywords ) {
			$setc = cleanString( $set );
			if ( isset( $row->attribs[$setc] ) && trim( $row->attribs[$setc] ) ) {
				$keywords = explodeTrim( $row->attribs[$setc] );
				$tmplvars["attribs_$set"] = implode( ", ", $keywords );
			} else {
				$tmplvars["attribs_$set"] = "";
			}
		}


		$tmpl = new mxTemplate( "$mosConfig_absolute_path/components/$option/templates/{$cfgjl['template']}" );
		if ( $tmpl->setTemplate( "show" ) ) {
			$tmpl->setVars( $tmplvars );
			$tmpl->parseTemplate();
			print $tmpl->getOutput();
		} else {
			showError( _JL_ERRORSETTMPL . ": show" );
		}
		
	}

	function previewJobPosting( $row ) {
		global $option, $my, $mosConfig_editor, $cfgjl, $Itemid;
		global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang;

		mosMakeHtmlSafe( $row );

		$hiddenvalues = "";
		foreach ( get_object_vars( $row ) as $k => $v ) {
			if ( $k != "attribs" && substr( $k, 0, 1 ) != "_" ) {
				$hiddenvalues .= "  <input type='hidden' name='$k' value='$v' />\n";
			}
		}

		$row->attribs = mosParseParams( $row->attribs );
		$row->attribs = get_object_vars( $row->attribs );
		foreach ( $row->attribs as $key => $val ) {
			$hiddenvalues .= "  <input type='hidden' name='$key' value='$val' />\n";
		}

		$tmplvars = array(
			'Itemid' => $Itemid,
			'hiddenvalues' => $hiddenvalues,
			'link_list' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=list"),
			'link_search' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=search"),
			);
		
		$tmpl = new mxTemplate( "$mosConfig_absolute_path/components/$option/templates/{$cfgjl['template']}" );
		if ( $tmpl->setTemplate( "preview" ) ) {
			$tmpl->setVars( $tmplvars );
			$tmpl->parseTemplate();
			print $tmpl->getOutput();
		} else {
			showError( _JL_ERRORSETTMPL . ": preview" );
		}


	}

	function editJobPosting ( $row, $lists ) {
		global $option, $my, $mosConfig_editor, $cfgjl, $Itemid;
		global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang;
		mosMakeHtmlSafe( $row );
		$keysets = getKeywordSets();
		$extrafields = getExtraFields();
?>
<script language="javascript" type="text/javascript">
	 /**
	  * Submit the admin form
	  */
	 function submitform(pressbutton){
		 document.adminForm.task.value=pressbutton;
		 try {
			 document.adminForm.onsubmit();
		 }
		 catch(e){}
		 document.adminForm.submit();
	 }
	 function submitbutton(pressbutton) {
		var form = document.adminForm;
  	        if (pressbutton == 'cancel') {
			    submitform( pressbutton );
			    return;
		    }

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
print getEditorContentsJx( 'editor1', 'description') ;
?>
		    // do field validation
		    if (form.title.value == ""){
			    alert( "<?php echo _JL_A_NOTITLE; ?>" );
		    } else {
			     submitform( pressbutton );
		    }

	}
</script>
<form action="index.php" method="POST" name="adminForm" enctype="multipart/form-data">
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
	  <tr>
	    <td class="contentheading" width="100%" colspan="2"><?php echo _JL_ENTERJOBINFO; ?></td>
	  </tr>
      <tr>
        <td width="20%" align="right"><?php echo _JL_A_ENTER_TITLE; ?></td>
          <td width="60%"><input class="inputbox" type="text" name="title" size="40" maxlength="255" value="<?php echo $row->title; ?>" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo _JL_A_ENTER_JOBTYPE; ?></td>
        <td><?php echo $lists['jobtype']; ?></td>
      </tr>
       <tr>
        <td valign="top" align="right"><?php echo _JL_A_ENTER_DESCRIPTION; ?></td>
        <td><?php
   // parameters : areaname, content, hidden field, width, height, rows, cols
   print editorAreaJx( 'editor1',  "$row->description", 'description', '40', '10' );
?></td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo _JL_A_ENTER_QUALIFICATIONS; ?></td>
        <td><textarea class="inputbox" name="qualifications" id="qualifications" cols="60" rows="5"><?php echo $row->qualifications; ?></textarea></td>
      </tr>
      <tr>
        <td align="right"><?php echo _JL_A_ENTER_COMPENSATION; ?></td>
        <td><input class="inputbox" type="text" name="compensation" size="40" maxlength="100" value="<?php echo $row->compensation; ?>" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo _JL_A_SHOW_COMPENSATION; ?></td>
        <td><input type='checkbox' name='showcomp' value='1' <?php echo ($row->showcomp == "1") ? "checked='checked'" : ""; ?> /></td>
      </tr>


<?php foreach ( $extrafields as $field ) { ?>
 <?php if ( trim( $field ) ) { ?>
      <tr>
        <td valign="top" align="right"><?php echo $field; ?></td>
        <td><input class="inputbox" type="text" name="<?php echo cleanString($field); ?>" size="50" maxlength="255" value="<?php echo isset($row->attribs[cleanString($field)]) ? $row->attribs[cleanString($field)] : ""; ?>" /></td>
      </tr>
 <?php } ?>
<?php } ?>

<?php foreach ( $keysets as $set => $keywords ) { ?>
 <?php if ( trim( $set ) ) { ?>
      <tr>
        <td valign="top" align="right"><?php echo $set; ?></td>
        <td valign="top">
          <input type="hidden" name="<?php echo cleanString($set); ?>" value="" />
  	      <?php foreach ( $keywords as $kw ) { ?>
		   <input type="checkbox" name="cb_<?php echo cleanString($set); ?>" value="<?php echo $kw; ?>" <?php echo in_array( $kw, $row->keywords[cleanString($set)] ) ? 'checked="checked"' : ''; ?>/> <?php echo $kw; ?><br />
          <?php } ?>
        </td>
      </tr>
 <?php } ?>
<?php } ?>

      <tr>
        <td align="right"><?php echo _JL_A_ENTER_COMPANYNAME; ?></td>
        <td><input class="inputbox" type="text" name="company" size="40" maxlength="100" value="<?php echo $row->company; ?>" />
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
		<td><input class="inputbox" type="text" name="companyurl" size="40" maxlength="100" value="<?php echo trim ($row->companyurl) ? $row->companyurl : "http://"; ?>" />
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
<?php if ( $cfgjl['ccfields'] ) { ?>
      <tr>
        <td valign="top" align="right"><?php echo _JL_A_ENTER_CREDITCARD_TYPE; ?></td>
        <td><?php echo $lists['creditcardtype']; ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo _JL_A_ENTER_CREDITCARD_NUMBER; ?></td>
        <td><input class="inputbox" type="text" name="creditcardnumber" size="16" maxlength="16" value="<?php echo $row->creditcardnumber; ?>" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo _JL_A_ENTER_CREDITCARD_EXPIRES; ?></td>
        <td><?php echo $lists['ccexpmon']; ?> / <?php echo $lists['ccexpyear']; ?></td>
      </tr>
<?php } ?>
      <tr>
        <td colspan="2" height="5"></td>
      <tr>
<?php if ( trim( $cfgjl['termsofservice'] ) ) { ?>
      <tr>
		<td valign="top" align="right"><?php echo _JL_TERMSOFSERVICE; ?></td>
        <td><?php echo $cfgjl['termsofservice']; ?></td>
      </tr>
      <tr>
        <td colspan="2" height="5"></td>
      <tr>
<?php } ?>

    </table>

  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
  <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
  <input type="hidden" name="version" value="<?php echo $row->version; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="button" name="previewbtn" class="button" value="<?php echo _JL_PREVIEW_JOB; ?>" onclick="submitbutton( 'preview' );" />
  <input type="button" name="cancelbtn" class="button" value="<?php echo _JL_CANCEL_JOB; ?>" onclick="submitbutton( 'cancel' );" />
</form>

<?php
	  }

	function showSearch( $search = "" ) {
		global $option, $my, $mosConfig_editor, $cfgjl, $Itemid;
		global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang;

		$tmplvars = array(
			'Itemid' => $Itemid,
			'search' => $search,
			'link_list' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=list"),
			'link_search' => sefRelToAbs("index.php?option=$option&Itemid=$Itemid&task=search"),
			);
		
		$tmpl = new mxTemplate( "$mosConfig_absolute_path/components/$option/templates/{$cfgjl['template']}" );
		if ( $tmpl->setTemplate( "search" ) ) {
			$tmpl->setVars( $tmplvars );
			$tmpl->parseTemplate();
			print $tmpl->getOutput();
		} else {
			showError( _JL_ERRORSETTMPL . ": search" );
		}
	}

	function showError( $error, $error_header ) {
		global $option, $my, $mosConfig_editor, $cfgjl, $Itemid;
		global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang;

		$tmplvars = array(
			'Itemid' => $Itemid,
			'error' => $error,
			'error_header' => $error_header,
			);

		$tmpl = new mxTemplate( "$mosConfig_absolute_path/components/$option/templates/{$cfgjl['template']}" );
		
		if ( $tmpl->setTemplate( "error" ) ) {
			$tmpl->setVars( $tmplvars );
			$tmpl->parseTemplate();
			print $tmpl->getOutput();
		} else {
			print  _JL_ERRORSETTMPL . ": error";
			print "<br />Original error: $error";
		}
	}
}
?>

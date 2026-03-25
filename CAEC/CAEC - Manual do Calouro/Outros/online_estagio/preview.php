<?php


define( "_VALID_MOS", 1 );

require_once("../../includes/auth.php");
$css = mosGetParam($_REQUEST,"t","");

//Get right Language file
if ( file_exists( "../../../components/com_online_estagio/language/$mosConfig_lang.php" ) ) {
	include_once( "../../../components/com_online_estagio/language/$mosConfig_lang.php" );
} else {
	include_once( "../../../components/com_online_estagio/language/english.php" );
}
include_once( "../../../components/com_online_estagio/configuration.php" );

?>
<?php echo "<"."?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo _JL_A_PREVIEW; ?></title>
<link rel="stylesheet" href="../../../templates/<?php echo $css;?>/css/template_css.css" type="text/css">
    <script language="javascript" type="text/javascript">
		var form = window.opener.document.adminForm

		var title = form.title.value;
        var description = form.description.value;
        description = description.replace(/\n/g,"<br />\n");

		var applyinfo = form.applyinfo.value;
        applyinfo = applyinfo.replace(/\n/g,"<br />\n");

		var qualifications = form.qualifications.value;
        qualifications = qualifications.replace(/\n/g,"<br />\n");

        var id = form.id.value;
        var created = form.created.value;
        var compensation = form.compensation.value;
        var showcomp = form.showcomp.checked;
        var company = form.company.value;
        var address1 = form.address1.value;
        var address2 = form.address2.value;
        var city = form.city.value;
        var companyurl = form.companyurl.value;
        var contactname = form.contactname.value;
        var contactphone = form.contactphone.value;
        var contactemail = form.contactemail.value;
        var showcontact = form.showcontact.checked;

        var jobtypes = new Array("<?php echo _JL_JOBTYPE_FULLTIME; ?>", "<?php echo _JL_JOBTYPE_PARTTIME; ?>", "<?php echo _JL_JOBTYPE_INTERNSHIP; ?>");
        var jobtype = jobtypes[form.jobtype.value];



        if ( id == '' ) {
			id = '<?php echo _JL_A_NOIDYET; ?>';
        }

        if ( address2 != "" ) {
			address2 = address2 + '<br />';
		}
	</script>
</head>

<body style="background-color:#FFFFFF">
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="90%" class="contentpane">
	  <tr>
		<td class="componentheading" width="100%" colspan="2"><script>document.write(title)</script></td>
	  </tr>
      <tr>
        <td colspan="2" height="5"></td>
      </tr>
      <tr>
        <td width="20%"><b><?php echo _JL_JOBTYPE; ?></b></td>
		<td><script>document.write(jobtype)</script></td>
      </tr>
      <tr>
        <td colspan="2" height="5"></td>
      </tr>
      <tr>
        <td><b><?php echo _JL_JOBID; ?></b></td>
		<td><script>document.write(id)</script></td>
      </tr>
      <tr>
        <td colspan="2" height="5"></td>
      </tr>
      <tr>
        <td valign="top"><b><?php echo _JL_COMPANYINFO; ?></b></td>
		<td>
          <script>document.write(company)</script><br />
          <script>document.write(address1)</script><br />
		  <script>document.write(address2)</script>
		  <script>document.write(city)</script>,
          <b><?php echo _JL_WEBSITE; ?>:</b> <a href='<script>document.write(companyurl)</script>' target='_blank'><script>document.write(companyurl)</script></a><br />
        </td>
      </tr>
      <tr>
        <td colspan="2" height="5"></td>
      </tr>
      <tr>
        <td valign="top"><b><?php echo _JL_DESCRIPTION; ?></b></td>
		<td><script>document.write(description)</script></td>
      </tr>
      <tr>
        <td colspan="2" height="5"></td>
      </tr>
      <tr>
        <td valign="top"><b><?php echo _JL_QUALIFICATIONS; ?></b></td>
		<td><script>document.write(qualifications)</script></td>
      </tr>
      <tr>
        <td colspan="2" height="5"></td>
      </tr>
<script>
		if ( showcomp ) {
            document.write( '      <tr>\n');
            document.write( '        <td><b><?php echo _JL_COMPENSATION; ?></b></td>\n');
            document.write( '		<td>' + compensation + ' </td>\n');
            document.write( '      </tr>\n');
            document.write( '      <tr>');
            document.write( '        <td colspan="2" height="5"></td>');
            document.write( '      </tr>');
        }
</script>
      <tr>
        <td colspan="2" height="5"></td>
      </tr>
<script>
		if ( showcontact ) {
            document.write( '      <tr>');
            document.write( '        <td valign="top"><b><?php echo _JL_CONTACTINFO; ?></b></td>');
            document.write( '		<td>');
            document.write( '          ' + contactname + '<br />');
            document.write( '		  <?php echo _JL_CONTACTPHONE; ?>: ' + contactphone + '<br />');
            document.write( '          <?php echo _JL_EMAIL; ?>: <a href="mailto:' + contactemail + '">' + contactemail + '</a><br />');
            document.write( '        </td>');
            document.write( '      </tr>');
            document.write( '      <tr>');
            document.write( '        <td colspan="2" height="5"></td>');
            document.write( '      </tr>');
		}
</script>
	  <tr>
		<td align="center" colspan="2">
          <a href="#" onClick="window.close()"><?php echo _JL_A_CLOSE; ?></a>
          <a href="javascript:;" onClick="window.print(); return false"><?php echo _JL_A_PRINT; ?></a>
        </td>
	  </tr>
	</table>

<br />


</body>
</html>

<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mosConfig_absolute_path."/components/com_postcard/postcard.html.php" );

switch ($task) {
	case "send1":
		Send1( $option, $Itemid, $your_name, $your_mail, $to_name, $to_mail, $subject, $postmessage, $id, $filename );
		break;

	case "send2":
		Send2( $option, $Itemid, $your_name, $your_mail, $to_name, $to_mail, $subject, $postmessage, $id, $filename );
		break;

	case "send3":
		Send3( $option, $Itemid, $your_name, $your_mail, $to_name, $to_mail, $subject, $postmessage, $id, $filename );
		break;

	case "view":
		viewPostal( $option, $Itemid, $id, $mail );
		break;

	case "category":
		FrontpageCategory( $option, $Itemid, $id );
		break;
	
	default:
		Frontpage( $option, $Itemid );
		break;
}


function viewPostal( $option, $Itemid, $id, $mail ) {
	global $database;

	$database->setQuery( "SELECT * FROM #__postcard_send WHERE id='".$id."' AND from_email='".$mail."'" );
	$row = null;
	$database->loadObject( $row );
	echo $database->getErrorMsg();

	$database->setQuery( "SELECT filename FROM #__postcard WHERE id='".$row->id_postcard."'" );
	$filename = $database->loadResult();
	echo $database->getErrorMsg();

	ShowPostal( $option, $Itemid, $row, $filename );
}


function Send1( $option, $Itemid, $your_name, $your_mail, $to_name, $to_mail, $subject, $postmessage, $id, $filename ) {
	global $database;
	
	$database->setQuery( "SELECT * FROM #__postcard_config WHERE id='1'" );
	$postcard_info = null;
	$database->loadObject( $postcard_info );
	echo $database->getErrorMsg();
	
	ShowSend1( $option, $Itemid, $postcard_info, $id, $filename, $your_name, $your_mail, $to_name, $to_mail, $subject, $postmessage );
}

function Send2( $option, $Itemid, $your_name, $your_mail, $to_name, $to_mail, $subject, $postmessage, $id, $filename ) {
	ShowSend2( $option, $Itemid, $postcard_info, $id, $filename, $your_name, $your_mail, $to_name, $to_mail, $subject, $postmessage );
}

function Send3( $option, $Itemid, $your_name, $your_mail, $to_name, $to_mail, $subject, $postmessage, $id, $filename ) {
	global $mosConfig_lang, $database, $mosConfig_live_site;
	
	require("components/com_postcard/language/$mosConfig_lang.php");
	
	$database->setQuery( "SELECT view_in_template FROM #__postcard_config WHERE id='1'" );
	$view_in_template = $database->loadResult();

	$database->setQuery( "INSERT INTO #__postcard_send(date, id_postcard, from_email, from_name, to_name, to_email, subject, message) VALUES('".date("Y-m-d")."', '$id', '$your_name', '$your_mail', '$to_name', '$to_mail', '$subject', '$postmessage')" );
	$database->query();

	$head= "MIME-Version: 1.0\n";
	$head .= "Content-type: text/plain; charset=iso-8859-1\n";
	$head .= "X-Priority: 1\n";
	$head .= "X-MSMail-Priority: High\n";
	$head .= "X-Mailer: php\n";
	$head .= "From: \"".$your_name."\" <".$your_mail.">\n";
	
	$postmessage = _EMAIL_CONTENT;
	$postmessage = str_replace("{name}", $to_name, $postmessage);
	if($view_in_template==1) {
		$postmessage = str_replace("{link}", sefRelToAbs($mosConfig_live_site."/index.php?option=$option&Itemid=$Itemid&task=view&id=".mysql_insert_id()."&mail=".$your_mail), $postmessage);
	}else{
		$postmessage = str_replace("{link}", sefRelToAbs($mosConfig_live_site."/index2.php?option=$option&Itemid=$Itemid&task=view&id=".mysql_insert_id())."&mail=".$your_mail, $postmessage);
	}
	$postmessage = str_replace("{website}", $mosConfig_live_site, $postmessage);
	
	mail($to_mail, _POSTCARD_MAIL_SUBJECT, $postmessage, "From: \"".$your_name."\" <".$your_mail.">\n");
	
	ShowSend3( $option, $Itemid, $postcard_info, $id, $filename, $your_name, $your_mail, $to_name, $to_mail, $subject, $postmessage );
}

function Frontpage( $option, $Itemid ) {
	global $database;
	
	$database->setQuery( "SELECT * FROM #__postcard_config WHERE id='1'" );
	$postcard_info = null;
	$database->loadObject( $postcard_info );
	echo $database->getErrorMsg();

	$database->setQuery( "SELECT * FROM #__postcard_category" );
	$rows = $database->loadObjectList();
	echo $database->getErrorMsg();
	
	ShowFrontpage( $option, $Itemid, $postcard_info, $rows );
}

function FrontpageCategory( $option, $Itemid, $id_category ) {
	global $database;

	$database->setQuery( "SELECT * FROM #__postcard_category WHERE id='$id_category'" );
	$category_info = null;
	$database->loadObject( $category_info );
	echo $database->getErrorMsg();
	
	$database->setQuery( "SELECT * FROM #__postcard WHERE id_category='$id_category'" );
	$rows = $database->loadObjectList();
	echo $database->getErrorMsg();
	
	ShowFrontpageCategory( $option, $Itemid, $rows, $category_info );
}
?>
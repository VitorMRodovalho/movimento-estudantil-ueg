<?php


defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ( $task ) {
	case "conf":
		TOOLBAR_online_estagio::_EDIT_CONFIG();
		break;
	case "list":
		TOOLBAR_online_estagio::LIST_MENU();
		break;
	case "queue":
		TOOLBAR_online_estagio::QUEUE_MENU();
		break;
	case "new":
	case "edit":
	case "editqueue":
		TOOLBAR_online_estagio::EDIT_MENU();
		break;
	case "listtemplates":
		TOOLBAR_online_estagio::LISTTMPL_MENU();
		break;
	case "edittemplate":
		TOOLBAR_online_estagio::EDITTMPL_MENU();
		break;
	case "info":
	default:
		TOOLBAR_online_estagio::BACKONLY_MENU();
		break;
}
?>
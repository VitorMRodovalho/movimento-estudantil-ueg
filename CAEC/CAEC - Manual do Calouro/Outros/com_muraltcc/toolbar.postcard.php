<?php
// $Id: toolbar.weblinks.php,v 1.5 2003/09/22 07:03:20 rcastley Exp $
/**
* Weblink Toolbar Menu
* @package Mambo Open Source
* @Copyright (C) 2000 - 2003 Miro International Pty Ltd
* @ All rights reserved
* @ Mambo Open Source is Free Software
* @ Released under GNU/GPL License: http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.5 $
**/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );
require_once( $mainframe->getPath( 'toolbar_default' ) );

switch ($task) {
//CONFIG EVENTS
	case "config":
		menuConfig::DEFAULT_MENU();
		break;

//POSTCARD EVENTS
	case "manage_new":
		menuPostcard::EDIT_MENU();
		break;

	case "manage_edit":
		menuPostcard::EDIT_MENU();
		break;

	case "manage":
		menuPostcard::DEFAULT_MENU();
		break;

//CATEGORY EVENTS
	case "category_new":
		menuCategory::EDIT_MENU();
		break;

	case "category_edit":
		menuCategory::EDIT_MENU();
		break;

	case "category":
		menuCategory::DEFAULT_MENU();
		break;
}
?>
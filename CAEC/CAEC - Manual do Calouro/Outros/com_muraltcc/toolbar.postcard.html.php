<?php
// $Id: toolbar.weblinks.html.php,v 1.5 2003/09/22 07:03:20 rcastley Exp $
/**
* Weblink Toolbar Menu HTML
* @package Mambo Open Source
* @Copyright (C) 2000 - 2003 Miro International Pty Ltd
* @ All rights reserved
* @ Mambo Open Source is Free Software
* @ Released under GNU/GPL License: http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.5 $
**/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class menuConfig {
	function DEFAULT_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'config_save', 'Save' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
}

class menuPostcard {
	function EDIT_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'manage_save', 'Save' );
		mosMenuBar::cancel( 'manage_cancel', 'Cancel' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function DEFAULT_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::addNew( 'manage_new', 'Add' );
		mosMenuBar::editList( 'manage_edit', 'Edit' );
		mosMenuBar::deleteList( 'manage_remove', 'manage_remove' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
}

class menuCategory {
	function EDIT_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'category_save', 'Save' );
		mosMenuBar::cancel( 'category_cancel', 'Cancel' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function DEFAULT_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::addNew( 'category_new', 'Add' );
		mosMenuBar::editList( 'category_edit', 'Edit' );
		mosMenuBar::deleteList( 'category_remove', 'category_remove' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
}
?>
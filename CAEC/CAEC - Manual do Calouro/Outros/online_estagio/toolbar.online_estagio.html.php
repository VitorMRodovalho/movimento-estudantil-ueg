<?php


defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class TOOLBAR_online_estagio {

	function _EDIT_CONFIG() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::save( 'saveconf' );
		mosMenuBar::back();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function BACKONLY_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::back();
		mosMenuBar::endTable();
	}

	function QUEUE_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::editList();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	
	function LIST_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::publish();
		mosMenuBar::unpublish();
		mosMenuBar::divider();
		mosMenuBar::addNew();
		mosMenuBar::editList();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	
	function EDIT_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::preview( '../components/com_online_estagio/preview' );
		mosMenuBar::divider();
		mosMenuBar::save();
		mosMenuBar::divider();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function LISTTMPL_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::editList( 'edittemplate' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function EDITTMPL_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'savetemplate' );
		mosMenuBar::divider();
		mosMenuBar::cancel( 'canceltemplate' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

}
?>
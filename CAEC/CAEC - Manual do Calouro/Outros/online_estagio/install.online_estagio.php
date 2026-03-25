<?php
	/**
	 *	online_estagio Component for Mambo 4.5
     */
function com_install() {
	global $database, $mosConfig_absolute_path;

	$errmsg = "";

	// Change the image for the Job List Administration submenu item in the admin section.
	$sql = "UPDATE #__components SET admin_menu_img='js/ThemeOffice/edit.png' WHERE admin_menu_link='option=com_online_estagio&task=list'";
	$database->setQuery($sql);
	if ( !$database->query() ) {
		$errmsg .= "Couldn't update image for Job Postings submenu item.<br />\n";
	}

	// Change the image for the Configuration submenu item in the admin section.
	$sql = "UPDATE #__components SET admin_menu_img='js/ThemeOffice/config.png' WHERE admin_menu_link='option=com_online_estagio&task=conf'";
	$database->setQuery($sql);
	if ( !$database->query() ) {
		$errmsg .= "Couldn't update image for Configuration submenu item.<br />\n";
	}

	// Change the image for the Information submenu item in the admin section.
	$sql = "UPDATE #__components SET admin_menu_img='js/ThemeOffice/help.png' WHERE admin_menu_link='option=com_online_estagio&task=info'";
	$database->setQuery($sql);
	if ( !$database->query() ) {
		$errmsg .= "Couldn't update image for Information submenu item.<br />\n";
	}

	// Change the image for the List Templates submenu item in the admin section.
	$sql = "UPDATE #__components SET admin_menu_img='js/ThemeOffice/template.png' WHERE admin_menu_link='option=com_online_estagio&task=listtemplates'";
	$database->setQuery($sql);
	if ( !$database->query() ) {
		$errmsg .= "Couldn't update image for List Templates submenu item.<br />\n";
	}

/*
	// Install modules
	$modules = array( "mod_gtlinks_menu", "mod_gtlinks_categories" );
	foreach ( $modules as $mod ) {
		$modfile = "$mosConfig_absolute_path/components/com_gtlinks/$mod.zip";
		$base_Dir = "$mosConfig_absolute_path/uploadfiles/";
		if ( is_writable( $base_Dir ) ) {
			if ( !( @rename( $modfile, "$base_Dir/$mod.zip" ) && chmod( "$base_Dir/$mod.zip", 0777 ) ) ) {
				$errmsg .= "Couldn't copy module file $mod.zip to installation directory.<br />\n";
			} else {
				$modinstaller = new mosInstallerModule( "$mod.zip" );
				if( !$modinstaller->install() )
				{
					$errmsg .= "Error installing module $mod. " . $modinstaller->getError() . "<br />\n";
				}
				cleanupInstall( "$mod.zip", $modinstaller->unpackDir() );
			}
		} else {
			$errmsg .= "Directory uploadfiles is not writeable!<br />\n";
		}
	}
*/

	// Show installation error messages
	if ( $errmsg ) {
		HTML_installer::showInstallMessage( $errmsg, "Installation error messages", $option );
	}

	return "online_estagio Successfully Installed. Visit <a href=\"http://mambo.theyard.org\" target=\"blank\">Mambo at the Yard</a> for more exciting Mambo addons.";
}
?>

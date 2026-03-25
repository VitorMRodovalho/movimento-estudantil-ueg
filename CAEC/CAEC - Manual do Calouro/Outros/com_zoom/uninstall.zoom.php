<?php
//zOOm Media Gallery//
/** 
-----------------------------------------------------------------------
|  zOOm Media Gallery! by Mike de Boer - a multi-gallery component    |
-----------------------------------------------------------------------

-----------------------------------------------------------------------
|                                                                     |
| Date: February, 2005                                                |
| Author: Mike de Boer, <http://www.mikedeboer.nl>                    |
| Copyright: copyright (C) 2004 by Mike de Boer                       |
| Description: zOOm Media Gallery, a multi-gallery component for      |
|              Mambo. It's the most feature-rich gallery component    |
|              for Mambo! For documentation and a detailed list       |
|              of features, check the zOOm homepage:                  |
|              http://zoom.ummagumma.nl                               |
| License: GPL                                                        |
| Filename: uninstall.zoom.php                                        |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
function com_uninstall(){
		if( $zoom_agree != 1 ){ ?>
		<form action="uninstall.zoom.php" method="post" name="form" id="form">
			<table border="0" cellspacing="0" cellpadding="0" background="<?php $mosConfig_live_site; ?>'/components/com_zoom/images/zoom_logo_faded.gif" style="background-repeat:no-repeat; background-position:top right;" width="75%">
				<p><input type="checkbox" name="zoom_agree" value="1" />Do you want to delete zOOm Media Gallery along with your uploaded media?</p>
				<p><input type="submit" name="uninstall_zoom" value="Un-install zOOm" /></p>
			</table>
		</form>
		<?php
	}else{
		delzoom( '../images/zoom' );
		if( $del_zoom == 1 ) {
			echo "zOOm Media Gallery un-installed succesfully.";
		}else{
			echo "zOOm Media Gallery could not be un-installed completely.<br />";
			echo "The directory \"images/zoom\" has to be deleted manually!";
		}
	}

	function delzoom( $dir ) {
		$dh=opendir( $dir );
   		while ( $file=readdir( $dh ) ) {
       		if( $file != '.' && $file != '..' ) {
           		$fullpath = $dir . '/' . $file;
           		if( !is_dir( $fullpath ) ) {
               		unlink( $fullpath );
           		} else {
               		deldir( $fullpath );
           		}
       		}
   		}

   		closedir($dh);
   
   		if(rmdir($dir)) {
       		return $del_zoom = 1;
   		} else {
       		return $del_zoom = 0;
   		}
	}
}
?>
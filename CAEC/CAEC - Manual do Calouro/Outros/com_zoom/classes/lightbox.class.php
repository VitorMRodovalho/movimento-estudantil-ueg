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
| Filename: lightbox.class.php                                        |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class lightbox extends zoom{
	var $_session_id = null;
	var $_items = array();
	var $_file = null;
	
	function lightbox(){
		$_session_id = SID;
		$this->_file = 'media/'.uniqid('lightbox_').'.zip';
	}
	function addItem($object_id, $type, $qty = 1){
		$curr_id = $this->getNoOfItems();
		foreach ($this->_items as $item){
			
		}
		$this->_items[$curr_id] = new lightbox_item($object_id, $type, $qty);
		return true;
	}
	function removeItem($id){
		unset($this->_items[$id]);
		$temp_array = array_values($this->_items);
		$this->_items = $temp_array;
		return true;
	}
	function editItem($id,$qty){
		$this->_items[$id]->setQty($qty);
		return true;
	}
	function getNoOfItems(){
		return sizeof($this->_items);
	}
	function createZipFile(){
		global $zoom, $mosConfig_live_site;
		// the idea is that the array of items is iterated through and images are added
		// to the filelist array automatically. Galleries, however, need to be parsed
		// individually!
		echo _ZOOM_LIGHTBOX_PARSEZIP;
			$filelist = array();
			foreach($this->_items as $item){
				if(isset($item->_image) || !empty($item->_image)){
					// item has been identified as an image, so add it simply to the filelist...
					$item->_image->getInfo();
					$filelist[] = $zoom->_CONFIG['imagepath'].$item->_image->getDir().'/'.$item->_image->_filename;
				}else{
					// item has been identified as a gallery, so parse it for images an THEN
					// add those to the filelist...
					foreach($item->_gallery->_images as $image){
						$image->getInfo();
						$filelist[] = $zoom->_CONFIG['imagepath'].$item->_gallery->getDir().'/'.$image->_filename;
					}
				}
			}
			$remove_dir = $zoom->_CONFIG['imagepath'];
			echo '<b><font color="green">'._ZOOM_INFO_DONE.'</font></b><br />';
		echo _ZOOM_LIGHTBOX_DOZIP;
			if($zoom->createArchive($filelist, $this->_file, $remove_dir)){
				$zoom->_EditMon->setEditMon(0, 'lightbox', $this->_file);
				echo '<b><font color="green">'._ZOOM_INFO_DONE.'</font></b><br />';
				echo _ZOOM_LIGHTBOX_DLHERE.': <a href="'.$this->_file.'"><img src="'.$mosConfig_live_site.'/components/com_zoom/images/save.png" border="0" /></a>';
			}else{
				echo '<b><font color="red">error!</font></b><br />';
			}
	}
}
class lightbox_item extends lightbox{
	var $_id = null;
	var $_image = null;
	var $_gallery = null;
	var $_qty = null;
	
	function lightbox_item($object_id, $type, $qty = 1){
		if($type == 1)
			$this->_image = new image($object_id);
		elseif($type == 2)
			$this->_gallery = new gallery($object_id);
		$this->_qty = $qty;		
	}
	function getImage(){
		return $this->_image;		
	}
	function getGallery(){
		return $this->_gallery;
	}
	function getQty(){
		return $this->_qty;
	}
	function setQty($qty = 1){
		$this->_qty = $qty;
	}
}

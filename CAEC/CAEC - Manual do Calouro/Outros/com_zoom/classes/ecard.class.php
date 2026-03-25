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
| Filename: ecard.class.php                                           |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class ecard {
	var $_id = null;
	var $_image = null;
	var $_to_name = null;
	var $_from_name = null;
	var $_to_email = null;
	var $_from_email = null;
	var $_message = null;
	var $_end_date = null;
	var $_user_ip = null;
	//--------------------Default Constructor of the ecard-class------------//
	function ecard($id = 0){
		$this->_user_ip = getenv('REMOTE_ADDR');
		if($id == 0){
			$this->_id = date("U").rand(100, 500);
		}else{
			$this->_id = $id;
			$this->getInfo();
		}
	}
	function getInfo(){
		global $database;
		$database->setQuery("SELECT * FROM #__zoom_ecards WHERE ecdid = ".$this->_id." LIMIT 1");
		$result = $database->query();
		while($row = mysql_fetch_object($result)){
			$this->_image = new image($row->imgid);
			$this->_to_name = $row->to_name;
			$this->_from_name = $row->from_name;
			$this->_to_email = $row->to_email;
			$this->_from_email = $row->from_email;
			$this->_message = $row->message;
			$this->_end_date = $row->end_date;
			$this->_user_ip = $row->user_ip;
		}
	}
	function save($imgid, $to_name, $from_name, $to_email, $from_email, $message){
		global $database, $zoom;
		$this->_image = $imgid;
		$this->_to_name = $to_name;
		$this->_from_name = $from_name;
		$this->_to_email = $to_email;
		$this->_from_email = $from_email;
		$this->_message = $message;
		// construct the end-date for this eCard...
		$lifetime = $zoom->_CONFIG['ecards_lifetime'];
		$tempDate = date('Y-d-m');
		$date_arr = explode("-", $tempDate);
		intval($date_arr[0]);
		intval($date_arr[1]);
		intval($date_arr[2]);
		if($lifetime == 7 || $lifetime == 14){
			// 7 means seven days, or a WEEK; 14 means fourteen days, or TWO WEEKS
			for($i = 1; $i <= $lifetime; $i++){
				$date_arr[1]++;
				if(!checkdate($date_arr[2], $date_arr[1], $date_arr[0])){
					//faulty date
					$date_arr[2]++; //add one month
					if($date_arr[2] >= 12)
						$date_arr[0] = 1; //set month to january.
					$date_arr[1] = 1; //set no. of days to one (new month!)
				}
				
			}
		}elseif($lifetime == 1 || $lifetime == 3){
			// 1 means ONE MONTH; 3 means THREE MONTHS
			for($i = 1; $i <= $lifetime; $i++){
				$date_arr[2]++;
				if(!checkdate($date_arr[2], $date_arr[1], $date_arr[0])){
					$date_arr[0]++; //add one year
					$date_arr[2] = 1; //set no. of months to one (new year!)
				}
			}
		}else{
			return false;
		}
		$this->_end_date = implode("-", $date_arr);
		$database->setQuery("INSERT INTO #__zoom_ecards "
		 . "SET ecdid='".$this->_id."',imgid='".$this->_image."', to_name='".$this->_to_name."',from_name='".$this->_from_name."',"
		 . "to_email='".$this->_to_email."',from_email='".$this->_from_email."',message='".$this->_message."',"
		 . "end_date='".$this->_end_date."', user_ip='".$this->_user_ip."'");
		 if ($database->query()) {
		 	return true;
		 }else{
		 	return false;
		 }
		
		
	}
	function send(){
		global $mosConfig_live_site, $mosConfig_host, $_SERVER;
		$messageUrl = sefRelToAbs($mosConfig_live_site."/index.php?option=com_zoom&Itemid=".$Itemid."&page=ecard&task=viewcard&ecdid=".$this->_id);
		$subject = _ZOOM_ECARD_SUBJ." ".$this->_from_name;
		
		$msg  = "$this->_to_name,\n\n";
		$msg .= $this->_from_name." "._ZOOM_ECARD_MSG1." ".$mosConfig_live_site."\n\n";
		$msg .= html_entity_decode(_ZOOM_ECARD_MSG2)."\n\n";
		$msg .= "URL: $messageUrl\n\n";
		$msg .= html_entity_decode(_ZOOM_ECARD_MSG3)."\n";
		$msg .= "\n\n\n\n\n";
		$msg .= "------------------------------------------------------------------------------------------------------------------\n";
		$msg .= "|  zOOm Media Gallery! - a multi-gallery component\n";
		$msg .= "|  copyright (C) 2004 by Mike de Boer, http://zoom.ummagumma.nl\n";
		$msg .= "------------------------------------------------------------------------------------------------------------------";
		
		$from = $mosConfig_live_site;
		mosMail($this->_from_email, $this->_from_name, $this->_to_email, $subject, $msg);
		return true;
	}
}
?>
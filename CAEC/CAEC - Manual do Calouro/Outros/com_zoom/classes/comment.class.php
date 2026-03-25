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
| Filename: comment.class.php                                         |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class comment{
	var $_id = null;
	var $_name = null;
	var $_comment = null;
	var $_date = null;
	
	function comment($comment_id){
		$this->_id = $comment_id;
		$this->getInfo();
	}
	function getInfo(){
		global $database;
		$database->setQuery("SELECT cmtcontent, date_format(cmtdate, '%d-%m-%y') AS date, cmtname FROM #__zoom_comments WHERE cmtid=".mysql_escape_string($this->_id));
		$this->_result = $database->query();
		while($row = mysql_fetch_object($this->_result)){
			$this->_name = stripslashes($row->cmtname);
			$this->_comment = stripslashes($row->cmtcontent);
			$this->_date = $row->date;
		}
	}
	function processSmilies($message, $url_prefix='', $smilies) { 
		global $orig, $repl; 
		if (!isset($orig)) { 
			$orig = $repl = array(); 
			for($i = 0; $i < count($smilies); $i++) { 
				$orig[] = "/(?<=.\W|\W.|^\W)" . preg_quote($smilies[$i][0], "/") . "(?=.\W|\W.|\W$)/"; 
				$repl[] = '<img src="'. $url_prefix .'images/smilies' . '/' . ($smilies[$i][1]) . '" alt="' . ($smilies[$i][2]) . '" border="0" />'; 
			} 
		}
		if (count($orig)) { 
			$message = preg_replace($orig, $repl, ' ' . $message . ' '); 
			$message = substr($message, 1, -1); 
		} 
		return $message; 
	}
}
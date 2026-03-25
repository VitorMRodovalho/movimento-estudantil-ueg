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
| Filename: zoom.class.php                                            |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class zoom{
	//first, some of the default internal variables...
	var $_sql = null;
	var $_result = null;
	var $_CONFIG = null;
	var $_toolbox = null;
	var $_EditMon = null;
	var $_gallery = null;
	var $_ecard = null;
	var $_counter = null;
	var $_isAdmin = null;
	var $_isUser = null;
	var $_CurrUID = null;
	var $_startRow = null;
	var $_pageSize = null;
	var $_tabclass = null;
	var $_EXIF_cachefile = null;
	var $_CAT_LIST = null;
	var $_isWin = null;
	var $_isBackend = null;
	var $_tempname = null;
	//--------------------Default Constructor of the zOOm-class------------//
	function zoom(){
		// initialize object variables with some values...
		$this->getConfig();
		$this->_currUID = -1;
		$this->checkAdmin(true);
		$this->checkAdmin(false);
		$this->_counter = 0;
		$this->_startRow = 0;
		$this->_tabclass = Array("sectiontableentry1", "sectiontableentry2");
		$this->_EXIF_cachefile = "exif.dat";
		$this->_isWin = (strtolower(PHP_OS) == 'winnt');
		$this->_isBackend = false;
		// get child-objects...
		$this->_EditMon = new editmon();
	}
	function isWin(){
		return $this->_isWin;
	}
	//--------------------END Constructor of the zOOm-class----------------//
	//--------------------zOOm Security Functions--------------------------//	
	function checkAdmin($admin){
  		global $my;
		$gid = intval( $my->gid );
		$username = $my->username;
		$usertype_lft = $this->getUsertypeLft();
		if($admin){
			if(strtolower($my->usertype) == 'administrator' || strtolower($my->usertype) == 'superadministrator' || strtolower($my->usertype) == 'super administrator'){
				$this->_isAdmin = true;
			}else{
				$this->_isAdmin = false;
			}
		}elseif($usertype_lft >= $this->_CONFIG['utype']){
			$this->_isUser = true;
		}else{
			$this->_isUser = false;
		}
		$this->_CurrUID = $my->id;
	}
	function getUsertypeLft(){
		global $database, $my;
		$database->setQuery("SELECT lft FROM #__core_acl_aro_groups WHERE name = '".$my->usertype."' LIMIT 1");
		if ($this->_result = $database->query()) {
			$row = mysql_fetch_object($this->_result);
			return $row->lft;
		}
		
	}
    function getUsersList($userspass = 0){
        global $database;
        // Create users List
        $database->setQuery("SELECT id,name,username FROM #__users ORDER BY name ASC");
        if ($this->_result = $database->query()) {
        	$musers = array();
	        $musers = array("<select name=\"selections[]\" class=\"inputbox\" size=\"20\" multiple=\"multiple\">");
	        $musers[] = "<option value=\"0\">"._ZOOM_USERSLIST_LINE1."</option>";
	        if(@in_array(1, $userspass))
	            $musers[] = "<option value=\"1\" selected>"._ZOOM_USERSLIST_ALLOWALL."</option>";
	        else
	            $musers[] = "<option value=\"1\">"._ZOOM_USERSLIST_ALLOWALL."</option>";
	        if(@in_array(2, $userspass))
	            $musers[] = "<option value=\"2\" selected>"._ZOOM_USERSLIST_MEMBERSONLY."</option>";
	        else
	            $musers[] = "<option value=\"2\">"._ZOOM_USERSLIST_MEMBERSONLY."</option>";
	        // append the rest of the users to the array
	        // and select the already access-granted users from the passed userlist...
	        while($row = mysql_fetch_object($this->_result)){
	            if($userspass == 0){
	                $musers[] = "<option value=\"".$row->id."\">".$row->id."-".$row->name."(".$row->username.")"."</option>";
	            }else{
	                if(in_array($row->id, $userspass))
	                    $selected = "selected";
	                else
	                    $selected = "";
	                $musers[] = "<option value=\"".$row->id."\"".$selected.">".$row->id."-".$row->name."(".$row->username.")"."</option>";
	            }
	        }
	        $musers[] = "</select>";
	        return $musers;
        }
        
    }
 	//--------------------END zOOm Security Functions----------------------//
	//--------------------Filesystem Functions-----------------------------//
	function ftp_rmAll($conn_id,$dst_dir){
	   $ar_files = ftp_nlist($conn_id, $dst_dir);
	   //check whether we really got something from the ftp_nlist function	
       if(is_array($ar_files)){
           foreach($ar_files as $dir){
              if($dir != "." && $dir != ".."){
                 if(ftp_size($conn_id,$dir)===-1){ // dirname
                   $this->ftp_rmAll($conn_id,$dir); // recursion
                 }else{
                   ftp_delete($conn_id,$dir); // del file
                 }
              }
           }
           ftp_rmdir($conn_id, $dst_dir); // delete empty directories
           return true;
        }else{
            return false;
        }
    }
	function deldir($dir){
		global $mosConfig_absolute_path;
		if($this->_CONFIG['safemodeON']){
			$dir = substr($dir,strlen($mosConfig_absolute_path));
            $dir = $this->_CONFIG['ftp_hostdir'].$dir;
			//initialize FTP connection
			$conn_id = ftp_connect($this->_CONFIG['ftp_server']);	
			// login
			$login_result = ftp_login($conn_id, $this->_CONFIG['ftp_username'], $this->_CONFIG['ftp_pass']);	
			// verify connection
			if ((!$conn_id) || (!$login_result)){
				echo ("Error connecting FTP\n"
				 . "Error connecting to FTP-Server ".$this->_CONFIG['ftp_server']." for user ".$this->_CONFIG['ftp_username']);
				return false;
			}else{
				//create directory
				//$result = ftp_rmdir($conn_id,$path);//this won't work with subdirectory      
		        $retval = $this->ftp_rmAll($conn_id, $dir); //do it recursively with helper function
			}		
			//Close connection
			ftp_quit($conn_id);		
			return $retval;
		}else{
	 		$current_dir = opendir($dir);
	 		while($entryname = readdir($current_dir)){
				if(fs_is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")){
		   			$this->deldir("${dir}/${entryname}");
		    	}elseif($entryname != "." and $entryname!=".."){
		   			fs_unlink("${dir}/${entryname}");
				}
		 	}
	 		closedir($current_dir);
	 		rmdir(${dir});
	 		return true;
		}
	}
	
	function newdir(){
		$newdir = "";
		srand((double) microtime() * 1000000);
		for ($acc = 1; $acc <= 6; $acc++){
		    $newdir .= chr(rand (0,25) + 65);
	   	}
		return $newdir;
	}
	function createdir($path, $mode = 0777){
		global $mosConfig_absolute_path;
		if($this->_CONFIG['safemodeON']){
			//append directory on host to the path...
			$path = $this->_CONFIG['ftp_hostdir']."/".$path;
			//initialize FTP connection
			$conn_id = ftp_connect($this->_CONFIG['ftp_server']);	
			// login
			$login_result = ftp_login($conn_id, $this->_CONFIG['ftp_username'], $this->_CONFIG['ftp_pass']);	
			// verify connection
			if ((!$conn_id) || (!$login_result)){
				echo ("Error connecting FTP<br />\n"
				 . "Error connecting to FTP-Server $ftp_server for user $ftp_user_name");
				exit();
			}else{
				//create directory
				//$result = ftp_mkdir($conn_id,$path); //this won't work with subdirectorys
				$dir = split("/", $path);
		   		$path = "";
				$result = true;
		   		for ($i = 1; $i < count($dir); $i++){
		       		$path .= "/".$dir[$i];
		       		//echo "$path\n";
		       		if(!@ftp_chdir($conn_id,$path)){
		        		@ftp_chdir($conn_id,"/");
		        		if(!@ftp_mkdir($conn_id,$path)){
		        			$result = false;
		         			break;
		         		}else{
							//@ftp_chmod($conn_id, $mode, $path); //this gives problems with some servers
							$chmod_cmd="CHMOD ".$mode." ".$path;
							$chmod=ftp_site($conn_id, $chmod_cmd);
						}
		       		}
		   		}
			}
			//Close connection
			ftp_quit($conn_id);	
		}else{
			//append full path to Mambo to the $path variable...
			$path = $mosConfig_absolute_path."/".$path;
			
			$result = fs_mkdir($path, $mode);
			@chmod($path, $mode);
		}
		return $result;
	}
    function writefile($filename, $content){
        if ($fp = fs_fopen("$filename", "w+")) {
		      fputs($fp, $content, strlen($content));
		      fclose ($fp);
              return true;
        }
    }
    function createPlaylist($audiofile, $artist, $title){
    	global $mosConfig_absolute_path;
    	$pl_file = $mosConfig_absolute_path."/components/com_zoom/audiolist.xml";
    	$playlist = ("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
    	 . "<songs>\n"
    	 . "\t<song path=\"".$audiofile."\" bild=\"\" artist=\"".$artist."\" title=\"".$title."\"/>\n"
    	 . "</songs>\n");
    	 $this->writefile($pl_file, $playlist);
    }
	function extractArchive($extractdir, $archivename){
		global $mosConfig_absolute_path, $version;
		
		$zlib_prefix = "$mosConfig_absolute_path/administrator/includes/pcl/";
		
		require_once( $zlib_prefix."pclzip.lib.php" );
		$zipfile = new PclZip($archivename);
		if($this->_isWin)
			define('OS_WINDOWS',1);
		$ret = $zipfile->extract(PCLZIP_OPT_PATH, $mosConfig_absolute_path."/".$extractdir);		if($ret <= 0)
			return false;
		else
			return true;
	}
	function createArchive($filelist, $archivename, $remove_dir){
		global $mosConfig_absolute_path, $version;
		$zlib_prefix = "$mosConfig_absolute_path/administrator/includes/pcl/";
		require_once( $zlib_prefix."pclzip.lib.php" );
		$zipfile = new PclZip($archivename);
		if($this->_isWin)
			define('OS_WINDOWS',1);
		$ret = $zipfile->create($filelist, '', $remove_dir);
		if($ret <= 0)
			return false;
		else
			return true;
	}
	//--------------------END Filesystem Functions-------------------------//
	//--------------------Accepted file format functions-------------------//
	function acceptableFormat($tag){
		return ($this->isImage($tag) || $this->isMovie($tag) || $this->isDocument($tag) || $this->isAudio($tag));
	}
	function acceptableFormatRegexp(){
		return "(" . join("|", $this->acceptableFormatList()) . ")";
	}
	function acceptableFormatCommaSep(){
		return join(", ", $this->acceptableFormatList());
	}
	function acceptableMovieList(){
	    return array('avi', 'mpg', 'mpeg', 'wmv', 'mov', 'rm');
	}
	function acceptableImageList(){
	    return array('jpg', 'jpeg', 'gif', 'png');
	}
	function acceptableDocumentList(){
		return array('doc', 'ppt', 'pdf', 'rtf');
	}
	function acceptableAudioList(){
		return array('mp3','ogg','wma');
	}
	function thumbnailableMovieList(){
		// this list doesn't have to be this big, BUT these are the formats supported by FFmpeg...
		return array('avi', 'ac3', 'asf', 'asx', 'dv', 'm4v', 'mpg', 'mpeg', 'mjpeg', 'mov', 'mp4', 'm4a', 'rm', 'rpm', 'wc3', 'wmv');
	}
	function thumbnailableList(){
		return array_merge($this->acceptableImageList(), $this->thumbnailableMovieList());
	}
	function indexableList(){
		return array('pdf');
	}
	function acceptableFormatList(){
	    return array_merge($this->acceptableImageList(), $this->acceptableMovieList(), $this->acceptableDocumentList(), $this->acceptableAudioList());
	}
	function isImage($tag){
	    return in_array($tag, $this->acceptableImageList());
	}
	function isMovie($tag){
	    return in_array($tag, $this->acceptableMovieList());
	}
	function isAudio($tag){
		return in_array($tag, $this->acceptableAudioList());
	}
	function isRealmedia($tag){
		if($tag == 'rm')
			return true;
		else
			return false;
	}
	function isQuicktime($tag){
		if($tag == 'mov')
			return true;
		else
			return false;
	}
	function isDocument($tag){
		return in_array($tag, $this->acceptableDocumentList());
	}
	function isThumbnailable($tag){
		return in_array($tag, $this->thumbnailableList());
	}
	function isIndexable($tag){
		return in_array($tag, $this->indexableList());
	}
	//--------------------END Accepted file format functions---------------//
	//--------------------Module auto-detection----------------------------//
	function getModule(){
		global $database;
		$database->setQuery("SELECT title FROM #__modules WHERE module = 'mod_zoom'");
		if ($this->_result = $database->query()) {
			if(mysql_num_rows($this->_result) != 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}	
	//--------------------END Module auto-detection------------------------//
	//--------------------Cleaning String-datatype-------------------------//
	function cleanString($text) {
		// code adapted from the phpDig package,
		// a PHP search engine/ webspider.
		// Copyright (C) 2001 - 2003, Antoine Bajolet, http://www.toiletoine.net/
		// Copyright (C) 2003 - current, Charter, http://www.phpdig.net/

		//replace blank characters by spaces
		$text = ereg_replace("[\r\n\t]+"," ",$text);
		//delete content of head, script, and style tags
		$text = eregi_replace("<head[^>]*>.*</head>"," ",$text);
		$text = preg_replace("/<script[^>]*?>.*?<\/script>/is"," ",$text); // less conservative
		$text = eregi_replace("<style[^>]*>.*</style>"," ",$text);
		$text = preg_replace("/<iframe[^>]*?>.*?<\/iframe>/is"," ",$text);
		// clean tags
		$text = preg_replace("/<[\/\!]*?[^<>]*?>/is"," ",$text);
		$text = ereg_replace("[[:space:]]+"," ",$text);
		return $text;
	}
	function removeTags($msg) {
		$msg = strip_tags($msg);
		return $msg;
	}
	function getRequestShift($Name)
	{
	  $Result = mosGetParam($_REQUEST,$Name);
	  if (!$Result)
	    return null;
	  if (is_array($Result))
	    return array_shift($Result);
	  return $Result;   
	}
	//--------------------END Cleaning Strings Datatype--------------------//
	//--------------------Date Handling functions--------------------------//
	function convertDate($zdate){
		// contributed by mic (developer@mamboworld.net) 2004.12.30
		// converts zooms date to a unix string and returns then local date as defined in the language string
        $tmp_year = substr( $zdate, 6, 2 ); // year
        $tmp_mon = substr( $zdate, 3, 2 ); // month
        $tmp_day = substr( $zdate, 0, 2 ); // day
        $tmp_hour = substr( $zdate, 10, 2 ); // hour
        $tmp_min = substr( $zdate, 13, 2 ); // min
        $tmp_sec = substr( $zdate, 16, 2 ); // sec
        
        $new_date = mktime( $tmp_hour, $tmp_min, $tmp_sec, $tmp_mon, $tmp_day, $tmp_year );
        $newdate = strftime ( _ZOOM_DATEFORMAT, $new_date );
        return $newdate;
	}
	//--------------------END Date Handling functions----------------------//
	//--------------------Database Editing Functions-----------------------//
	function getConfig(){
		global $database, $mosConfig_absolute_path, $zoomConfig;
		$this->_CONFIG['conversiontype'] = $zoomConfig['conversiontype'];
		$this->_CONFIG['zoom_title'] = $zoomConfig['zoom_title'];
		$this->_CONFIG['imagepath'] = $zoomConfig['imagepath'];
		$this->_CONFIG['IM_path'] = $zoomConfig['IM_path'];
		$this->_CONFIG['NETPBM_path'] = $zoomConfig['NETPBM_path'];
		$this->_CONFIG['FFMPEG_path'] = $zoomConfig['FFMPEG_path'];
		$this->_CONFIG['PDF_path'] = $zoomConfig['PDF_path'];
		$this->_CONFIG['JPEGquality'] = $zoomConfig['JPEGquality'];
		$this->_CONFIG['maxsize'] = $zoomConfig['maxsize'];
		$this->_CONFIG['size'] = $zoomConfig['size'];
		$this->_CONFIG['columnsno'] = $zoomConfig['columnsno'];
		$this->_CONFIG['PageSize'] = $zoomConfig['PageSize'];
		$this->_CONFIG['catOrderMethod'] = $zoomConfig['catOrderMethod'];
		$this->_CONFIG['orderMethod'] = $zoomConfig['orderMethod'];
		$this->_CONFIG['commentsOn'] = $zoomConfig['commentsOn'];
		$this->_CONFIG['cmtLength'] = $zoomConfig['cmtLength'];
		$this->_CONFIG['galleryPrefix'] = $zoomConfig['galleryPrefix'];
		$this->_CONFIG['ratingOn'] = $zoomConfig['ratingOn'];
		$this->_CONFIG['zoomOn'] = $zoomConfig['zoomOn'];
		$this->_CONFIG['popUpImages'] = $zoomConfig['popUpImages'];
		$this->_CONFIG['catImg'] = $zoomConfig['catImg'];
		$this->_CONFIG['slideshow'] = $zoomConfig['slideshow'];
		$this->_CONFIG['displaylogo'] = $zoomConfig['displaylogo'];
		$this->_CONFIG['allowUserUpload'] = $zoomConfig['allowUserUpload'];
		$this->_CONFIG['readEXIF'] = $zoomConfig['readEXIF'];
		$this->_CONFIG['readID3'] = $zoomConfig['readID3'];
		$this->_CONFIG['tempDescr'] = $zoomConfig['tempDescr'];
		$this->_CONFIG['tempName'] = $zoomConfig['tempName'];
		$this->_CONFIG['autonumber'] = $zoomConfig['autonumber'];
		$this->_CONFIG['showHits'] = $zoomConfig['showHits'];
		$this->_CONFIG['showName'] = $zoomConfig['showName'];
		$this->_CONFIG['showDescr'] = $zoomConfig['showDescr'];
		$this->_CONFIG['showKeywords'] = $zoomConfig['showKeywords'];
		$this->_CONFIG['showDate'] = $zoomConfig['showDate'];
		$this->_CONFIG['showFilename'] = $zoomConfig['showFilename'];
		$this->_CONFIG['showSearch'] = $zoomConfig['showSearch'];
		$this->_CONFIG['showMetaBox'] = $zoomConfig['showMetaBox'];
		$this->_CONFIG['catcolsno'] = $zoomConfig['catcolsno'];
		$this->_CONFIG['utype'] = $zoomConfig['utype'];
		$this->_CONFIG['lightbox'] = $zoomConfig['lightbox'];
		$this->_CONFIG['ecards'] = $zoomConfig['ecards'];
		$this->_CONFIG['ecards_lifetime'] = $zoomConfig['ecards_lifetime'];
		$this->_CONFIG['zoomModule'] = $zoomConfig['zoomModule'];
		$this->_CONFIG['mod_showcount'] = $zoomConfig['mod_showcount'];
		$this->_CONFIG['mod_showupdate'] = $zoomConfig['mod_showupdate'];
		$this->_CONFIG['mod_showcatnames'] = $zoomConfig['mod_showcatnames'];
		$this->_CONFIG['mod_showmeth'] = $zoomConfig['mod_showmeth'];
		$this->_CONFIG['mod_dateformat'] = $zoomConfig['mod_dateformat'];
		$this->_CONFIG['allowUserCreate'] = $zoomConfig['allowUserCreate'];
		$this->_CONFIG['allowUserDel'] = $zoomConfig['allowUserDel'];
		$this->_CONFIG['allowUserEdit'] = $zoomConfig['allowUserEdit'];
		$this->_CONFIG['safemodeON'] = $zoomConfig['safemodeON'];
		$this->_CONFIG['version'] = $zoomConfig['version'];
		$this->_CONFIG['safemodeversion'] = $zoomConfig['safemodeversion'];
		if(strlen($this->_CONFIG['safemodeversion']) > 0){
			require_once($mosConfig_absolute_path."/components/com_zoom/safemode.php");
			$this->_CONFIG['ftp_server'] = $ftp_server;
			$this->_CONFIG['ftp_username'] = $ftp_username;
			$this->_CONFIG['ftp_pass'] = $ftp_pass;
			$this->_CONFIG['ftp_hostdir'] = $ftp_hostdir;
		}
		$this->_pageSize = $this->_CONFIG['PageSize'];
	}
	function saveConfig(){
		global $database, $_REQUEST, $mosConfig_absolute_path;
		$s01 = mysql_escape_string($_REQUEST['s01']);
		$s02 = mysql_escape_string($_REQUEST['s02']);
		$s03 = mysql_escape_string($_REQUEST['s03']);
		$s04 = mysql_escape_string($_REQUEST['s04']);
		$s05 = mysql_escape_string($_REQUEST['s05']);
		$s06 = mysql_escape_string($_REQUEST['s06']);
		$s07 = mysql_escape_string($_REQUEST['s07']);
		$s08 = mysql_escape_string($_REQUEST['s08']);
		$s09 = mysql_escape_string($_REQUEST['s09']);
		$s10 = mysql_escape_string($_REQUEST['s10']);
		$s11 = mysql_escape_string($_REQUEST['s11']);
		$s12 = mysql_escape_string($_REQUEST['s12']);
		$s13 = mysql_escape_string($_REQUEST['s13']);
		$s14 = mysql_escape_string($_REQUEST['s14']);
		$s15 = mysql_escape_string($_REQUEST['s15']);
		$s16 = mysql_escape_string($_REQUEST['s16']);
		$s17 = mysql_escape_string($_REQUEST['s17']);
		// s18 is the CSS textarea...thus skipped.
		$s19 = mysql_escape_string($_REQUEST['s19']);
		$s20 = mysql_escape_string($_REQUEST['s20']);
		$s21 = (isset($HTTP_POST_VARS['s21'])) ? 1 : 0;
		$s22 = mysql_escape_string($_REQUEST['s22']);
		$s23 = mysql_escape_string($_REQUEST['s23']);
		$s24 = mysql_escape_string($_REQUEST['s24']);
		$s25 = mysql_escape_string($_REQUEST['s25']);
		$s26 = mysql_escape_string($_REQUEST['s26']);
		$s27 = mysql_escape_string($_REQUEST['s27']);
		$s28 = mysql_escape_string($_REQUEST['s28']);
		$s29 = mysql_escape_string($_REQUEST['s29']);
		$s30 = mysql_escape_string($_REQUEST['s30']);
		$s31 = mysql_escape_string($_REQUEST['s31']);
		$s32 = mysql_escape_string($_REQUEST['s32']);
		$s33 = mysql_escape_string($_REQUEST['s33']);
		$s34 = mysql_escape_string($_REQUEST['s34']);
		$s35 = mysql_escape_string($_REQUEST['s35']);
		$s36 = mysql_escape_string($_REQUEST['s36']);
		$s37 = mysql_escape_string($_REQUEST['s37']);
        $s38 = mysql_escape_string($_REQUEST['s38']);
        $s39 = mysql_escape_string($_REQUEST['s39']);
        $s40 = mysql_escape_string($_REQUEST['s40']);
        $s41 = mysql_escape_string($_REQUEST['s41']);
        $s42 = mysql_escape_string($_REQUEST['s42']);
        $s43 = mysql_escape_string($_REQUEST['s43']);
        $s44 = mysql_escape_string($_REQUEST['s44']);
        $s45 = mysql_escape_string($_REQUEST['s45']);
        if(strlen($zoom->_CONFIG['safemodeversion']) > 0){
        	$s46 = mysql_escape_string($_REQUEST['s46']);
        }else{
        	$s46 = 0;
        }
        // variables s47 till s49 are in use by the ftp feature and handled separately.
        $s50 = mysql_escape_string($_REQUEST['s50']);
        $s51 = mysql_escape_string($_REQUEST['s51']);
        // variable s52 is in use by the ftp feature and handled seperately.
        $s53 = mysql_escape_string($_REQUEST['s53']);
        $s54 = mysql_escape_string($_REQUEST['s54']);
        $s55 = mysql_escape_string($_REQUEST['s55']);
        $s56 = mysql_escape_string($_REQUEST['s56']);
        $s57 = mysql_escape_string($_REQUEST['s57']);
        // mp3 configuration variable...
        $s58 = mysql_escape_string($_REQUEST['s58']); 
		if(!isset($s29) || empty($s29))
			$s29 = 0;
		// the representation and meaning of each s-variable explains itself
		// in the following sql-statement:
		$cfg_content = "<?php\n";
	    $cfg_content .= "defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );\n";
	    $cfg_content .= "\$zoomConfig['conversiontype'] = {$s01};\n";
	    $cfg_content .= "\$zoomConfig['zoom_title'] = \"{$s28}\";\n";
		$cfg_content .= "\$zoomConfig['imagepath'] = \"{$s02}\";\n";
		$cfg_content .= "\$zoomConfig['IM_path'] = \"{$s03}\";\n";
		$cfg_content .= "\$zoomConfig['NETPBM_path'] = \"{$s04}\";\n";
		$cfg_content .= "\$zoomConfig['FFMPEG_path'] = \"{$s36}\";\n";
		$cfg_content .= "\$zoomConfig['PDF_path'] = \"{$s45}\";\n";
		$cfg_content .= "\$zoomConfig['JPEGquality'] = {$s05};\n";
		$cfg_content .= "\$zoomConfig['maxsize'] = {$s26};\n";
		$cfg_content .= "\$zoomConfig['size'] = {$s06};\n";
		$cfg_content .= "\$zoomConfig['columnsno'] = {$s07};\n";
		$cfg_content .= "\$zoomConfig['PageSize'] = {$s08};\n";
		$cfg_content .= "\$zoomConfig['orderMethod'] = {$s24};\n";
		$cfg_content .= "\$zoomConfig['catOrderMethod'] = {$s51};\n";
		$cfg_content .= "\$zoomConfig['commentsOn'] = {$s09};\n";
		$cfg_content .= "\$zoomConfig['cmtLength'] = {$s44};\n";
		$cfg_content .= "\$zoomConfig['galleryPrefix'] = \"{$s50}\";\n";
		$cfg_content .= "\$zoomConfig['ratingOn'] = {$s17};\n";
		$cfg_content .= "\$zoomConfig['zoomOn'] = {$s19};\n";
		$cfg_content .= "\$zoomConfig['popUpImages'] = {$s10};\n";
		$cfg_content .= "\$zoomConfig['catImg'] = {$s11};\n";
		$cfg_content .= "\$zoomConfig['slideshow'] = {$s12};\n";
		$cfg_content .= "\$zoomConfig['displaylogo'] = {$s13};\n";
		$cfg_content .= "\$zoomConfig['allowUserUpload'] = {$s15};\n";
		$cfg_content .= "\$zoomConfig['readEXIF'] = {$s14};\n";
		$cfg_content .= "\$zoomConfig['readID3'] = {$s58};\n";
		$cfg_content .= "\$zoomConfig['tempDescr'] = \"{$s16}\";\n";
		$cfg_content .= "\$zoomConfig['tempName'] = \"{$s20}\";\n";
		$cfg_content .= "\$zoomConfig['autonumber'] = {$s21};\n";
		$cfg_content .= "\$zoomConfig['showHits'] = {$s22};\n";
        $cfg_content .= "\$zoomConfig['showName'] = {$s38};\n";
        $cfg_content .= "\$zoomConfig['showDescr'] = {$s39};\n";
        $cfg_content .= "\$zoomConfig['showKeywords'] = {$s40};\n";
        $cfg_content .= "\$zoomConfig['showDate'] = {$s41};\n";
        $cfg_content .= "\$zoomConfig['showFilename'] = {$s42};\n";
		$cfg_content .= "\$zoomConfig['showSearch'] = {$s37};\n";
		$cfg_content .= "\$zoomConfig['showMetaBox'] = {$s43};\n";
		$cfg_content .= "\$zoomConfig['catcolsno'] = {$s23};\n";
		$cfg_content .= "\$zoomConfig['utype'] = \"{$s27}\";\n";
		$cfg_content .= "\$zoomConfig['lightbox'] = {$s25};\n";
		$cfg_content .= "\$zoomConfig['ecards'] = {$s34};\n";
		$cfg_content .= "\$zoomConfig['ecards_lifetime'] = {$s35};\n";
		$cfg_content .= "\$zoomConfig['zoomModule'] = {$s29};\n";
		$cfg_content .= "\$zoomConfig['mod_showcount'] = {$s53};\n";
		$cfg_content .= "\$zoomConfig['mod_showupdate'] = {$s54};\n";
		$cfg_content .= "\$zoomConfig['mod_showcatnames'] = {$s55};\n";
		$cfg_content .= "\$zoomConfig['mod_showmeth'] = {$s56};\n";
		$cfg_content .= "\$zoomConfig['mod_dateformat'] = \"{$s57}\";\n";
		$cfg_content .= "\$zoomConfig['allowUserCreate'] = {$s30};\n";
		$cfg_content .= "\$zoomConfig['allowUserDel'] = {$s31};\n";
		$cfg_content .= "\$zoomConfig['allowUserEdit'] = {$s32};\n";
		$cfg_content .= "\$zoomConfig['safemodeON'] = {$s46};\n";
		$cfg_content .= "\$zoomConfig['version'] = \"{$this->_CONFIG['version']}\";\n";
		$cfg_content .= "\$zoomConfig['safemodeversion'] = \"{$this->_CONFIG['safemodeversion']}\";\n";
		$cfg_content .= "?>";
	    $cfg_file = $mosConfig_absolute_path.'/components/com_zoom/zoom_config.php';
		@chmod ($cfg_file, 0766);
		$permission = is_writable($cfg_file);
		if (!$permission) {
			echo "Error: zOOm Configuration file ".$cfg_file." is not writable!";
			exit();
		}
		$this->writefile($cfg_file, $cfg_content);
		// now save the usermenu-item link, if the s33 was checked or delete it otherwise...
		if($s33 == 1 && !$this->issetUserMenu()){
			// all ok, insert menu-option...
			$database->setQuery("INSERT INTO #__menu (`id`,`menutype`,`name`,`link`,`type`,`published`,`parent`,`componentid`,`sublevel`,`ordering`,`checked_out`,`checked_out_time`,`pollid`,`browserNav`,`access`,`utaccess`,`params`) VALUES ('','usermenu','Upload Media','index.php?option=com_zoom&page=admin','url','1','0','0','0','0','0','0000-00-00 00:00:00','0','0','1','2','')");
			$database->query();
		}elseif($s33 == 0 && $theId = $this->issetUserMenu()){
			$database->setQuery("DELETE FROM #__menu WHERE id = ".$theId);
			$database->query();
		}
		return true;
	}
	// this function will be moved to the image.class.php (doesn't belong here anymore...)
	function saveImage($filename, $keywords, $name, $descr, $catid){
		global $database;
		$uid = $this->_CurrUID;
		$database->setQuery("INSERT INTO #__zoomfiles (imgfilename,imgname, imgkeywords, imgdescr, imgdate, catid, uid, imgmembers) VALUES ('".mysql_escape_string($filename)."', '".mysql_escape_string($name)."', '".mysql_escape_string($keywords)."','".mysql_escape_string($descr)."', now(), '".mysql_escape_string($catid)."', '$uid', '1')");
		if ($database->query()) {
			return true;
		}else{
			return false;
		}
	}
	function optimizeTables(){
		global $database;
		$database->setQuery("OPTIMIZE TABLE `#__zoom`");
		$database->query();
		$database->setQuery("OPTIMIZE TABLE `#__zoomfiles`");
		$database->query();
		$database->setQuery("OPTIMIZE TABLE `#__zoom_comments`");
		$database->query();
		$database->setQuery("OPTIMIZE TABLE `#__zoom_editmon`");
		$database->query();
	}
	//--------------------END Database Editing Functions-------------------//
	//--------------------Database Querying Functions----------------------//
	function getSmiliesTable(){
		//gentle solution to avoid the use of the pompous smilies-table
		//from the authors of phpBB...
		return array(
			array(':!:', 'icon_exclaim.gif', 'Exclamation'),
			array(':?:', 'icon_question.gif', 'Question'),
			array(':D', 'icon_biggrin.gif', 'Very Happy'),
			array(':d', 'icon_biggrin.gif', 'Very Happy'),
			array(':-D', 'icon_biggrin.gif', 'Very Happy'),
			array(':grin:', 'icon_biggrin.gif', 'Very Happy'),
			array(':)', 'icon_smile.gif', 'Smile'),
			array(':-)', 'icon_smile.gif', 'Smile'),
			array(':smile:', 'icon_smile.gif', 'Smile'),
			array(':(', 'icon_sad.gif', 'Sad'),
			array(':-(', 'icon_sad.gif', 'Sad'),
			array(':sad:', 'icon_sad.gif', 'Sad'),
			array(':o', 'icon_surprised.gif', 'Surprised'),
			array(':-o', 'icon_surprised.gif', 'Surprised'),
			array(':eek:', 'icon_surprised.gif', 'Surprised'),
			array(':shock:', 'icon_eek.gif', 'Shocked'),
			array(':?', 'icon_confused.gif', 'Confused'),
			array(':-?', 'icon_confused.gif', 'Confused'),
			array(':???:', 'icon_confused.gif', 'Confused'),
			array('8)', 'icon_cool.gif', 'Cool'),
			array('8-)', 'icon_cool.gif', 'Cool'),
			array(':cool:', 'icon_cool.gif', 'Cool'),
			array(':lol:', 'icon_lol.gif', 'Laughing'),
			array(':x', 'icon_mad.gif', 'Mad'),
			array(':-x', 'icon_mad.gif', 'Mad'),
			array(':mad:', 'icon_mad.gif', 'Mad'),
			array(':P', 'icon_razz.gif', 'Razz'),
			array(':p', 'icon_razz.gif', 'Razz'),
			array(':-P', 'icon_razz.gif', 'Razz'),
			array(':razz:', 'icon_razz.gif', 'Razz'),
			array(':oops:', 'icon_redface.gif', 'Embarassed'),
			array(':cry:', 'icon_cry.gif', 'Crying or Very sad'),
			array(':evil:', 'icon_evil.gif', 'Evil or Very Mad'),
			array(':twisted:', 'icon_twisted.gif', 'Twisted Evil'),
			array(':roll:', 'icon_rolleyes.gif', 'Rolling Eyes'),
			array(':wink:', 'icon_wink.gif', 'Wink'),
			array(';)', 'icon_wink.gif', 'Wink'),
			array(';-)', 'icon_wink.gif', 'Wink'),
			array(':idea:', 'icon_idea.gif', 'Idea'),
			array(':arrow:', 'icon_arrow.gif', 'Arrow'),
			array(':|', 'icon_neutral.gif', 'Neutral'),
			array(':-|', 'icon_neutral.gif', 'Neutral'),
			array(':neutral:', 'icon_neutral.gif', 'Neutral'),
			array(':mrgreen:', 'icon_mrgreen.gif', 'Mr. Green')
		);
	}
	function getCatList($parent, $ident='', $ident2=''){
		global $database;
		// The author of Coppermine Gallery inspired me for this piece of code.
		// Main trick is the use of recursion. For every sub-category (or each level,
		// or each value of pos) the entire method is called again. And so on...and so on...
		$database->setQuery("SELECT catid, catname, published, shared, uid FROM #__zoom WHERE subcat_id=$parent ORDER BY pos");
		$this->_result = $database->query();
		$rowset = Array();
		while($row = mysql_fetch_array($this->_result))
			$rowset[] = $row;
		foreach($rowset as $subcat){
			if(!$this->_isAdmin){
				if(($subcat['uid'] == $this->_CurrUID) || ($subcat['shared'] == 1)){
					$this->_CAT_LIST[] = array(
						'id' => $subcat['catid'],
						'catname' => $ident.$subcat['catname'],
						'published' => $subcat['published'],
						'shared' => $subcat['shared'],
						'uid' => $subcat['uid'],
						'virtpath' => $ident2.$subcat['catname']);
				}
			}else{
				$this->_CAT_LIST[] = array(
					'id' => $subcat['catid'],
					'catname' => $ident.$subcat['catname'],
					'published' => $subcat['published'],
					'shared' => $subcat['shared'],
					'uid' => $subcat['uid'],
					'virtpath' => $ident2.$subcat['catname']);					
			}
			$this->getCatList($subcat['catid'], $ident.'>&nbsp;', $ident2.$subcat['catname'].'>&nbsp;');
		}
	}
	function getKeywordsList(){
		global $database;
		$database->setQuery("SELECT cat.catkeywords AS catkeywords, img.imgkeywords AS imgkeywords FROM #__zoom AS cat, #__zoomfiles AS img WHERE (cat.published = 1 OR img.published = 1) AND (cat.catkeywords <> '' OR img.imgkeywords <> '')");
		if ($this->_result = $database->query()) {
			$keywords = array();
			$newkeys = array();
			$allkeys = array();
			$catrow = array();
			$imgrow = array();
			// first, put the keywords from both columns into arrays...
			while($row = mysql_fetch_object($this->_result)){
				$catrow[] = (!empty($row->catkeywords)) ? $row->catkeywords : "";
				$imgrow[] = (!empty($row->imgkeywords)) ? $row->imgkeywords : "";
			}
			// combine those two arrays...
			$allkeys = array_merge($catrow, $imgrow);
			// now, delete empty rows...
			$this->_counter = 0;
			foreach ($allkeys as $akey){
				if(!empty($akey))
					$newkeys[] = $akey;
			}
			// then, get each individual keyword and put it into the array '$keywords'
			foreach ($newkeys as $newkey){
				$temp = explode(",", $newkey);
				if(is_array($temp)){
					foreach ($temp as $t){
						if(!empty($t)){
							$keywords[] = $t;
						}
					}
				}
			}
			// remove duplicate keywords...
			$keywords = array_unique($keywords);
			sort($keywords);
			return $keywords;
		}
	}
	function getItemid($option){
		global $database;
		$database->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=".mysql_escape_string($option)."'");
		if ($this->_result = $database->query()) {
			$row = mysql_fetch_object($this->_result);
			return $row->id;
		}
		
	}
	function getOrderMethod(){
		switch ($this->_CONFIG['orderMethod']){
			case 1:
				return "imgname ASC";
				break;
			case 2:
				return "imgname DESC";
				break;
			case 3:
				return "imgfilename ASC";
				break;
			case 4:
				return "imgfilename DESC";
				break;
			case 5:
				return "imgdate ASC";
				break;
			case 6:
				return "imgdate DESC";
				break;
		}
	}
	function getCatOrderMethod(){
		// manual gallery ordering will be added later on...
		switch ($this->_CONFIG['catOrderMethod']){
			case 1:
				return "catid ASC";
				break;
			case 2:
				return "catid DESC";
				break;
			case 3:
				return "catname ASC";
				break;
			case 4:
				return "catname DESC";
				break;
		}
	}
	function setGallery($gallery_id, $galleryview = false){
		$this->_gallery = null;
		$this->_gallery = new gallery($gallery_id, $galleryview);
	}
	function setEcard($id = 0){
		$this->_ecard = null;
		$this->_ecard = new ecard($id);
	}
	function issetUserMenu(){
		global $database;
		$database->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_zoom&page=admin' LIMIT 1");
		if ($this->_result = $database->query()) {
			if(mysql_num_rows($this->_result) > 0){
				$row = mysql_fetch_object($this->_result);
				return $row->id;
			}else{
				return false;
			}
		}
		
	}
	function getUserInfo($userid){
		global $database;
		$database->setQuery("SELECT name, username, email, usertype, registerDate, lastvisitDate FROM #__users WHERE id=$userid LIMIT 1");
		if($result = $database->query()){
			return mysql_fetch_object($result);
		}else{
			return false;
		}
	}
	function checkDuplicate($checkThis, $checkWhat = 'filename'){
		global $database;
		// There are two things this function can check for:
		// - duplicate filenames
		// - duplpicate directories (of galleries)
		if ($checkWhat === "directory") {
			$database->setQuery("SELECT catid FROM #__zoom WHERE catdir = '$checkThis'");
			if($this->_result = $database->query()){
				if(mysql_num_rows($this->_result) > 0){
					$newname = $this->newdir();
					$this->checkDuplicate($newname, 'directory');
				}else{
					$this->_tempname = $checkThis;
				}
			}else{
				$this->_tempname = $checkThis;
			}
		}else{
			$database->setQuery("SELECT imgid FROM #__zoomfiles WHERE imgfilename = '$checkThis'");
			if($this->_result = $database->query()){
				if(mysql_num_rows($this->_result) > 0){
					// filename exists already in the database, so change the filename and test again...
					// the filename will be changed accordingly:
					// if a filename exists, add the suffix _{number} incrementally,
					// thus 'afile_1.jpg' will become 'afile_2.jpg' and so on...
					$newname = preg_replace( "/^(.+?)(_?)(\d*)(\.[^.]+)?$/e", "'\$1_'.(\$3+1).'\$4'", $checkThis );
					$this->checkDuplicate($newname);
				}else{
					$this->_tempname = $checkThis;
				}
			}else{
				 $this->_tempname = $checkThis;
			}
		}
		
	}
	//--------------------END Database Querying Functions------------------// 
	//--------------------HTML content-creation functions------------------//
	function createSlideshow($key){
		global $mosConfig_live_site;
		?>
		<script language="JavaScript" type="text/JavaScript">
		// (C) 2000 www.CodeLifter.com
		// http://www.codelifter.com
		// Free for all users, but leave in this  header
		// NS4-6,IE4-6
		// Fade effect only in IE; degrades gracefully
		var stopstatus = 0
		
		// Set slideShowSpeed (milliseconds)
		var slideShowSpeed = 5000
		
		// Duration of crossfade (seconds)
		var crossFadeDuration = 3
		
		// Specify the image files
		var Pic = new Array() // don't touch this
		// to add more images, just continue
		// the pattern, adding to the array below
		<?php
  		$i = 0;
  		$j = 0;
  		while ($i<count($this->_gallery->_images)) {
  			$this->_gallery->_images[$i]->getInfo();
  			if($this->isImage($this->_gallery->_images[$i]->_type)){
				echo "Pic[$i] = '".$mosConfig_live_site."/".$this->_CONFIG['imagepath'].$this->_gallery->_dir."/".$this->_gallery->_images[$i]->_viewsize."'\n\t\t";
  			}
			if ($i == $key){
	  			$j = $i;
			}
  		$i++;
  		}
		?>
		
		var t
		var j = <?php echo "$j\n" ?>
		var keyPic = '<?php echo $mosConfig_live_site."/".$this->_CONFIG['imagepath'].$this->_gallery->_dir."/".$this->_gallery->_images[$key]->_viewsize."'\n";?>
		var p = Pic.length
		var pos = j
		var preLoad = new Array()
		
		function preLoadPic(index){
  			if (Pic[index] != ''){
				window.status='Loading : '+Pic[index]
				preLoad[index] = new Image()
				preLoad[index].src = Pic[index]
				Pic[index] = ''
				window.status=''
  			}
		}
		
		function runSlideShow(){
	  		if (stopstatus != '1'){
				if (document.all){
	  				document.images.zImage.style.filter="blendTrans(duration=2)"
	  				document.images.zImage.style.filter= "blendTrans(duration=crossFadeDuration)"
	      			document.images.zImage.filters.blendTrans.Apply()
				}
				document.images.zImage.src = preLoad[j].src
				if (document.all){
	  				document.images.zImage.filters.blendTrans.Play()
				}
				pos = j
				j = j + 1
				if (j > (p-1)) j=0
				t = setTimeout('runSlideShow()', slideShowSpeed)
				preLoadPic(j)
  			}
		}

		function endSlideShow(){
  			stopstatus = 1
  			document.images.zImage.src = keyPic
		}

		preLoadPic(j)
		
		</script>
		<?php
	}
	function createZoomJavascript($size){
		?>
		<script language="JavaScript" type="text/JavaScript">
		<!--
		// Zoom-in and -out script for zOOm Image Gallery
		// version 1.0
		// All functions: Copyright (C) 2003, Mike de Boer, MikedeBoer.nl Software
		// This software is licensed according to the GPL
		// Leave this copyright untouched!
		
		var zoomed = 0; // keeps track of how many times the user zoomed in or out (up to 4 times)
		var scale = 1.5;     // factor to zoom by
		
		function zoomIn() {
			if (zoomed == 0){
				imReset();
			}
			if (zoomed != 4){
	  		document.images.zImage.width = document.images.zImage.width * scale;
	  		document.images.zImage.height = document.images.zImage.height * scale;
	  		zoomed = zoomed+1;
			}
		}
		
		function zoomOut() {
			if (zoomed == 0){
				imReset();
			}
			if (zoomed != -4){
	  		document.images.zImage.width = document.images.zImage.width / scale;
	  		document.images.zImage.height = document.images.zImage.height / scale;
	  		zoomed = zoomed-1;
			}
		}
		
		function imReset(){
			document.images.zImage.width = <?php echo $size[0];?>;
			document.images.zImage.height = <?php echo $size[1];?>;
			zoomed = 0;
		}
		// -->
		</script>
		<?php
	}
	function createSubmitScript($formname){
		?>
		<script language="JavaScript" type="text/JavaScript">
		<!--
		function reloadPage() {
			document.<?php echo $formname;?>.submit();
			return false;
		}
		// -->
		</script>
		<?php
	}
	function createCheckAllScript(){
		?>
		<script language="JavaScript" type="text/JavaScript">
		<!--
		function checkUncheckAll(oCheckbox, sName)
			{
			var el, i = 0, bWhich = oCheckbox.checked, oForm = oCheckbox.form;
			while (el = oForm[i++]) 
				if (el.type == 'checkbox' && el.name == sName) el.checked = bWhich;
			}
		// -->
		</script>
		<?php
	}
	function createFormControlScript($formname){
		?>
		<script language="JavaScript" type="text/JavaScript">
		<!--
		var disabled = false;
		
		function disable(theForm ,elmnt) {
			document.forms[theForm].elements[elmnt].disabled = true;
			disabled = true;
		}
		function enable(elmnt) {
			document.forms[theForm].elements[elmnt].disabled = false;
			disabled = false;
		}
		function toggleDisabled(elmnt) {
			if (disabled == true) {
				enable(elmnt);
			} else {
				disable(elmnt);
			}
		}
		// -->
		</script>
		<?php
	}
	function adminFooter(){
		?>
		<p align="center">
			<b>zOOm Media Gallery <?php echo $this->_CONFIG['version'];?></b><br />Copyright &copy; 2004 by Mike de Boer.
			<br />&copy; 2004 FOOOD's Icons. All rights reserved. COMMERCIAL! Visit him at <a href="http://foood.net" target="blank">Foood.net</a>
		</p>
		<?php
	}
	function createCatDropdown($sel_name = 'catid', $first_opt, $onchange=0, $sel=0){
		if ($onchange==0){
			$html = '<select name="'.$sel_name.'" class="inputbox">';
		}elseif ($onchange==1){
			$html = '<select name="'.$sel_name.'" class="inputbox" onchange="reloadPage()">';
		}
		$html .= $first_opt;
		// NOW, I'm going to offer the users infinite level of navigation and gallery-creation;
		// check the function 'getCatList()' for more info...code inspired by Coppermine.
		$this->_CAT_LIST = null;
		$this->getCatList(0, '>&nbsp;', '>&nbsp;');
		if(isset($this->_CAT_LIST)){
			foreach($this->_CAT_LIST as $category){
		 		$html.= '<option value="'.$category['id'].'"'.($sel == $category['id'] ? ' selected': '').">".$category['catname']."</option>\n";
			}
		}
		return $html.'</select>';
	}
	function createKeywordsDropdown($sel_name, $first_opt, $onchange=0, $sel=0){
		if ($onchange==0){
			$html = "<select name=\"".$sel_name."\" class=\"inputbox\">\n";
		}elseif ($onchange==1){
			$html = "<select name=\"".$sel_name."\" class=\"inputbox\" onchange=\"reloadPage()\">\n";
		}
		$html .= $first_opt;
		$keywords = $this->getKeywordsList();
		if(isset($keywords)){
			foreach($keywords as $keyword){
		 		$html.= "<option value=\"".$keyword."\">".$keyword."</option>\n";
			}
		}
		return $html."</select>\n";
	}
	function createCatDeleteFormbody(){
		// This function creates the table of edit.php...it uses the 'virtpath'-column
		// of the internal CAT_LIST variable. Check the 'getCatList()' function for more details...
		global $Itemid, $mosConfig_live_site;
		$html = '';
		$this->_CAT_LIST = null;
		$this->getCatList(0, '>&nbsp;', '>&nbsp;');
		$i = 0;
		$html .= ("\n\t<table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">\n"
		 . "\t<tr>\n"
		 . "\t\t<td height=\"20\" width=\"50\" class=\"sectiontableheader\">&nbsp;</td>\n"
		 . "\t\t<td class=\"sectiontableheader\">"._ZOOM_HD_NAME."</td>\n"
		 . "\t\t<td class=\"sectiontableheader\">"._ZOOM_PUBLISHED."</td>\n"
		 . "\t\t<td class=\"sectiontableheader\">"._ZOOM_SHARED."</td>\n"
		 . "\t\t<td class=\"sectiontableheader\">"._ZOOM_HD_CREATEDBY."</td>\n"
		 . "\t</tr>");
		if(isset($this->_CAT_LIST)){
			foreach($this->_CAT_LIST as $category){
				$i++;
				$bgcolor = ($i & 1) ? $$this->_tabclass[1] : $this->_tabclass[0];
				$edit_link = "index";
				if($this->_isBackend){
					$edit_link .= "2";
				}
				$edit_link .= ".php?option=com_zoom&page=catsmgr&task=edit&catid=".$category['id']."&Itemid=".$Itemid;
		 		$html .= ("\n\t<tr class=\"".$bgcolor."\">\n"
		 		 . "\t\t<td><input type=\"checkbox\" name=\"catid[]\" value=\"".$category['id']."\"></td>\n"
		 		 . "\t\t<td><a href=\"".$edit_link."\">".$category['virtpath']."</a></td>\n"
		 		 . "\t\t<td width=\"20\" align=\"center\"><a href=\"javascript:submitForm('publish', ".$category['id'].");\"><img src=\"".$mosConfig_live_site."/components/com_zoom/images/");
		 		// special cells with published, shared and userid info...
		 		$html .= ($category['published']) ? "publish_g.png" : "publish_x.png";
		 		$html .= ("\" border=\"0\" /></a></td>\n"
		 		 . "\t\t<td width=\"20\" align=\"center\"><a href=\"javascript:submitForm('share', ".$category['id'].");\"><img src=\"".$mosConfig_live_site."/components/com_zoom/images/");
		 		$html .= ($category['shared']) ? "share_u.png" : "share_l.png";
		 		$cat_user = $this->getUserInfo($category['uid']);
		 		$html .= ("\" border=\"0\" /></a></td>\n"
		 		 . "\t\t<td width=\"40\" align=\"center\">".$cat_user->username."</td>\n"
		 		 . "\t</tr>\n");
		 	}
		}
		$html .= ("\n\t<tr>"
		 . "\t\t<td height=\"20\" class=\"sectiontableheader\"><input type=\"checkbox\" name=\"checkall\" onclick=\"checkUncheckAll(this, 'catid[]');\"></td>\n"
		 . "\t\t<td height=\"20\" class=\"sectiontableheader\" colspan=\"4\"><strong>"._ZOOM_HD_CHECKALL."</strong></td>\n"
		 . "\t</tr>\n"
		 . "\t</table>\n");
		return $html;
	}
	function createMediaEditForm(){
		global $Itemid;
		$this->_counter = 0;
		global $mosConfig_live_site, $mosConfig_absolute_path;
		$this->createCheckAllScript();
		$tabcnt = 0;
		$this->_counter = 0;
		echo ("<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"
		 . "\t\t\t<tr class=\"sectiontableheader\">\n"
		 . "\t\t\t\t<td width=\"50\" class=\"sectiontableheader\">&nbsp;</td>\n"
		 . "\t\t\t\t<td class=\"sectiontableheader\">"._ZOOM_NAME."</td>\n"
		 . "\t\t\t\t<td class=\"sectiontableheader\">"._ZOOM_FILENAME."</td>\n"
		 . "\t\t\t\t<td class=\"sectiontableheader\">"._ZOOM_HD_PREVIEW."</td>\n"
		 . "\t\t\t</tr>\n");
		 foreach($this->_gallery->_images as $image){
		 	$image->getInfo();
			if($tabcnt > 1)
				$tabcnt = 0;
			$tag = ereg_replace(".*\.([^\.]*)$", "\\1", $image->_filename);
			$edit_link = "index";
			if ($this->_isBackend) {
				$edit_link .= "2";
			}
			$edit_link .= ".php?option=com_zoom&page=mediamgr&task=edit&catid=".$image->_catid."&keys=".$this->_counter."&Itemid=".$Itemid;
			echo ("\t\t\t<tr class=\"".$this->_tabclass[$tabcnt]."\">\n"
			 . "\t\t\t\t<td align=\"center\" width=\"10\"><input type=\"checkbox\" name=\"keys[]\" value=\"".$this->_counter."\"></td>\n"
			 . "\t\t\t\t<td><a href=\"".$edit_link."\">".$image->_name."</a><br /></td>\n"
			 . "\t\t\t\t<td>".$image->_filename."<br />\n"
			 . "\t\t\t\t</td>\n"
			 . "\t\t\t\t<td><img src=\"".$image->_thumbnail."\" border=\"0\" /></td>\n"
			 . "\t\t\t</tr>\n");
			$tabcnt++ ;
			$this->_counter++;
		}
		echo ("\t\t\t<tr>\n"
		 . "\t\t\t\t<td height=\"20\" class=\"sectiontableheader\" align=\"center\"><input type=\"checkbox\" name=\"checkall\" onclick=\"checkUncheckAll(this, 'keys[]');\"></td>\n"
		 . "\t\t\t\t<td height=\"20\" class=\"sectiontableheader\" colspan=\"3\"><strong>"._ZOOM_HD_CHECKALL."</strong>\n"
		 . "\t\t\t\t</td>\n"
		 . "\t\t\t</tr>\n"
		 . "\t\t\t</table>\n");
		 
	}
	function createFileChooseForm($userfile, $userfile_name){
		global $Itemid;
		$this->_counter = 0;
		$html = '<form name="filepick" method="post" action="index.php?option=com_zoom$Itemid='.$Itemid.'&page=upload&formtype=scan">';
		$html .= '<table border="0" cellspacing="0" cellpadding="3">';
		$html .= '<tr class="sectiontableheader"><td height="20">&nbsp;</td><td height="20">'._ZOOM_FILENAME.'</td>';
		foreach ($userfile as $file){
			$html.= '<tr><td><input type="checkbox" name="userfile[]" value="'.$file.'"></td><td>'.$file.'</td></tr>';
			$html .= '<input type="hidden" name="userfile_name[]" value="'.$userfile_name[$this->_counter].'">';
			$this->_counter++;
		}
		$html.= '<td height="20" class="sectiontableheader"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"></td><td  height="20" class="sectiontableheader"><strong>'._ZOOM_HD_CHECKALL.'</strong></td>';
		$html .= '</table></form>';
		return $html;
	}
	function createACLgroupList(){
		global $database;
		$database->setQuery("SELECT lft, name FROM #__core_acl_aro_groups WHERE lft < 13 AND lft > 3 ORDER BY lft");
		$this->_result = $database->query();
		$html = '<select name="s27" size="'.mysql_num_rows($this->_result).'" class="inputbox">';
		$ident = '>';
		while($row = mysql_fetch_object($this->_result)){
			$html .= '<option value="'.$row->lft.'"';
			if($this->_CONFIG['utype'] == $row->lft)
			 	$html .= ' selected>|'.$ident.'&nbsp;'.$row->name.'</option>';
			else
				$html .= '>|'.$ident.'&nbsp;'.$row->name.'</option>';
			$ident = '-'.$ident;
		}
		$html .= '</select>';
		return $html;
	}
	function createFileList(&$imagelist, $extractloc = ""){
		global $mosConfig_live_site, $mosConfig_absolute_path;
		$this->createCheckAllScript();
		$tabcnt = 0;
		$this->_counter = 0;
		echo ("<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\" width=\"95%\">\n"
		 . "\t\t\t<tr class=\"sectiontableheader\">\n"
		 . "\t\t\t\t<td width=\"50\" class=\"sectiontableheader\">&nbsp;</td>\n"
		 . "\t\t\t\t<td class=\"sectiontableheader\">"._ZOOM_FILENAME."</td>\n"
		 . "\t\t\t\t<td class=\"sectiontableheader\">"._ZOOM_HD_PREVIEW."</td>\n"
		 . "\t\t\t</tr>\n");
		foreach($imagelist as $image){
			if($tabcnt > 1)
				$tabcnt = 0;
			$tag = ereg_replace(".*\.([^\.]*)$", "\\1", $image);
			if ($this->isImage($tag)) {
				if (!fs_is_file($image)) {
					$image_path = $mosConfig_absolute_path."/".$extractloc."/".$image;
					$image_virt = $mosConfig_live_site."/".$extractloc."/".$image;
					$imginfo = getimagesize($extractloc."/".$image);
					$ratio = max($imginfo[0], $imginfo[1]) / $this->_CONFIG['size'];
					$ratio = max($ratio, 1.0);
					$imgWidth = (int)($imginfo[0] / $ratio);
					$imgHeight = (int)($imginfo[1] / $ratio);
				}else{
					$image_path = $image;
					$image_virt = $image_path;
					$imginfo = getimagesize($image_virt);
					$ratio = max($imginfo[0], $imginfo[1]) / $this->_CONFIG['size'];
					$ratio = max($ratio, 1.0);
					$imgWidth = (int)($imginfo[0] / $ratio);
					$imgHeight = (int)($imginfo[1] / $ratio);
				}
			}
			echo ("\t\t\t<tr class=\"".$this->_tabclass[$tabcnt]."\">\n"
			 . "\t\t\t\t<td align=\"center\" width=\"10\"><input type=\"checkbox\" name=\"scannedimg[]\" value=\"".$this->_counter."\" checked></td>\n"
			 . "\t\t\t\t<td width=\"100%\">".$image."<br />\n");
			if ($this->isImage($tag)) {
				echo ("\t\t\t\t\t<input type=\"checkbox\" name=\"rotate[]\" value=\"1\">"._ZOOM_ROTATE."$nbsp;\n"
				 . "\t\t\t\t\t<input type=\"radio\" name=\"rotate".$this->_counter."\" value=\"90\">"._ZOOM_CLOCKWISE."\n"
				 . "\t\t\t\t\t<input type=\"radio\" name=\"rotate".$this->_counter."\" value=\"-90\">"._ZOOM_CCLOCKWISE."\n"
				 . "\t\t\t\t</td>\n"
				 . "\t\t\t\t<td><img src=\"".$image_virt."\" border=\"0\" width=\"".$imgWidth."\" height=\"".$imgHeight."\"></td>\n"
				 . "\t\t\t</tr>\n");
			}else{
				echo ("\t\t\t\t</td>\n"
				 . "\t\t\t\t<td>&nbsp;</td>\n"
				 . "\t\t\t</tr>\n");
			}
			$tabcnt++ ;
			$this->_counter++;
		}
		echo ("\t\t\t<tr>\n"
		 . "\t\t\t\t<td height=\"20\" class=\"sectiontableheader\" align=\"center\"><input type=\"checkbox\" name=\"checkall\" onclick=\"checkUncheckAll(this, 'scannedimg[]');\" checked></td>\n"
		 . "\t\t\t\t<td height=\"20\" class=\"sectiontableheader\" colspan=\"2\"><strong>"._ZOOM_HD_CHECKALL."</strong>\n"
		 . "\t\t\t\t</td>\n"
		 . "\t\t\t</tr>\n"
		 . "\t\t\t</table>\n");
	}
	function highlight($it, $text){
		$replacement = '<font color="red">'.quotemeta($it).'</font>';
		return eregi_replace($it, $replacement, $text);
	}
	//--------------------END content-creation functions-------------------//
}

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
| Filename: image.class.php                                           |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class image extends gallery{
	var $_id = null;
	var $_name = null;
    var $_keywords = null;
	var $_filename = null;
	var $_descr = null;
	var $_date = null;
	var $_hits = null;
	var $_votenum = null;
	var $_votesum = null;
	var $_type = null;
	var $_thumbnail = null;
	var $_viewsize = null;
	var $_published = null;
	var $_catid = null;
	var $_uid = null;
	var $_sql = null;
	var $_result = null;
	var $_size = array();
	var $_comments = array();
    var $_members = array();
	
	function image($image_id){
		$this->_id = $image_id;
	}
	function getInfo(){
		global $database, $mosConfig_absolute_path, $zoom;
		$database->setQuery("SELECT imgname, imgkeywords, imgfilename, imgdescr, date_format(imgdate, '%d-%m-%y, %h:%i') AS date, imghits, votenum, votesum, published, catid, uid, imgmembers FROM #__zoomfiles WHERE imgid=".mysql_escape_string($this->_id));
		$this->_result = $database->query();
		while($row = mysql_fetch_object($this->_result)){
			$this->_name = stripslashes($row->imgname);
            $this->_keywords = stripslashes($row->imgkeywords);
			$this->_filename = $row->imgfilename;
			$this->_descr = stripslashes($row->imgdescr);
			$this->_date = $row->date;
			$this->_hits = $row->imghits;
			$this->_votenum = $row->votenum;
			$this->_votesum = $row->votesum;
			$this->_published = $row->published;
			$this->_catid = $row->catid;
			$this->_uid = $row->uid;
            $members = explode(",", $row->imgmembers);
            // gallery-members of type 1 are of access-level 'public'
            // and members of type 2 are 'registered'.
            if(in_array("1", $members))
                $this->_members[0] = 1;
            elseif(in_array("2", $members))
                $this->_members[0] = 2;
            else
                $this->_members = $members;
		}
		$this->_type = ereg_replace(".*\.([^\.]*)$", "\\1", $this->_filename);
		$this->_type = strtolower($this->_type);
		$this->setThumbnail();
		$this->_viewsize = $this->getViewsize();
		// get comments of image...
		$this->getComments();
		//$this->_size = getimagesize($zoom->_CONFIG['imagepath'].$dir."/".$this->_filename);
	}
	// function save() is to be implemented yet...
	function save($filename, $keywords, $name, $descr, $catid){
		global $database;
		$uid = $this->_CurrUID;
		$database->setQuery("INSERT INTO #__zoomfiles (imgfilename,imgname, imgkeywords, imgdescr, imgdate, catid, uid, imgmembers) VALUES ('".mysql_escape_string($filename)."', '".mysql_escape_string($name)."', '".mysql_escape_string($keywords)."','".mysql_escape_string($descr)."', now(), '".mysql_escape_string($catid)."', '$uid', '1')");
		if ($database->query()) {
			return true;
		}else{
			return false;
		}
	}
	function delete(){
		global $database, $mosConfig_absolute_path, $zoom;
		$file1 = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$this->_filename;
		if($zoom->isMovie($zoom->_gallery->_images[$key]->_type))
			$filename2 = ereg_replace("(.*)\.([^\.]*)$", "\\1", $this->_filename).".jpg";
		else
			$filename2 = $this->_filename;
		$file2 = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/thumbs/".$filename2;
		$file3 = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/viewsize/".$this->_filename;
		$error = false;
		if(file_exists($file1)){
			if(!@fs_unlink($file1)){
				$error = true;				
			}
		}
		//If image is deleted, delete thumb (if it exists at all)
		if (!$error) {
			if ($zoom->isImage($this->_type) || $zoom->isMovie($this->_type)) {
				if(file_exists($file2)){// NB: Documents and audiofiles don't have thumbmnails...
					if(!@fs_unlink($file2)){
						$error = true;						
					}
				}
			}
		}
		//If thumbnail is deleted, delete the viewsize image (if it exists at all)
		if (!$error) {
			if ($zoom->isImage($this->_type)) {// Only images have viewsize versions of themselves...
				if (file_exists($file3)) {
					if (!@fs_unlink($file3)) {
						$error = true;
					}
				}
			}
		}
		if (!$error) {
			//Delete record from mos_zoomfiles and comments from mos_zoom_comments
			$database->setQuery("DELETE FROM  #__zoomfiles WHERE imgid=".mysql_escape_string($this->_id));
			$database->query();
			$database->setQuery("DELETE FROM #__zoom_comments WHERE imgid=".mysql_escape_string($this->_id));
			$database->query();
			// check if the image was a category image...
			$database->setQuery("SELECT catid FROM #__zoom WHERE catimg = ".mysql_escape_string($this->_id)." LIMIT 1");
			$this->_result = $database->query();
			if (mysql_num_rows($this->_result) > 0){
				$row = mysql_fetch_object($this->_result);
				$database->setQuery("UPDATE #__zoom SET catimg = NULL WHERE catid = ".$row->catid);
				$database->query();
			}
		}
		return $error;		
	}
	function setThumbnail(){
		global $mosConfig_live_site, $mosConfig_absolute_path, $zoom;
		if($zoom->isImage($this->_type)){
			if(fs_file_exists($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/thumbs/".$this->_filename))
				$this->_thumbnail = $mosConfig_live_site."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/thumbs/".$this->_filename;
			else
				$this->_thumbnail = $mosConfig_live_site."/components/com_zoom/images/filetypes/image.png";
		}elseif($zoom->isDocument($this->_type)){
			if ($this->_type == 'pdf') {
				$this->_thumbnail = $mosConfig_live_site."/components/com_zoom/images/filetypes/pdf.png";
			}else{
				$this->_thumbnail = $mosConfig_live_site."/components/com_zoom/images/filetypes/document.png";
			}
		}elseif($zoom->isMovie($this->_type)){
			if($zoom->isThumbnailable($this->_type)){
				$file = ereg_replace("(.*)\.([^\.]*)$", "\\1", $this->_filename).".jpg";
				if(fs_file_exists($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/thumbs/".$file))
					$this->_thumbnail = $mosConfig_live_site."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/thumbs/".$file;
				else
					$this->_thumbnail = $mosConfig_live_site."/components/com_zoom/images/filetypes/video.png";
			}else{
				$this->_thumbnail = $mosConfig_live_site."/components/com_zoom/images/filetypes/video.png";
			}
		}elseif($zoom->isAudio($this->_type)){
			$this->_thumbnail = $mosConfig_live_site."/components/com_zoom/images/filetypes/audio.png";
		}
	}
	function getViewsize(){
		global $mosConfig_absolute_path, $zoom;
		if(fs_file_exists($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/viewsize/".$this->_filename)){
			$this->_viewsize = "viewsize/".$this->_filename;
			return $this->_viewsize;
		}else{
			$this->_viewsize = $this->_filename;
			return $this->_filename;
		}
	}
	function isResized(){
		if(strstr($this->_viewsize, 'viewsize/')){
			return true;
		}else{
			return false;
		}
	}
	function getComments(){
		global $database;
		$this->_comments = null;
		$database->setQuery('SELECT cmtid FROM #__zoom_comments WHERE imgid = '.mysql_escape_string($this->_id).' ORDER BY cmtdate ASC');
		$this->_result = $database->query();
		while($row = mysql_fetch_object($this->_result)){
			$comment = new comment($row->cmtid);
			$this->_comments[] = $comment;
		}
	}
	function setImgInfo($newname, $newkeywords,$newdescr,$catimg=0,$parentimg=0,$published=0,$selections=1){
		global $database, $zoom;
        // replace space-character with 'air'...or nothing!
        $newkeywords = ereg_replace(" ", "", $newkeywords);
		$database->setQuery("UPDATE #__zoomfiles SET imgname = '".mysql_escape_string($newname)."', imgkeywords='".mysql_escape_string($newkeywords)."', imgdescr = '".mysql_escape_string($newdescr)."', published = '$published', imgmembers = '$selections' WHERE imgid=".mysql_escape_string($this->_id));
		$database->query();
		if (isset($catimg) && $catimg == 1){
			// set new category image, override old one (if it ever existed)...
			$database->setQuery("UPDATE #__zoom SET catimg = ".mysql_escape_string($this->_id)." WHERE catid=".$zoom->_gallery->_id);
			$database->query();
		}else{
			// unset category image, old setting also overridden...
			$database->setQuery("UPDATE #__zoom SET catimg = NULL WHERE catid=".$zoom->_gallery->_id." AND catimg=".mysql_escape_string($this->_id));
			$database->query();
		}
		if (isset($parentimg) && $parentimg == 1){
			// set new category image for parent-gallery, override old one (if it ever existed)...
			$database->setQuery("UPDATE #__zoom SET catimg = ".mysql_escape_string($this->_id)." WHERE catid=".$zoom->_gallery->_subcat_id);
			$database->query();
		}else{
			// unset category image of parent gallery, old setting also overridden...
			$database->setQuery("UPDATE #__zoom SET catimg = NULL WHERE catid=".$zoom->_gallery->_subcat_id." AND catimg=".mysql_escape_string($this->_id));
			$database->query();
		}
	}
	function hitPlus(){
		global $database;
		$database->setQuery("UPDATE #__zoomfiles SET imghits=imghits+1 WHERE imgid=".mysql_escape_string($this->_id));
		$database->query();
		$this->_hits++;
	}
	function rateImg($vote){
		global $database, $zoom;
		if(!$zoom->_EditMon->isEdited($this->_id, 'vote')){
			$database->setQuery("UPDATE #__zoomfiles SET votesum=votesum+$vote, votenum=votenum+1 WHERE imgid=".mysql_escape_string($this->_id));
			$database->query();
			$zoom->_EditMon->setEditMon($this->_id, 'vote');
			$this->_votesum = $this->_votesum + $vote;
			$this->_votenum++;
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\"> alert('" . html_entity_decode( _ZOOM_ALERT_VOTE_OK ) . "'); </script>";
		}else{
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\"> alert('" . html_entity_decode( _ZOOM_ALERT_VOTE_ERROR) . "'); </script>";

		}
	}
	function addComment($uname, $comment){
		global $database, $zoom;
		if(!$zoom->_EditMon->isEdited($this->_id, 'comment')){
			$uname = $zoom->cleanString($uname);
			$comment = $zoom->cleanString($comment);
			if(strlen($comment) > $zoom->_CONFIG['cmtLength'])
				$comment = substr($comment, 0, $zoom->_CONFIG['cmtLength']-4)."...";
    		$today = date("Y-m-d-H-i" ,time());
			$database->setQuery("INSERT INTO #__zoom_comments (imgid,cmtname,cmtcontent,cmtdate) VALUES ('".mysql_escape_string($this->_id)."','".mysql_escape_string($uname)."','".mysql_escape_string($comment)."','$today')");
    		$database->query();
    		$zoom->_EditMon->setEditMon($this->_id, 'comment');
    		echo "<script language=\"JavaScript\" type=\"text/JavaScript\"> alert('" . html_entity_decode( _ZOOM_ALERT_COMMENTOK ) . "'); </script>";
    	}else{
    		echo "<script language=\"JavaScript\" type=\"text/JavaScript\"> alert('" . html_entity_decode( _ZOOM_ALERT_COMMENTERROR ) . "'); </script>";
    	}
    	// reload/ refill comments array...
    	$this->getComments();
	}
	function delComment($delComment){
		global $database;
		$database->setQuery("DELETE FROM #__zoom_comments WHERE cmtid=".mysql_escape_string($delComment));
		$database->query();
		$this->getComments();
	}
	function isParentImage($parent_id){
		global $database;
		$database->setQuery("SELECT catid FROM #__zoom WHERE catimg=".mysql_escape_string($this->_id));
		$this->_result = $database->query();
		while ($cat = mysql_fetch_object($this->_result)){
			if ($cat->catid == $parent_id)
				return true;
		}
		return false;
	}
	function isPublished(){
		if($this->_published == 1)
			return true;
		else
			return false;
	}
	function getNumOfComments(){
		return count($this->_comments);
	}
	function getDir(){
		global $database;
		$database->setQuery("SELECT catdir FROM #__zoom WHERE catid=".$this->_catid);
		$this->_result = $database->query();
		$row = mysql_fetch_object($this->_result);
		return $row->catdir;
	}
    function getKeywords($method = 1){
        global $Itemid, $zoom;
        // This function will return the list of keywords in two methods:
        // 1: plain list (comma seperated) of keywords.
        // 2: each keyword as hyperlink to the search-page...a search for
        //    items matching that keyword will be conducted automatically.
        if($method == 1){
            return $this->_keywords;
        }elseif($method == 2){
        	// used for display purposes only (media view)...
            $keywords = explode(",", $this->_keywords);
            $list = "";
            $counter = 0;
            foreach($keywords as $keyword){
                if($counter != 0)
                    $list .= ", ";
                if ($zoom->_CONFIG['popUpImages']) {
                	$list .= "<a href=\"javascript:void(0);\" onclick=\"searchKeyword('".$keyword."')\">".$keyword."</a>";
                } else {
                	$list .= "<a href=\"index.php?option=com_zoom&page=search&type=quicksearch&Itemid=".$Itemid."&sstring=".$keyword."\">".$keyword."</a>";
                }
                $counter++;
            }
            return $list;
        }
    }
    function isMember($popup = false){
        global $my, $zoom;
        $id = intval($my->id);
		if (strtolower($my->usertype) == 'registered')
			$registered = true;
		else
			$registered = false;
		if(in_array(1, $this->_members) || (in_array(2, $this->_members) && $registered) || in_array($id, $this->_members) || $zoom->_isAdmin || $popup == true)
			return true;
		else
			return false; 
    }
}
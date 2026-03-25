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
| Description: zOOm Image Gallery, a multi-gallery component for      |
|              Mambo based on RSGallery by Ronald Smit. It's the most |
|              feature-rich gallery component for Mambo!              |
| License: GPL                                                        |
| Filename: toolbox.class.php                                         |
| Version: 2.5                                                        |
|                                                                     |
-----------------------------------------------------------------------
-----------------------------------------------------------------------
|                                                                     |
| What is the toolbox? --> well, it's an object that bundles all      |
| medium-manipulation tools into one convenient class.                |
| These tools would include:                                          |
|                                                                     |
| - Image resizing                                                    |
| - Image rotating                                                    |
| - Image watermarking with custom TrueType fonts                     |
| - Parse Directories for media types                                 |
| - PDF/ documents searching                                          |
| - Video JPEG capturing                                              |
| - MP3 id3 tag processing                                            |
| - ALL tools have implementations for the following manipulation     |
|   software: ImageMagick, NetPBM, GD1.x and GD2.x.                   |
|                                                                     |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class toolbox{
	var $_platform = null;
	var $_isBackend = null;
	var $_conversiontype = null;
	var $_IM_path = null;
	var $_NETPBM_path = null;
	var $_FFMPEG_path = null;
	var $_PDF_path = null;
	var $_use_FFMPEG = null;
	var $_use_PDF = null;
	var $_JPEGquality = null;
    var $_err_num = null;
    var $_err_names = array();
    var $_err_types = array();
	var $_wmtext = null;
	var $_wmdatfmt = null;
	var $_wmfont = null;
	var $_wmfont_size = null;
	var $_wmrgbtext = null;
	var $_wmrgbtsdw = null;
	var $_wmhotspot = null;
	var $_wmtxp = null;
	var $_wmtyp = null;
	var $_wmsxp = null;
	var $_wmsyp = null;
    var $_buffer = null;
	
	function toolbox(){
		// constructor of the toolbox - primary init...
		global $zoom, $mosConfig_absolute_path;
		$this->_isBackend = $zoom->_isBackend;
		$this->_conversiontype = $zoom->_CONFIG['conversiontype'];
		$this->_IM_path = $zoom->_CONFIG['IM_path'];
		$this->_NETPBM_path = $zoom->_CONFIG['NETPBM_path'];
		$this->_FFMPEG_path = $zoom->_CONFIG['FFMPEG_path'];
		$this->_PDF_path = $zoom->_CONFIG['PDF_path'];
		$this->_JPEGquality = $zoom->_CONFIG['JPEGquality'];
		// load watermark settings...
		if (!isset($zoom->_CONFIG['wmtext']))
			$this->_wmtext = "[date]";
		if (!isset($zoom->_CONFIG['wmfont']))
			$this->_wmfont = "ARIAL.TTF";
		if (!isset($zoom->_CONFIG['wmfont_size']))
			$this->_wmfont_size = 12;
		if (!isset($zoom->_CONFIG['wmrgbtext']))
			$this->_wmrgbtext = "FFFFFF";
		// truetype font shadow color in hex format...
		if (!isset($zoom->_CONFIG['wmrgbtsdw']))
			$this->_wmrgbtsdw = "000000";
		if (!isset($zoom->_CONFIG['wmhotspot']))
			$this->_wmhotspot = 8;
		$this->_wmdatfmt = "Y-m-d";
		// watermark offset coordinates...t = top and s = side.
		$this->_wmtxp = 0;
		$this->_wmtyp = 0;
		$this->_wmsxp = 1;
		$this->_wmsyp = 1;
		if ($zoom->_isAdmin) {
			switch ($this->_conversiontype){
				//Imagemagick
				case 1:
					if($this->_IM_path == 'auto'){
						$this->_IM_path = '';
					}else{
						if($this->_IM_path){
							if(!is_dir($this->_IM_path)){
									echo "<div align=\"center\"><font color=\"red\">Error: your ImageMagick path is not correct! Please (re)specify it in the Admin-system under 'Settings'</font><br />";
								}
						}
					}
					break;
				//NetPBM
				case 2:
					if($this->_NETPBM_path == 'auto'){
						$this->_NETPBM_path ='';
					}else{
						if($this->_NETPBM_path){
							if(!is_dir($this->_NETPBM_path)){
									echo "<div align=\"center\"><font color=\"red\">Error: your NetPBM path is not correct! Please (re)specify it in the Admin-system under 'Settings'</font><br /></div>";
								}
						}
					}
					break;
				//GD1
				case 3:
					if (!function_exists('imagecreatefromjpeg')) {
					    echo "<div align=\"center\"><font color=\"red\">PHP running on your server does not support the GD image library, check with your webhost if ImageMagick is installed</font><br /></div>";
					}
					break;
				//GD2
				case 4:
					if (!function_exists('imagecreatefromjpeg')) {
					    echo "<div align=\"center\"><font color=\"red\">Error: PHP running on your server does not support the GD image library, check with your webhost if ImageMagick is installed</font><br /></div>";
					}
					if (!function_exists('imagecreatetruecolor')) {
					    echo "<div align=\"center\"><font color=\"red\">Error: PHP running on your server does not support GD version 2.x, please switch to GD version 1.x on the config page</font><br /></div>";
					}
					break;
			}
			if($this->_FFMPEG_path == 'auto'){
				$this->_FFMPEG_path = '';
			}else{
				if($this->_FFMPEG_path){
					if(is_dir($this->_FFMPEG_path)){
						$this->_use_FFMPEG = true;
					}else{
						$this->_use_FFMPEG = false;
					}
				}
			}
			if($this->_PDF_path == 'auto'){
				$this->_PDF_path = '';
			}else{
				if($this->_PDF_path){
					if(is_dir($this->_PDF_path)){
						$this->_use_PDF = true;
					}else{
						$this->_use_PDF = false;
					}
				}
			}
		}		
		// toolbox ready for use...
	}
    function processImage($image, $filename, $keywords, $name, $descr, $rotate, $degrees = 0, $copyMethod = 1){
		global $mosConfig_absolute_path, $zoom;
		// reset script execution time limit (as set in MAX_EXECUTION_TIME ini directive)...
		// requires SAFE MODE to be OFF!
		if( ini_get( 'safe_mode' ) != 1 ){
			set_time_limit(0);
		}
		$imagepath = $zoom->_CONFIG['imagepath'];
		$catdir = $zoom->_gallery->getDir();
		$filename = urldecode($filename);
        // replace every space-character with a single "_"
	    $filename = ereg_replace(" ", "_", $filename);
     	// Get rid of extra underscores
     	$filename = ereg_replace("_+", "_", $filename);
     	$filename = ereg_replace("(^_|_$)", "", $filename);
		$tag = ereg_replace(".*\.([^\.]*)$", "\\1", $filename);
		$tag = strtolower($tag);
		$zoom->checkDuplicate($filename, 'filename');
		$filename = $zoom->_tempname;
        if($zoom->acceptableFormat($tag)){
            // File is an image/ movie/ document...
            $file = $mosConfig_absolute_path."/".$imagepath.$catdir."/".$filename;
            $desfile = $mosConfig_absolute_path."/".$imagepath.$catdir."/thumbs/".$filename;
            if($copyMethod == 1){
                if (!@move_uploaded_file("$image", "$file")){
                    // some error occured while moving file, register this...
                    $this->_err_num++;
                    $this->_err_names[$this->_err_num] = $filename;
                    $this->_err_types[$this->_err_num] = _ZOOM_ALERT_MOVEFAILURE;
                    return false;
                }
            }elseif($copyMethod == 2){
                if (!@fs_copy("$image", "$file")){
                    // some error occured while moving file, register this...
                    $this->_err_num++;
                    $this->_err_names[$this->_err_num] = $filename;
                    $this->_err_types[$this->_err_num] = _ZOOM_ALERT_MOVEFAILURE;
                    return false;
                }
            }
            @chmod("$file", 0644);
           if($zoom->isImage($tag)){
           	   $imginfo = getimagesize($file);
           	   // get image EXIF & IPTC data from file to save it in viewsize image and get a thumbnail...
           	   if ($zoom->_CONFIG['readEXIF'] && $tag === "jpg"){
           	   	// Retreive the EXIF, XMP and Photoshop IRB information from
				// the existing file, so that it can be updated later on...
				$jpeg_header_data = get_jpeg_header_data( $file );
				$EXIF_data = get_EXIF_JPEG($file);
				$XMP_data = read_XMP_array_from_text( get_XMP_text( $jpeg_header_data ) );
                $IRB_data = get_Photoshop_IRB( $jpeg_header_data );
                $new_ps_file_info = get_photoshop_file_info($EXIF_data, $XMP_data, $IRB_data);
                // Check if there is a default for the date defined
                if ( ( ! array_key_exists( 'date', $new_ps_file_info ) ) ||
                	( ( array_key_exists( 'date', $new_ps_file_info ) ) &&
                	( $new_ps_file_info['date'] == '' ) ) )
                {
                	// No default for the date defined
                	// figure out a default from the file
                	// Check if there is a EXIF Tag 36867 "Date and Time of Original"
                	if ( ( $EXIF_data != FALSE ) &&
                		( array_key_exists( 0, $EXIF_data ) ) &&
                		( array_key_exists( 34665, $EXIF_data[0] ) ) &&
                		( array_key_exists( 0, $EXIF_data[0][34665] ) ) &&
                		( array_key_exists( 36867, $EXIF_data[0][34665][0] ) ) )
                	{
                    	// Tag "Date and Time of Original" found - use it for the default date
                    	$new_ps_file_info['date'] = $EXIF_data[0][34665][0][36867]['Data'][0];
                    	$new_ps_file_info['date'] = preg_replace( "/(\d\d\d\d):(\d\d):(\d\d)( \d\d:\d\d:\d\d)/", "$1-$2-$3", $new_ps_file_info['date'] );
                    }
                    // Check if there is a EXIF Tag 36868 "Date and Time when Digitized"
                    else if ( ( $EXIF_data != FALSE ) &&
                    	( array_key_exists( 0, $EXIF_data ) ) &&
                    	( array_key_exists( 34665, $EXIF_data[0] ) ) &&
                    	( array_key_exists( 0, $EXIF_data[0][34665] ) ) &&
                    	( array_key_exists( 36868, $EXIF_data[0][34665][0] ) ) )
                    {
                    	// Tag "Date and Time when Digitized" found - use it for the default date
                    	$new_ps_file_info['date'] = $EXIF_data[0][34665][0][36868]['Data'][0];
                    	$new_ps_file_info['date'] = preg_replace( "/(\d\d\d\d):(\d\d):(\d\d)( \d\d:\d\d:\d\d)/", "$1-$2-$3", $new_ps_file_info['date'] );
                    }
                    // Check if there is a EXIF Tag 306 "Date and Time"
                    else if ( ( $EXIF_data != FALSE ) &&
                    	( array_key_exists( 0, $EXIF_data ) ) &&
                    	( array_key_exists( 306, $EXIF_data[0] ) ) )
                    {
                    	// Tag "Date and Time" found - use it for the default date
                    	$new_ps_file_info['date'] = $EXIF_data[0][306]['Data'][0];
                    	$new_ps_file_info['date'] = preg_replace( "/(\d\d\d\d):(\d\d):(\d\d)( \d\d:\d\d:\d\d)/", "$1-$2-$3", $new_ps_file_info['date'] );
                    }else{
                    	// Couldn't find an EXIF date in the image
                    	// Set default date as creation date of file
                    	$new_ps_file_info['date'] = date ("Y-m-d", filectime( $file ));
                    }
                }
           	   }
               if($rotate){
                   if(!$zoom->_toolbox->rotateImage($file, $file, $filename, $degrees)){
                       $this->_err_num++;
                       $this->_err_names[$this->_err_num] = $filename;
                       $this->_err_types[$this->_err_num] = "Error rotating image";
                       return false;
                   }
               }
               // if the image size is greater than the given maximum: resize it!               
               if($imginfo[0] > $zoom->_CONFIG['maxsize'] || $imginfo[1] > $zoom->_CONFIG['maxsize']){
               		$viewsize = $mosConfig_absolute_path."/".$imagepath.$catdir."/viewsize/".$filename;
               		if(!$zoom->_toolbox->resizeImage($file, $viewsize, $zoom->_CONFIG['maxsize'], $filename)){
               			if ($zoom->_CONFIG['readEXIF'] && ($tag === "jpg" || $tag = "jpeg")){
               				// put the EXIF info back in the resized file...
               				// Update the JPEG header information with the new Photoshop File Info
               				// NOTE: this only seems to work with GD2.x. Why? I don't know ;-)
                        	$jpeg_header_data = put_photoshop_file_info( $jpeg_header_data, $new_ps_file_info, $EXIF_data, $XMP_data, $IRB_data );
                        	if (put_jpeg_header_data( $file, $viewsize, $jpeg_header_data ) == false) {
                       			$this->_err_num++;
			                    $this->_err_names[$this->_err_num] = $filename;
			                    $this->_err_types[$this->_err_num] = _ZOOM_ALERT_IMGERROR;
			                    return false;
                        	}
               			}
               		   $this->_err_num++;
	                   $this->_err_names[$this->_err_num] = $filename;
	                   $this->_err_types[$this->_err_num] = _ZOOM_ALERT_IMGERROR;
	                   return false;
               		}
               }
               // resize to thumbnail...
               // JPEG files often carry pre-made thumbnails in them, so we'll use that one
               // if it exists at all.
               if ($zoom->_CONFIG['readEXIF'] && ($tag === "jpg" || $tag = "jpeg") && count($EXIF_data) >= 2 && is_array($EXIF_data[1][513]) && !empty($EXIF_data[1][513]['Data'])){
           			if(!$zoom->writefile($desfile, $EXIF_data[1][513]['Data'])){
	           			$this->_err_num++;
	                   	$this->_err_names[$this->_err_num] = $filename;
	                   	$this->_err_types[$this->_err_num] = _ZOOM_ALERT_IMGERROR;
	                   	return false;
           			}
               }elseif(!$zoom->_toolbox->resizeImage($file, $desfile, $zoom->_CONFIG['size'], $filename)){
                   $this->_err_num++;
                   $this->_err_names[$this->_err_num] = $filename;
                   $this->_err_types[$this->_err_num] = _ZOOM_ALERT_IMGERROR;
                   return false;
               }
           }elseif($zoom->isDocument($tag)){
               if($zoom->isIndexable($tag) && $this->_use_PDF){
                	if(!$this->indexDocument($file, $filename)){
                	   $this->_err_num++;
                       $this->_err_names[$this->_err_num] = $filename;
                       $this->_err_types[$this->_err_num] = _ZOOM_ALERT_INDEXERROR;
                       return false;
                	}
               }
           }elseif($zoom->isMovie($tag)){
               //if movie is 'thumbnailable' -> make a thumbnail then!
               if($zoom->isThumbnailable($tag) && $this->_use_FFMPEG){
                   if(!$zoom->_toolbox->createMovieThumb($file, $zoom->_CONFIG['size'], $filename)){
                       $this->_err_num++;
                       $this->_err_names[$this->_err_num] = $filename;
                       $this->_err_types[$this->_err_num] = _ZOOM_ALERT_IMGERROR;
                       return false;
                   }
               }
           }elseif($zoom->isAudio($tag)){
           		// TODO: indexing audio files (mp3-files, etc.) properties, e.g. id3vX tags...
           }
           // replace space-characters in combination with a comma with 'air'...or nothing!
           $keywords = ereg_replace(", ", ",", $keywords);
           if(empty($name))
           	$name = $zoom->_CONFIG['tempName'];
           $zoom->saveImage($filename, $keywords, $name, $descr, $zoom->_gallery->_id);
        }else{
            //Not the right format, register this...
            $this->_err_num++;
            $this->_err_names[$this->_err_num] = $filename;
            $this->_err_types[$this->_err_num] = _ZOOM_ALERT_WRONGFORMAT_MULT;
            return false;
        }
        return true;
    }
	function resizeImage($file, $desfile, $size, $filename = ""){
		switch ($this->_conversiontype){
			//Imagemagick
			case 1:
				if($this->resizeImageIM($file, $desfile, $size))
					return true;
				else
					return false;
				break;
			//NetPBM
			case 2:
				if($this->resizeImageNETPBM($file,$desfile,$size,$filename))
					return true;
				else
					return false;
				break;
			//GD1
			case 3:
				if($this->resizeImageGD1($file, $desfile, $size))
					return true;
				else
					return false;
				break;
			//GD2
			case 4:
				if($this->resizeImageGD2($file, $desfile, $size))
					return true;
				else
					return false;
				break;
		}
		return true;
	}
	function resizeImageIM($src_file, $dest_file, $new_size){
		$cmd = $this->_IM_path."convert -resize $new_size \"$src_file\" \"$dest_file\"";
		exec($cmd, $output, $retval);
		if($retval)
			return false;
		else
			return true;
	}
	function resizeImageNETPBM($src_file,$des_file,$new_size,$orig_name){
		$quality = $this->_JPEGquality;
		$imginfo = getimagesize($src_file);
		if ($imginfo == null)
			return false;
		// height/width
		$srcWidth = $imginfo[0];
		$srcHeight = $imginfo[1];
		$ratio = max($srcWidth, $srcHeight) / $new_size;
		$ratio = max($ratio, 1.0);
		$destWidth = (int)($srcWidth / $ratio);
		$destHeight = (int)($srcHeight / $ratio);
		if (eregi("\.png", $orig_name)){
			$cmd = $this->_NETPBM_path . "pngtopnm $src_file | " . $this->_NETPBM_path . "pnmscale -xysize $destWidth $destHeight | " . $this->_NETPBM_path . "pnmtopng > $des_file" ; 
		}
		else if (eregi("\.(jpg|jpeg)", $orig_name)){
			$cmd = $this->_NETPBM_path . "jpegtopnm $src_file | " . $this->_NETPBM_path . "pnmscale -xysize $destWidth $destHeight | " . $this->_NETPBM_path . "ppmtojpeg -quality=$quality > $des_file" ;
		}
		else if (eregi("\.gif", $orig_name)){
			$cmd = $this->_NETPBM_path . "giftopnm $src_file | " . $this->_NETPBM_path . "pnmscale -xysize $destWidth $destHeight | " . $this->_NETPBM_path . "ppmquant 256 | " . $this->_NETPBM_path . "ppmtogif > $des_file" ; 
		}else{
			return false;
		}
		exec($cmd, $output, $retval);
		if($retval)
			return false;
		else
			return true;
	}
	function resizeImageGD1($src_file, $dest_file, $new_size){
		$imginfo = getimagesize($src_file);
		if ($imginfo == null)
			return false;
		// GD can only handle JPG & PNG images
		if ($imginfo[2] != 2 && $imginfo[2] != 3){
			return false;
		}
		// height/width
		$srcWidth = $imginfo[0];
		$srcHeight = $imginfo[1];
		$ratio = max($srcWidth, $srcHeight) / $new_size;
		$ratio = max($ratio, 1.0);
		$destWidth = (int)($srcWidth / $ratio);
		$destHeight = (int)($srcHeight / $ratio);
		if ($imginfo[2] == 2)
			$src_img = imagecreatefromjpeg($src_file);
		else
			$src_img = imagecreatefrompng($src_file);
		if (!$src_img)
			return false;
		$dst_img = imagecreate($destWidth, $destHeight);
		imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $destWidth, (int)$destHeight, $srcWidth, $srcHeight);
		if ($imginfo[2] == 2)
			imagejpeg($dst_img, $dest_file, $this->_JPEGquality);
		else
			imagepng($dst_img, $dest_file);
		imagedestroy($src_img);
		imagedestroy($dst_img);
		return true; 
	}
	function resizeImageGD2($src_file, $dest_file, $new_size){
		$imginfo = getimagesize($src_file);
		if ($imginfo == null)
			return false;
		// GD can only handle JPG & PNG images
		if ($imginfo[2] != 2 && $imginfo[2] != 3){
			return false;
		}
		// height/width
		$srcWidth = $imginfo[0];
		$srcHeight = $imginfo[1];
		$ratio = max($srcWidth, $srcHeight) / $new_size;
		$ratio = max($ratio, 1.0);
		$destWidth = (int)($srcWidth / $ratio);
		$destHeight = (int)($srcHeight / $ratio);
		if ($imginfo[2] == 2)
			$src_img = imagecreatefromjpeg($src_file);
		else
			$src_img = imagecreatefrompng($src_file);
		if (!$src_img)
			return false;
		$dst_img = imagecreatetruecolor($destWidth, $destHeight);
		imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $destWidth, (int)$destHeight, $srcWidth, $srcHeight);
		if ($imginfo[2] == 2)
			imagejpeg($dst_img, $dest_file, $this->_JPEGquality);
		else
			imagepng($dst_img, $dest_file);
		imagedestroy($src_img);
		imagedestroy($dst_img);
		return true;
	}
	function rotateImage($file, $desfile, $filename, $degrees){
		$degrees = intval($degrees);
		switch ($this->_conversiontype){
			//Imagemagick
			case 1:
				if($this->rotateImageIM($file, $desfile, $degrees))
					return true;
				else
					return false;
				break;
			//NetPBM
			case 2:
				if($this->rotateImageNETPBM($file,$desfile, $filename, $degrees))
					return true;
				else
					return false;
				break;
			//GD1
			case 3:
				if($this->rotateImageGD1($file, $desfile, $degrees))
					return true;
				else
					return false;
				break;
			//GD2
			case 4:
				if($this->rotateImageGD2($file, $desfile, $degrees))
					return true;
				else
					return false;
				break;
		}
		return true;
	}
	function rotateImageIM($file, $desfile, $degrees){
		$cmd = $this->_IM_path."convert -rotate $degrees \"$file\" \"$desfile\"";
		exec($cmd, $output, $retval);
		if($retval)
			return false;
		else
			return true;
	}
	function rotateImageNETPBM($file, $desfile, $filename, $degrees){
		$quality = $this->_JPEGquality;
		$fileOut = "$file.1";
		fs_copy($file,$fileOut); 
		if (eregi("\.png", $filename)){
			$cmd = $this->_NETPBM_path . "pngtopnm $file | " . $this->_NETPBM_path . "pnmrotate $degrees | " . $this->_NETPBM_path . "pnmtopng > $fileOut" ; 
		}
		else if (eregi("\.(jpg|jpeg)", $filename)){
			$cmd = $this->_NETPBM_path . "jpegtopnm $file | " . $this->_NETPBM_path . "pnmrotate $degrees | " . $this->_NETPBM_path . "ppmtojpeg -quality=$quality > $fileOut" ;
		}
		else if (eregi("\.gif", $orig_name)){
			$cmd = $this->_NETPBM_path . "giftopnm $file | " . $this->_NETPBM_path . "pnmrotate $degrees | " . $this->_NETPBM_path . "ppmquant 256 | " . $this->_NETPBM_path . "ppmtogif > $fileOut" ; 
		}else{
			return false;
		}
		exec($cmd, $output, $retval);
		if($retval){
			return false;
		}else{
			$erg = fs_rename($fileOut, $desfile); 
			return true;
		}
	}
	function rotateImageGD1($file, $desfile, $degrees){
		$imginfo = getimagesize($file);
		if ($imginfo == null)
			return false;
		// GD can only handle JPG & PNG images
		if ($imginfo[2] != 2 && $imginfo[2] != 3){
			return false;
		}
		if ($imginfo[2] == 2)
			$src_img = imagecreatefromjpeg($file);
		else
			$src_img = imagecreatefrompng($file);
		if (!$src_img)
			return false;
		// The rotation routine...
		$src_img = imagerotate($src_img, $degrees, 0);
		$dst_img = imagecreate($imginfo[0], $imginfo[1]);
		imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $destWidth, (int)$destHeight, $srcWidth, $srcHeight);
		if ($imginfo[2] == 2)
			imagejpeg($dst_img, $desfile, $this->_JPEGquality);
		else
			imagepng($dst_img, $desfile);
		imagedestroy($src_img);
		imagedestroy($dst_img);
		return true; 
	}
	function rotateImageGD2($file, $desfile, $degrees){
		$imginfo = getimagesize($file);
		if ($imginfo == null)
			return false;
		// GD can only handle JPG & PNG images
		if ($imginfo[2] != 2 && $imginfo[2] != 3){
			return false;
		}
		if ($imginfo[2] == 2)
			$src_img = imagecreatefromjpeg($file);
		else
			$src_img = imagecreatefrompng($file);
		if (!$src_img)
			return false;
		// The rotation routine...
		$src_img = imagerotate($src_img, $degrees, 0);
		$dst_img = imagecreatetruecolor($imginfo[0], $imginfo[1]);
		imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $destWidth, (int)$destHeight, $srcWidth, $srcHeight);
		if ($imginfo[2] == 2)
			imagejpeg($dst_img, $desfile, $this->_JPEGquality);
		else
			imagepng($dst_img, $desfile);
		imagedestroy($src_img);
		imagedestroy($dst_img);
		return true;
	}
	// watermark by Elf Qrin ( http://www.ElfQrin.com/ ) - modified for use with zOOm.
	function watermark($file, $desfile) {
		$suffx = substr($file,strlen($file)-4,4);
		if ($suffx == ".jpg" || $suffx == "jpeg" || $suffx == ".png") {
			$text = str_replace("[date]",date($this->_wmdatfmt),$this->_wmtext);
			if ($suffx == ".jpg" || $suffx == "jpeg") {
				$image = imagecreatefromjpeg($file);
			}
			if ($suffx == ".png") {
				$image = imagecreatefrompng($file);
			}
			$rgbtext = HexDec($this->_wmrgbtext);
			$txtr = floor($rgbtext/pow(256,2));
			$txtg = floor(($rgbtext%pow(256,2))/pow(256,1));
			$txtb = floor((($rgbtext%pow(256,2))%pow(256,1))/pow(256,0));
			
			$rgbtsdw = HexDec($this->_wmrgbtsdw);
			$tsdr = floor($rgbtsdw/pow(256,2));
			$tsdg = floor(($rgbtsdw%pow(256,2))/pow(256,1));
			$tsdb = floor((($rgbtsdw%pow(256,2))%pow(256,1))/pow(256,0));
			
			$coltext = imagecolorallocate($image,$txtr,$txtg,$txtb);
			$coltsdw = imagecolorallocate($image,$tsdr,$tsdg,$tsdb);
			
			if ($this->_wmhotspot != 0) {
				$ix = imagesx($image);
				$iy = imagesy($image);
				$tsw = strlen($text)*$this->_wmfont_size/imagefontwidth($this->_wmfont)*3;
				$tsh = $this->_wmfont_size/imagefontheight($this->_wmfont);
				switch ($this->_wmhotspot) {
					case 1:
						$txp = $this->_wmtxp;
						$typ = $tsh*$tsh+imagefontheight($this->_wmfont)*2+$this->_wmtyp;
						break;
					case 2:
						$txp = floor(($ix-$tsw)/2);
						$typ = $tsh*$tsh+imagefontheight($this->_wmfont)*2+$this->_wmtyp;
						break;
					case 3:
						$txp = $ix-$tsw-$txp;
						$typ = $tsh*$tsh+imagefontheight($this->_wmfont)*2+$this->_wmtyp;
						break;
					case 4:
						$txp = $this->_wmtxp;
						$typ = floor(($iy-$tsh)/2);
						break;
					case 5:
						$txp = floor(($ix-$tsw)/2);
						$typ = floor(($iy-$tsh)/2);
						break;
					case 6:
						$txp = $ix-$tsw-$this->_wmtxp;
						$typ = floor(($iy-$tsh)/2);
						break;
					case 7:
						$txp = $this->_wmtxp;
						$typ = $iy-$tsh-$this->_wmtyp;
						break;
					case 8:
						$txp = floor(($ix-$tsw)/2);
						$typ = $iy-$tsh-$this->_wmtyp;
						break;
					case 9:
						$txp = $ix-$tsw-$this->_wmtxp;
						$typ = $iy-$tsh-$this->_wmtyp;
						break;
				}
			}
			ImageTTFText($image, $this->_wmfont_size, 0, $txp+$sxp, $typ+$syp, $coltsdw, $this->_wmfont,$text);
			ImageTTFText($image, $this->_wmfont_size, 0, $txp, $typ, $coltext, $this->wmfont, $text);	
			if ($suffx == ".jpg" || $suffx == "jpeg") {
				imagejpeg($image, $desfile, $this->_JPEGquality);
			}elseif($suffx == ".png"){
				imgepng($image, $desfile);
			}
			imagedestroy($image);
			return true;
		}else{
			return false;
		}
	}
	function createMovieThumb($file, $size, $filename){
		global $mosConfig_absolute_path, $zoom;
		if($this->_FFMPEG_path == 'auto'){
			$this->_FFMPEG_path = '';
		}else{
			if($this->_FFMPEG_path){
				if(!is_dir($this->_FFMPEG_path)){
						die("Error: your FFmpeg path is not correct! Please (re)specify it in the Admin-system under 'Settings'");
					}
			}
		}
		$tempdir = uniqid('zoom_');
		$gen_images = array();
		$gen_path = $mosConfig_absolute_path."/media/".$tempdir;
		// the file has the extension 'mpg' or 'avi' or 'whatever' and needs to be 'jpg'...
		$desfile = ereg_replace("(.*)\.([^\.]*)$", "\\1", $filename).".jpg";
		if(mkdir($gen_path, 0777)){
			$cmd = $this->_FFMPEG_path."ffmpeg -an -y -t 0:0:0.001 -ss 0:0:0.000 -i \"$file\" -f image -img jpeg \"$gen_path/file%d.jpg\"";
			exec($cmd, $output, $retval);
			if(!$retval){
				$gen_images = $this->parseDir($gen_path);
				$the_thumb = round(sizeof($gen_images) / 2) - 1;
				$the_thumb = $gen_path."/".$gen_images[$the_thumb];
				$target = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->getDir()."/thumbs/".$desfile;
				if(!$this->resizeImage($the_thumb, $target, $size, $desfile)){
					return false;
				}
			}else{
				return false;
			}
			$zoom->deldir($gen_path);
			return true;
		}else{
			return false;
		}
	}
	function searchPdf(&$file, $searchText){
        global $zoom, $mosConfig_absolute_path;
        $file->getInfo();
        $source = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$file->getDir()."/".ereg_replace("(.*)\.([^\.]*)$", "\\1", $file->_filename).".txt";
        if(fs_is_file($source)){
        	$txt = strtolower(file_get_contents($source));
        	if(preg_match("/$searchText/", $txt)){
        		return true;
        	}else{
        		return false;
        	}
        	unset($txt);
        }else{
        	return false;
        }
	}
	function indexDocument($file, $filename){
		global $mosConfig_absolute_path, $zoom;
		// this function will contain the algorithm to index a document (like a pdf)...
		// Method: use PDFtoText to create a plain ASCII text-file, which can be easily
		//         searched through. The text-file will be placed into the same dir as the
		//         original pdf.
		// Note: support for MS Word, Excel and Powerpoint indexing will be added later.
		if($this->_PDF_path == 'auto'){
			$this->_PDF_path = '';
		}else{
			if($this->_PDF_path){
				if(!is_dir($this->_PDF_path)){
						die("Error: your PDFtoText path is not correct! Please (re)specify it in the Admin-system under 'Settings'");
					}
			}
		}
		$desfile = ereg_replace("(.*)\.([^\.]*)$", "\\1", $filename).".txt";
		$target = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->getDir()."/".$desfile;
		$cmd = $this->_PDF_path."pdftotext \"$file\" \"$target\"";
		exec($cmd, $output, $retval);
		if(!$retval)
			return true;
		else
			return false;
	}
	function parseDir($dir){
		global $zoom, $mosConfig_absolute_path;
		$dir = $mosConfig_absolute_path."/".$dir;
		// start the scan...(open the local dir)
		$images = array();
		$handle = fs_opendir($dir);
		while (($file = readdir($handle)) != false) {
			if ($file != "." && $file != "..") {
				$tag = ereg_replace(".*\.([^\.]*)$", "\\1", $file);
				$tag = strtolower($tag);
				if ($zoom->acceptableFormat($tag)) {
					// Tack it onto images...
					$images[] = $file;
				}
			}
		}
		closedir($handle);
		return $images;
	}
	function getImageLibs(){
		// do auto-detection on the available graphics libraries
		// This assumes the executables are within the shell's path
		$imageLibs= array();
		// do various tests:
		if ($testIM = $this->testIM()) {
			$imageLibs['imagemagick'] = $testIM;
		}
		if ($testNetPBM = $this->testNetPBM()) {
			$imageLibs['netpbm'] = $testNetPBM;
		}
		if ($testFFmpeg = $this->testFFmpeg()) {
			$imageLibs['ffmpeg'] = $testFFmpeg;
		}
		if ($testXpdf = $this->testXpdf()) {
			$imageLibs['pdftotext'] = $testXpdf;
		}			
		$imageLibs['gd'] = $this->testGD();		
		return $imageLibs;
	}
	function testIM(){
		exec('convert -version', $output, $status);
		if(!$status){
			if(preg_match("/imagemagick[ \t]+([0-9\.]+)/i",$output[0],$matches))
			   return $matches[0];
		}
		unset($output, $status);
	}
	function testNetPBM(){
		exec('jpegtopnm -version 2>&1',  $output, $status);
		if(!$status){
			if(preg_match("/netpbm[ \t]+([0-9\.]+)/i",$output[0],$matches))
			   return $matches[0];
		}
		unset($output, $status);
	}
	function testGD(){
		$gd = array();
		$GDfuncList = get_extension_funcs('gd');
		ob_start();
		@phpinfo(INFO_MODULES);
		$output=ob_get_contents();
		ob_end_clean();
		$matches[1]='';
		if(preg_match("/GD Version[ \t]*(<[^>]+>[ \t]*)+([^<>]+)/s",$output,$matches)){
			$gdversion = $matches[2];
		}
		if( $GDfuncList ){
		 if( in_array('imagegd2',$GDfuncList) )
			$gd['gd2'] = $gdversion;
		 else
			$gd['gd1'] = $gdversion;
		}
		return $gd;
	}
	function testFFmpeg(){
		exec('ffmpeg -h',  $output, $status);
		if(!empty($output[0])){
			if(preg_match("/ffmpeg.*(\.[0-9])/i",$output[0],$matches)){
			   return $matches[0];
			}
		}
		unset($output, $status);
	}
	function testXpdf(){
		exec('pdftotext',  $output, $status);
		if(!empty($output[0])){
			if(preg_match("/pdftotext/i",$output[0],$matches)){
			   return "pdftotext";
			}
		}
		unset($output, $status);
	}
	function mp3_id($file) {
		$start = time();
		// This function parse ID3 tag from MP3 file. It's quite fast.
		// syntax mp3_id(filename)
		// function will return -1 if file not exists or no frame cynch found at the beginning of file. i realized that some songs downloaded thru gnutella have about four lines of text info at the beginning. it seepms players can handle. so i will implement it later.
		// variable bitrates are not yet implemented, as they are quite slow to check. you can find them to read lot of first frames and check their bitrates. If theyre not the same, its variable bitrate. and also you then cannot compute real song lenght, unless you will scan the whole file for frames and compute its lenght... (at least what i read)
		// there is second version of ID3 tag which is tagged at the beginning of the file and its quite large. you can learnt more about at http://www.id3.org/. i dont finding this so interesting. there are too good things on new version: you can write more than 30 chars at field and the tag is on the beginning of file. there are so many fields in v2 that i found really unusefull in many case. while it seems that id3v2 will still write tag v1 at the end, i can see no reason why to implement it, cos it is really 'slow' to parse all these informations.
		
		// You can use 'genres' to determine what means the 'genreid' number. if you think you will not need it, delete it to. And also we need to specify all variables for mp3 header.
		$genres = Array(
		'Blues',
		'Classic Rock',
		'Country',
		'Dance',
		'Disco',
		'Funk',
		'Grunge',
		'Hip-Hop',
		'Jazz',
		'Metal',
		'New Age',
		'Oldies',
		'Other',
		'Pop',
		'R&B',
		'Rap',
		'Reggae',
		'Rock',
		'Techno',
		'Industrial',
		'Alternative',
		'Ska',
		'Death Metal',
		'Pranks',
		'Soundtrack',
		'Euro-Techno',
		'Ambient',
		'Trip-Hop',
		'Vocal',
		'Jazz+Funk',
		'Fusion',
		'Trance',
		'Classical',
		'Instrumental',
		'Acid',
		'House',
		'Game',
		'Sound Clip',
		'Gospel',
		'Noise',
		'AlternRock',
		'Bass',
		'Soul',
		'Punk',
		'Space',
		'Meditative',
		'Instrumental Pop',
		'Instrumental Rock',
		'Ethnic',
		'Gothic',
		'Darkwave',
		'Techno-Industrial',
		'Electronic',
		'Pop-Folk',
		'Eurodance',
		'Dream',
		'Southern Rock',
		'Comedy',
		'Cult',
		'Gangsta',
		'Top 40',
		'Christian Rap',
		'Pop/Funk',
		'Jungle',
		'Native American',
		'Cabaret',
		'New Wave',
		'Psychadelic',
		'Rave',
		'Showtunes',
		'Trailer',
		'Lo-Fi',
		'Tribal',
		'Acid Punk',
		'Acid Jazz',
		'Polka',
		'Retro',
		'Musical',
		'Rock & Roll',
		'Hard Rock',
		'Folk',
		'Folk-Rock',
		'National Folk',
		'Swing',
		'Fast Fusion',
		'Bebob',
		'Latin',
		'Revival',
		'Celtic',
		'Bluegrass',
		'Avantgarde',
		'Gothic Rock',
		'Progressive Rock',
		'Psychedelic Rock',
		'Symphonic Rock',
		'Slow Rock',
		'Big Band',
		'Chorus',
		'Easy Listening',
		'Acoustic',
		'Humour',
		'Speech',
		'Chanson',
		'Opera',
		'Chamber Music',
		'Sonata',
		'Symphony',
		'Booty Bass',
		'Primus',
		'Porn Groove',
		'Satire',
		'Slow Jam',
		'Club',
		'Tango',
		'Samba',
		'Folklore',
		'Ballad',
		'Power Ballad',
		'Rhythmic Soul',
		'Freestyle',
		'Duet',
		'Punk Rock',
		'Drum Solo',
		'Acapella',
		'Euro-House',
		'Dance Hall'
		);
		
		$genreids = Array(
		"Blues" => 0,
		"Classic Rock" => 1,
		"Country" => 2,
		"Dance" => 3,
		"Disco" => 4,
		"Funk" => 5,
		"Grunge" => 6,
		"Hip-Hop" => 7,
		"Jazz" => 8,
		"Metal" => 9,
		"New Age" => 10,
		"Oldies" => 11,
		"Other" => 12,
		"Pop" => 13,
		"R&B" => 14,
		"Rap" => 15,
		"Reggae" => 16,
		"Rock" => 17,
		"Techno" => 18,
		"Industrial" => 19,
		"Alternative" => 20,
		"Ska" => 21,
		"Death Metal" => 22,
		"Pranks" => 23,
		"Soundtrack" => 24,
		"Euro-Techno" => 25,
		"Ambient" => 26,
		"Trip-Hop" => 27,
		"Vocal" => 28,
		"Jazz+Funk" => 29,
		"Fusion" => 30,
		"Trance" => 31,
		"Classical" => 32,
		"Instrumental" => 33,
		"Acid" => 34,
		"House" => 35,
		"Game" => 36,
		"Sound Clip" => 37,
		"Gospel" => 38,
		"Noise" => 39,
		"AlternRock" => 40,
		"Bass" => 41,
		"Soul" => 42,
		"Punk" => 43,
		"Space" => 44,
		"Meditative" => 45,
		"Instrumental Pop" => 46,
		"Instrumental Rock" => 47,
		"Ethnic" => 48,
		"Gothic" => 49,
		"Darkwave" => 50,
		"Techno-Industrial" => 51,
		"Electronic" => 52,
		"Pop-Folk" => 53,
		"Eurodance" => 54,
		"Dream" => 55,
		"Southern Rock" => 56,
		"Comedy" => 57,
		"Cult" => 58,
		"Gangsta" => 59,
		"Top 40" => 60,
		"Christian Rap" => 61,
		"Pop/Funk" => 62,
		"Jungle" => 63,
		"Native American" => 64,
		"Cabaret" => 65,
		"New Wave" => 66,
		"Psychadelic" => 67,
		"Rave" => 68,
		"Showtunes" => 69,
		"Trailer" => 70,
		"Lo-Fi" => 71,
		"Tribal" => 72,
		"Acid Punk" => 73,
		"Acid Jazz" => 74,
		"Polka" => 75,
		"Retro" => 76,
		"Musical" => 77,
		"Rock & Roll" => 78,
		"Hard Rock" => 79,
		"Folk" => 80,
		"Folk-Rock" => 81,
		"National Folk" => 82,
		"Swing" => 83,
		"Fast Fusion" => 84,
		"Bebob" => 85,
		"Latin" => 86,
		"Revival" => 87,
		"Celtic" => 88,
		"Bluegrass" => 89,
		"Avantgarde" => 90,
		"Gothic Rock" => 91,
		"Progressive Rock" => 92,
		"Psychedelic Rock" => 93,
		"Symphonic Rock" => 94,
		"Slow Rock" => 95,
		"Big Band" => 96,
		"Chorus" => 97,
		"Easy Listening" => 98,
		"Acoustic" => 99,
		"Humour" => 100,
		"Speech" => 101,
		"Chanson" => 102,
		"Opera" => 103,
		"Chamber Music" => 104,
		"Sonata" => 105,
		"Symphony" => 106,
		"Booty Bass" => 107,
		"Primus" => 108,
		"Porn Groove" => 109,
		"Satire" => 110,
		"Slow Jam" => 111,
		"Club" => 112,
		"Tango" => 113,
		"Samba" => 114,
		"Folklore" => 115,
		"Ballad" => 116,
		"Power Ballad" => 117,
		"Rhythmic Soul" => 118,
		"Freestyle" => 119,
		"Duet" => 120,
		"Punk Rock" => 121,
		"Drum Solo" => 122,
		"Acapella" => 123,
		"Euro-House" => 124,
		"Dance Hall" => 125
		);		
		$version=Array("00"=>2.5, "10"=>2, "11"=>1);
		$layer=Array("01"=>3, "10"=>2, "11"=>1);
		$crc=Array("Yes", "No");
		$bitrate["0001"]=Array(32,32,32,32,8,8);
		$bitrate["0010"]=Array(64,48,40,48,16,16);
		$bitrate["0011"]=Array(96,56,48,56,24,24);
		$bitrate["0100"]=Array(128,64,56,64,32,32);
		$bitrate["0101"]=Array(160,80,64,80,40,40);
		$bitrate["0110"]=Array(192,96,80,96,48,48);
		$bitrate["0111"]=Array(224,112,96,112,56,56);
		$bitrate["1000"]=Array(256,128,112,128,64,64);
		$bitrate["1001"]=Array(288,160,128,144,80,80);
		$bitrate["1010"]=Array(320,192,160,160,96,96);
		$bitrate["1011"]=Array(352,224,192,176,112,112);
		$bitrate["1100"]=Array(384,256,224,192,128,128);
		$bitrate["1101"]=Array(416,320,256,224,144,144);
		$bitrate["1110"]=Array(448,384,320,256,160,160);
		$bitindex=Array("1111"=>"0","1110"=>"1","1101"=>"2","1011"=>"3","1010"=>"4","1001"=>"5","0011"=>"3","0010"=>4,"0001"=>"5");
		$freq["00"]=Array("11"=>44100,"10"=>22050,"00"=>11025);
		$freq["01"]=Array("11"=>48000,"10"=>24000,"00"=>12000);
		$freq["10"]=Array("11"=>32000,"10"=>16000,"00"=>8000);
		$mode=Array("00"=>"Stereo","01"=>"Joint stereo","10"=>"Dual channel","11"=>"Mono");
		$copy=Array("No","Yes");
		if(!$f=@fopen($file, "r")) { return -1; break; } else {
		// read first 4 bytes from file and determine if it is wave file if so, header begins five bytes after word 'data'		
		     $tmp=fread($f,4);
		     if($tmp=="RIFF") {
		       $idtag["ftype"]="Wave";
		       fseek($f, 0);
		       $tmp=fread($f,128);
		       $x=StrPos($tmp, "data");
		       fseek($f, $x+8);
		       $tmp=fread($f,4);
		     }
		// now convert those four bytes to BIN. maybe it can be faster and easier. dunno how yet. help?
		     for($y=0;$y<4;$y++) {
		       $x=decbin(ord($tmp[$y]));
		       for($i=0;$i<(8-StrLen($x));$i++) {$x.="0";}
		       $bajt.=$x;
		     }
		// every mp3 framesynch begins with eleven ones, lets look for it. if not found continue looking for some 1024 bytes (you can search multiple for it or you can disable this, it will speed up and not many mp3 are like this. anyways its not standart)
		//     if(substr($bajt,1,11)!="11111111111") {
		//        return -1;
		//        break;
		//     }
		     if(substr($bajt,1,11)!="11111111111") {
		       fseek($f, 4);
		       $tmp=fread($f,2048);
		         for($i=0;$i<2048;$i++){
		           if(ord($tmp[$i])==255 && substr(decbin(ord($tmp[$i+1])),0,3)=="111") {
		              $tmp=substr($tmp, $i,4);
		              $bajt="";
		              for($y=0;$y<4;$y++) {
		                $x=decbin(ord($tmp[$y]));
		                for($i=0;$i<(8-StrLen($x));$i++) {$x.="0";}
		                $bajt.=$x;
		              }
		              break;
		            }
		          }
		     }
		     if($bajt=="") {
		        return -1;
		        break;
		     }
		// now parse all the info from frame header
		     $len=filesize($file);
		     $idtag["version"]=$version[substr($bajt,11,2)];
		     $idtag["layer"]=$layer[substr($bajt,13,2)];
		     $idtag["crc"]=$crc[$bajt[15]];
		     $idtag["bitrate"]=$bitrate[substr($bajt,16,4)][$bitindex[substr($bajt,11,4)]];
		     $idtag["frequency"]=$freq[substr($bajt,20,2)][substr($bajt,11,2)];
		     $idtag["padding"]=$copy[$bajt[22]];
		     $idtag["mode"]=$mode[substr($bajt,24,2)];
		     $idtag["copyright"]=$copy[$bajt[28]];
		     $idtag["original"]=$copy[$bajt[29]];
		// lets count lenght of the song
			 if ($idtag["bitrate"] != 0 && $idtag["frequency"] != 0) {
			     if($idtag["layer"]==1) {
			       $fsize=(12*($idtag["bitrate"]*1000)/$idtag["frequency"]+$idtag["padding"])*4;
			     } else {
			       $fsize=144*(($idtag["bitrate"]*1000)/$idtag["frequency"]+$idtag["padding"]);
			     }
			     // Modified by Luca (18/02/01): devel@lluca.com
			     $idtag["lenght_sec"]=round($len/round($fsize)/38.37);
			     // end
			 } else {
			 	$idtag["length_sec"] = 0;
			 }
			 $idtag["lenght"]=date("i:s",$idtag["length_sec"]);
		// now lets see at the end of the file for id3 tag. if exists then  parse it. if file doesnt have an id 3 tag if will return -1 in field 'tag' and if title is empty it returns file name.
		     if(!$len)
		     	$len = filesize($file);
		     fseek($f, $len-128);
		     $tag = fread($f, 128);
		     if(substr($tag,0,3)=="TAG") {
		       $idtag["file"]=$file;
		       $idtag["tag"]=-1;
		       // Modified by Luca (18/02/01): devel@lluca.com
		       $idtag["title"]=$this->strip_nulls( substr($tag,3,30) );
		       $idtag["artist"]=$this->strip_nulls( substr($tag,33,30) );
		       $idtag["album"]=$this->strip_nulls( substr($tag,63,30) );
		       $idtag["year"]=$this->strip_nulls( substr($tag,93,4) );
		       $idtag["comment"]=$this->strip_nulls( substr($tag,97,30) );
		       // If the comment is less than 29 chars, we look for the presence of a track #
		       if ( strlen( $idtag["comment"] ) < 29 ) {
		         if ( ord(substr($tag,125,1)) == chr(0) ) // If char 125 is null then track (maybe) is present
		           $idtag["track"]=ord(substr($tag,126,1));
		         else // If not, we are sure is not present.
		           $idtag["track"]=0;
		       } else { // If the comment is 29 or 30 chars long, there's no way to put track #
		         $idtag["track"]=0;
		       }
		       $idtag["genreid"]=ord(substr($tag,127,1));
		       $idtag["genre"]=$genres[$idtag["genreid"]];
		       $idtag["filesize"]=$len;
		     } else {
		       $idtag["tag"]=0;
		     }
		// close opened file and return results.
		   if(!$idtag["title"]) {
		     $idtag["title"]=str_replace("\\","/", $file);
		     $idtag["title"]=substr($idtag["title"],strrpos($idtag["title"],"/")+1, 255);
		   }
		   fclose($f);
		   return $idtag;
		}
	}
	function strip_nulls( $str ){
		$res = explode( chr(0), $str );
		return chop( $res[0] );
	}
	function interpret_ID3_to_HTML($id3_data){
		$title = (!empty($id3_data["title"])) ? $id3_data["title"] : "no title";
		$artist = (!empty($id3_data["artist"])) ? $id3_data["artist"] : "no artist";
		$album = (!empty($id3_data["album"])) ? $id3_data["album"] : "no album";
		$year = (!empty($id3_data["year"])) ? $id3_data["year"] : "no year";
		$comment = (!empty($id3_data["comment"])) ? $id3_data["comment"] : "no comment";
		$genre = (!empty($id3_data["genre"])) ? $id3_data["genre"] : "no genre";
		$html = ("\t\t<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"70%\">\n"
		 . "\t\t<tr>\n"
		 . "\t\t<td align=\"left\">"._ZOOM_ID3_LENGTH."</td>\n"
		 . "\t\t<td align=\"left\">".$id3_data["length"]."</td>\n"
		 . "\t\t</tr>"
		 . "\t\t<tr>\n"
		 . "\t\t<td align=\"left\">"._ZOOM_ID3_TITLE."</td>\n"
		 . "\t\t<td align=\"left\">".$title."</td>\n"
		 . "\t\t</tr>"
		 . "\t\t<tr>\n"
		 . "\t\t<td align=\"left\">"._ZOOM_ID3_ARTIST."</td>\n"
		 . "\t\t<td align=\"left\">".$artist."</td>\n"
		 . "\t\t</tr>"
		 . "\t\t<tr>\n"
		 . "\t\t<td align=\"left\">"._ZOOM_ID3_ALBUM."</td>\n"
		 . "\t\t<td align=\"left\">".$album."</td>\n"
		 . "\t\t</tr>"
		 . "\t\t<tr>\n"
		 . "\t\t<td align=\"left\">"._ZOOM_ID3_YEAR."</td>\n"
		 . "\t\t<td align=\"left\">".$year."</td>\n"
		 . "\t\t</tr>"
		 . "\t\t<tr>\n"
		 . "\t\t<td align=\"left\">"._ZOOM_ID3_COMMENT."</td>\n"
		 . "\t\t<td align=\"left\">".$comment."</td>\n"
		 . "\t\t</tr>"
		 . "\t\t<tr>\n"
		 . "\t\t<td align=\"left\">"._ZOOM_ID3_GENRE."</td>\n"
		 . "\t\t<td align=\"left\">".$genre."</td>\n"
		 . "\t\t</tr>\n"
		 . "\t\t</table>");
		 return $html;
	}
	//--------------------Error handling functions-------------------------//
	function displayErrors(){
        global $zoom;
		if ($this->_err_num <> 0){
			echo '<center><table border="0" cellpadding="3" cellspacing="0" width="70%">';
			echo '<tr class="sectiontableheader"><td width="100" align="left">Image Name</td><td align="left">Error type</td></tr>';
			$tabcnt = 0;
			for ($x = 0; $x <= $this->_err_num; $x++){
				echo '<tr class="'.$zoom->tabclass[$tabcnt].'" align="left"><td>'.$this->_err_names[$x].'</td><td align="left">'.$this->_err_types[$x].'</td></tr>';
				if ($tabcnt == 1){
	    			$tabcnt = 0;
				} else {
					$tabcnt++;
	    		}
			}
			echo "</table></center>";
		}
	}
	//--------------------END error handling functions----------------------//
}

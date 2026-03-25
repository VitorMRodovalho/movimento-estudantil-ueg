<?php
/*
 * $RCSfile: UnixPlatform.class,v $
 *
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2004 Bharat Mediratta
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
/**
 * @version $Revision: 1.30 $ $Date: 2004/10/25 08:10:45 $
 * @package GalleryCore
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * Load required class
 */
require_once(dirname(__FILE__) . 'platform.class');

/**
 * An Unix version of the GalleryPlatform class
 * 
 * @package GalleryCore
 * @subpackage Platform
 * @access public
 */
class UnixPlatform extends platform {

    /**
     * @see GalleryPlatform::exec()
     */
    function exec($cmdArray) {
	/* Assemble the command array into a pipeline */
	$command = '';
	foreach ($cmdArray as $cmdAndArgs) {
	    if (strlen($command)) {
		$command .= ' | ';
	    }
	    
	    foreach ($cmdAndArgs as $arg) {
		if ($arg === '>') {
		    $command .= '>';
		} else {
		    $command .= ' "' . $arg . '" ';
		}
	    }
	}
	$results = array();
	exec($command, $results, $status);

	list ($ret, $expected) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'exec.expectedStatus');
	if ($ret->isError()) {
	    $expected = 0;
	}

	$stderr = array();
	if ($this->file_exists($debugFile)) {
	    if ($this->filesize($debugFile) > 0) {
		if ($fd = $this->fopen($debugFile, "r")) {
		    while (!$this->feof($fd)) {
			$buf = $this->fgets($fd, 4096);
			$buf = rtrim($buf);
			if (!empty($buf)) {
			    $stderr[] = $buf;
			}
		    }
		    $this->fclose($fd);
		}
	    }
	    $this->unlink($debugFile);
	}
	
	return array($status == $expected, $results, $stderr);
    }
    
    /**
     * @see GalleryPlatform::isRestrictedByOpenBaseDir
     */
    function isRestrictedByOpenBaseDir($path) {
	$baseDirArray = split(':', ini_get('open_basedir'));

	if (empty($baseDirArray)) {
	    return false;
	}
	
	foreach ($baseDirArray as $baseDir) {
	    if (!strncmp($baseDir, $path, strlen($baseDir))) {
		return false;
	    }
	}
	
	return true;
    }

    /**
     * @see GalleryPlatform::splitPath
     */
    function splitPath($path) {
	$slash = $this->getDirectorySeparator();
	$list = array();
	foreach (explode($slash, $path) as $element) {
	    if (!empty($element)) {
		$list[] = $element;
	    } else if (empty($list)) {
		$list[] = $slash;
	    }
	}
	return $list;
    }

    /**
     * @see GalleryPlatform::isSymlinkSupported
     */
    function isSymlinkSupported() {
	return true;
    }
}
?>

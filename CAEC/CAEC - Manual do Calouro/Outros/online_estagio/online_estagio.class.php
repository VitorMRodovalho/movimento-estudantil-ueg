<?php
// online_estagio class definition

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// State info
// -3 = rejected
// -2 = unapproved
// -1 = archived
// 0 = unpublished
// 1 = published

// Job type
// 0 = Full time
// 1 = Part time
// 2 = Internship

class mosJobPosting extends mosDBTable {
/** @var int Primary key */
	var $id=null;
/** @var string */
	var $title=null;
/** @var int */
	var $jobtype=null;
/** @var string */
	var $description=null;
/** @var string */
	var $qualifications=null;
/** @var string */
	var $compensation=null;
/** @var int */
	var $showcomp=null;
/** @var string */
	var $applyinfo=null;
/** @var string */
	var $company=null;
/** @var string */
	var $address1=null;
/** @var string */
	var $address2=null;
/** @var string */
	var $city=null;
/** @var string */
	var $companyurl=null;
/** @var string */
	var $contactname=null;
/** @var string */
	var $contactphone=null;
/** @var string */
	var $contactemail=null;
/** @var int */
	var $showcontact=null;
/** @var string */
	var $memberid=null;
/** @var string */
	var $reference=null;
/** @var string */
	var $location=null;
/** @var string */
	var $attribs=null;
/** @var int */
	var $state=null;
/** @var datetime */
	var $created=null;
/** @var int User id*/
	var $created_by=null;
/** @var datetime */
	var $modified=null;
/** @var int User id*/
	var $modified_by=null;
/** @var boolean */
	var $checked_out=null;
/** @var time */
	var $checked_out_time=null;
/** @var int */
	var $hits=null;
/** @var int */
	var $version=null;
/** @var int */
	var $access=null;
/** @var int */
	var $ordering=null;

/**
* @param database A database connector object
*/
	function mosJobPosting( &$db ) {
		$this->mosDBTable( '#__jl_jobposting', 'id', $db );
	}

	function check() {
		if ( trim( $this->title ) == '' ) {
			$this->_error = _JL_ERR_NOTITLE;
			return false;
		}
		return true;
	}

/**
* Search method
*
* The sql must return the following fields that are used in a common display
* routine: href, title, section, created, text, browsernav
* @param string Target search string
* @param integer The state to search for -1=archived, 0=unpublished, 1=published [default]
* @param string A prefix for the section label, eg, 'Archived '
*/
	function search( $text, $jobtype=-1, $ordering='created DESC', $limitstart="", $limit="", &$totalRows, $state='1', $sectionPrefix='' ) {
		global $my, $cfgjl;
		global $mosConfig_abolute_path, $mosConfig_lang;

		$text = strtolower( trim( $text ) );
		$state = intval( $state );
		/*
		if ($text == '') {
			return array();
		}
		*/

		if ( $limit > 0 ) {
			$limitquery = "\nLIMIT $limitstart, $limit";
		}

		$whereOR = array();
		$whereOR[] = "LOWER(c.title) LIKE '%$text%'";
		$whereOR[] = "LOWER(c.description) LIKE '%$text%'";
		$whereOR[] = "LOWER(c.qualifications) LIKE '%$text%'";
		$whereOR[] = "LOWER(c.company) LIKE '%$text%'";
		$whereOR[] = "LOWER(c.location) LIKE '%$text%'";
		$whereOR[] = "LOWER(c.city) LIKE '%$text%'";
		$whereOR[] = "LOWER(c.reference) LIKE '%$text%'";

		$whereAND = array();
		$whereAND[] = "1=1";

		// Only show one job type
		if ( trim( $jobtype ) && $jobtype >= 0 ) {
			$type = intval( $jobtype );
			$whereAND[] = "c.jobtype = $type";
		}

		// Check out of we need to show only published items
		if ( $cfgjl['publishinglimit'] > 0 ) {
			$ptime = date( "Y-m-d H:i:s", time() - intval( $cfgjl['publishinglimit'] ) * 86400 );
			$whereAND[] = "c.created >= '$ptime'";
		}

		$this->_db->setQuery( "SELECT *"
		. "\nFROM #__jl_jobposting AS c"
		. "\nWHERE (".(implode( ' OR ', $whereOR ) ).")"
	    . "\n	AND (".(implode( ' AND ', $whereAND ) ).")"
		. "\n	AND c.state='$state' AND c.access<='$my->gid'"
		. "\nORDER BY $ordering"
        . "$limitquery"
		);
		#. "\nINNER JOIN #__categories AS b ON b.id=a.catid AND b.access <='$my->gid'"
		#print $this->_db->getQuery();

		$list = $this->_db->loadObjectList();

		$this->_db->setQuery( "SELECT count(id)"
		. "\nFROM #__jl_jobposting AS c"
		. "\nWHERE (".(implode( ' OR ', $whereOR ) ).")"
	    . "\n	AND (".(implode( ' AND ', $whereAND ) ).")"
		. "\n	AND c.state='$state' AND c.access<='$my->gid'"
		);
		$totalRows = $this->_db->loadResult();

		return $list;
	}


}

?>

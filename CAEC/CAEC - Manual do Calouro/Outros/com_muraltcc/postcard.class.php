<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );


class mosPostcardConfig extends mosDBTable
{
	var $id			= null;
	var $footertext	= "";
	var $view_in_template = 1;
	
	function mosPostcardConfig( &$_db )
	{
		$this->mosDBTable( '#__postcard_config', 'id', $_db );
	}
}


class mosPostcard extends mosDBTable
{
	var $id			 = null;
	var $pname		 = "";
	var $filename	 = "";
	var $id_category = 0;
	
	function mosPostcard( &$_db )
	{
		$this->mosDBTable( '#__postcard', 'id', $_db );
	}
}


class mosPostcardSent extends mosDBTable
{
	var $id				= null;
	var $date			= "";
	var $id_postcard	= 0;
	var $from_email		= "";
	var $from_name		= "";
	var $to_email		= "";
	var $to_name		= "";
	var $subject		= "";
	var $message		= "";
	
	function mosPostcardSent( &$_db )
	{
		$this->mosDBTable( '#__postcard_sent', 'id', $_db );
	}
}


class mosPostcardCategory extends mosDBTable
{
	var $id				= null;
	var $cname			= "";
	var $description	= "";
	var $style			= "";
	var $ordering		= 0;
	
	function mosPostcardCategory( &$_db )
	{
		$this->mosDBTable( '#__postcard_category', 'id', $_db );
	}
}
?>
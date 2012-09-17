<?php defined('ACCESS') or die("No direct script access allowed");


class DApcCache extends CApcCache
{
	protected $_cacheId;
	
	public function __construct($id)
	{
		$this->_cacheId = $id;
		$this->init();
	}
}
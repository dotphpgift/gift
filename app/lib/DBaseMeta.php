<?php defined('ACCESS') or die("No direct script access allowed");

class DBaseMeta
{
	protected $_meta;
	
	public static function getInstance($metadatas)
	{
		static $instance;
		if(!is_object($instance))
		{
			$instance = new DBaseMeta($metadatas);
		}
		return $instance;
	}
	
	public function getMeta()
	{
		return $this->_meta;
	}
	
	public function get($name)
	{
		if(!empty($name) && method_exists($this, $name))
			return $this->$name();
		else
			return array();
	}
	
	public function __construct($metadatas)
	{
		$this->_meta = $metadatas->getMeta();
	}
}
<?php defined('ACCESS') or die("No direct script access allowed");

abstract class DBaseFormModel extends CFormModel implements IBaseModel
{
	protected $_mdata;
	protected $_attribute;
	protected $_property;
	
	public static function getInstance($scenario, $classname,$metaname=NULL)
	{
		static $instance = array();
		if(!isset($instance[$classname]))
			$instance[$classname] = new $classname($scenario, $metaname);
		
		return $instance[$classname];	
	}
	
	public function __construct($scenario, $metaname=NULL)
	{
		if($metaname===null)
			$metaname = $this->getContext();
			
		$this->setMData($metaname);
		$this->_attribute = array_keys($this->getMData('attributeLabels'));
		parent::__construct($scenario);
	}
	
	public function getMData($key=null)
	{
		$metaname = $this->getContext();
		return $key===null ? $this->_mdata[$metaname]:$this->_mdata[$metaname]->itemAt($key)===null ? array():$this->_mdata[$metaname]->itemAt($key);
	}
	
	public function __get($name)
	{		
		if(in_array($name,$this->_attribute))
			return $this->_property[$name];
	}
	
	public function __set($name, $value)
	{
		if(in_array($name,$this->_attribute))
			$this->_property[$name] = $value;
	}
	
	public function setMData($metaname)
	{
		$this->_mdata[$metaname] = DBase::controller()->getMeta($metaname);
	}
	
	abstract public function getContext();
}
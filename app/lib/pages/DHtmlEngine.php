<?php defined('ACCESS') or die("No direct script access allowed");

abstract class DHtmlEngine extends CComponent
{
	private $_parent;
	public $attributes=array();
	
	abstract public function render();
	
	public function __construct($config, $parent)
	{
		$this->configure($config);
		$this->_parent=$parent;
	}
	
	public function __set($name,$value)
	{
		$setter='set'.$name; 
		if(method_exists($this,$setter))
			$this->$setter($value);
		else
			$this->attributes[$name]=$value;
	}
	
	public function __get($name)
	{
		$getter='get'.$name;
		if(method_exists($this,$getter))
			return $this->$getter();
		else if(isset($this->attributes[$name]))
			return $this->attributes[$name];
		else
			throw new CException(DBase::t('system','Property "{class}.{property}" is not defined.',
				array('{class}'=>get_class($this), '{property}'=>$name)));
	}
	
	public function configure($config)
	{
		if(is_array($config))
		{
			foreach($config as $name=>$value)
				$this->$name=$value;
		}
	}
	
	
	public function getParent()
	{
		return $this->_parent;
	}
	
	public function output()
	{
		return $this->render();
	}
}

class DHCollections extends DMap
{
	private $_el;
	private $_isTag;
	public function __construct($el, $isTag='header')
	{
		parent::__construct();
		$this->_el=$el;
		$this->_isTag=$isTag;
	}
}

class DCollections extends DMap
{
	private $_class;
	private $_isColumns;
	private $_isRow;
	private $_element;
	
	public function __construct($class, $element=null, $isRow=false, $isColumns=false)
	{
		parent::__construct();
		$this->_class=$class;
		$this->_element= $element;
		$this->_isColumns=$isColumns;
		$this->_isRow=$isRow;
	}
	
	public function add ($key, $value)
	{ 
		if(is_array($value)) 
		{ 
			if($this->_isColumns || $key==='columns')
			{
				//predie($value);
				$el=new DColumnsEl($value, $this->_class); 
				parent::add($key,$el); 
			}
			else if($this->_isRow)
			{
				$el=new DRowEl($value,$this->_class);
				parent::add($key, $el);
			}
			else
			{
				if($this->_element==='beforeHeader')
				{
					$el=new DHtmlProcessor($value,$this->_class);
					parent::add($key, $el);  
				}
				else if($this->_element==='header')
				{
					$el=new DHtmlProcessor($value,$this->_class);
					parent::add($key, $el);  
				}
				else if($this->_element==='afterHeader')
				{
					$el=new DHtmlProcessor($value,$this->_class);
					parent::add($key, $el);  
				}
				else
				{
					$v[$key]=$value; 
					parent::add($key, new DHtmlProcessor($v,$this->_class));
				}
			}
		}
		else
		{
			$el=new DStringEl(array('content'=>$value),$this->_class);
			parent::add($key,$el);				
		}
	}
}
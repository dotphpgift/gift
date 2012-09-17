<?php defined('ACCESS') or die("No direct script access allowed");

abstract class DFormElements  extends CComponent
{
	public $attributes=array();

	private $_parent;
	private $_visible;
	
	abstract function render();
	
	public function __construct($config,$parent)
	{
		$this->configure($config);
		$this->_parent=$parent;
	}
	
	public function __get($name)
	{
		$getter='get'.$name;
		if(method_exists($this,$getter))
			return $this->$getter();
		else if(isset($this->attributes[$name]))
			return $this->attributes[$name];
		else
			throw new CException(Yii::t('yii','Property "{class}.{property}" is not defined.',
				array('{class}'=>get_class($this), '{property}'=>$name)));
	}
	
	public function __toString()
	{
		return $this->render();
	}
	
	public function __set($name,$value)
	{
		$setter='set'.$name;
		if(method_exists($this,$setter))
			$this->$setter($value);
		else
			$this->attributes[$name]=$value;
	}
	
	public function configure($config)
	{
		if(is_string($config))
			$config=require(Yii::getPathOfAlias($config).'.php');
		if(is_array($config))
		{
			foreach($config as $name=>$value)
				$this->$name=$value;
		}
	}
	
	public function getVisible()
	{
		if($this->_visible===null)
			$this->_visible=$this->evaluateVisible();
		return $this->_visible;
	}
	
	public function getParent()
	{
		return $this->_parent;
	}
	
	protected function evaluateVisible()
	{
		return true;
	}	
}

class DButtonCollection extends DMap
{
	private $_form;
	private $_isButton;
	
	public function __construct($form,$isButton=false)
	{
		parent::__construct();
		$this->_form=$form;
		$this->_isButton=$isButton;
	}
	
	public function add($key,$value)
	{
		if(is_array($value))
		{
			if(is_string($key))
				$value['name'] = $key;
				
			$element = new DFormButtonElement($value, $this->_form);
		}
		parent::add($key,$element);	
	}
}

class DElementCollection extends DMap
{
	private $_form;
	private $_isCol;
	
	public function __construct($form,$isCol=false)
	{
		parent::__construct();
		$this->_form=$form;
		$this->_isCol=$isCol;
	}
	
	public function add($key,$value)
	{		
		if(is_array($value))
		{
			if(is_string($key))
			{
				$value['name']=$key; 
			}
			
			if($this->_isCol)
			{
				$value['key'] = $key;
				$element = new DFormInputElement($value, $this->_form); 
			}
			else
			{
				if(!isset($value['type']))
					$value['type']='text';
					
				if(!strcasecmp(substr($value['type'],-4),'form'))	// a form
				{
					$class=$value['type']==='form' ? get_class($this->_form) : DBase::import($value['type']);
					$element=new $class($value,null,$this->_form);
				}
				else
				{ 
					unset($value['type']);					
					if(isset($value['row']))
					{
						$value['row']['key'] = $key;
						$element = new DFormRowsEl($value['row'], null, $this->_form);
					}
				}				
			}				
		}
		parent::add($key,$element);		
	}
	
	public function remove($key)
	{
		if(($item=parent::remove($key))!==null)
			$this->_form->removedElement($key,$item,$this->_forButtons);
	}
}
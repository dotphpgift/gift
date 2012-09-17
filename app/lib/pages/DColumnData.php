<?php defined('ACCESS') or die("No direct script access allowed");

class DColumnData extends DColumn
{
	public $name;
	public $type='raw';
	public $value;	
	public $sortable;
	
	public function init()
	{
		parent::init();
		if($this->name===null && $this->value===null)
			throw new CException('Either "name" or "value" must be specified for DColumnData');
			
		if($this->id !== null)
			$this->htmlOptions['id'] = $this->id;
	}
	
	public function renderColumn()
	{
		$this->createCssClass($this->htmlOptions);
		$this->htmlOptions['class'] = implode(' ', $this->cssClass);
		if($this->value !== null)
			$value = $this->value;
		else if($this->name !== null)
			$value = $this->getController()->clips[$this->name];
		
		if(!$this->noWrap)	
			echo DHtml::openTag($this->tagName, $this->htmlOptions);
		echo $value===null ? $this->getRow()->nullDisplay : $this->getRow()->getFormatter()->format($value,$this->type);
		if(!$this->noWrap)	
			echo DHtml::closeTag($this->tagName);;
	}
	
	protected function createCssClass($options)
	{
		$cssClass = array();
		if(isset($options['class']))
		{
			if(is_string($options['class']))
				$cssClass = array($options['class']);
			else
				$cssClass = $options['class'];
		}
		$this->cssClass = array_merge($this->cssClass, $cssClass );
	}
}
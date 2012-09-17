<?php defined('ACCESS') or die("No direct script access allowed");

class DFormView extends CForm
{
	private $_opt;
	private $_groups;
	
	public function getGroups()
	{
		return $this->_groups;
	}
	
	public function setGroups($groups)
	{
		$this->_groups = new CMap($groups,false);
	}
	
		
	public function renderBody()
	{
		$output='';   
		if($this->title!==null)
		{
			if($this->getParent() instanceof self)
			{
				$attributes=$this->attributes;
				unset($attributes['name'],$attributes['type']);
				$output=CHtml::openTag('fieldset', $attributes)."<legend>".$this->title."</legend>\n";
			}
			else
				$output="<fieldset>\n<legend>".$this->title."</legend>\n";
		}

		if($this->description!==null)
			$output.="<div class=\"description\">\n".$this->description."</div>\n";

		if($this->showErrorSummary && ($model=$this->getModel(false))!==null)
			$output.=$this->getActiveFormWidget()->errorSummary($model)."\n";

		$output.=$this->renderElements()."\n".$this->renderButtons()."\n";

		if($this->title!==null)
			$output.="</fieldset>\n";

		return $output;
	}
	
	public function renderElements()
	{

		$output='';
		foreach($this->getElements() as $element)
			$output.=$this->renderElement($element);
		return $output;
	}
	
	public function renderElement($element)
	{
		if(is_string($element))
		{			
			if(($e=$this[$element])===null && ($e=$this->getButtons()->itemAt($element))===null)
				return $element;
			else
				$element=$e;
		}
		if($element->getVisible())
		{
			if($element instanceof CFormInputElement)
			{
				if($element->type==='hidden')
					return "<div style=\"visibility:hidden\">\n".$element->render()."</div>\n";
				else
					return "<div class=\"row field_{$element->name}\">\n".$element->render()."</div>\n";
			}
			else if($element instanceof CFormButtonElement)
				return $element->render()."\n";
			else
				return $element->render();
		}
		return '';
	}
	
	public function setElements($elements)
	{
		$collection=$this->getElements();
		foreach($elements as $name=>$config)
			$collection->add($name,$config);
	}
}
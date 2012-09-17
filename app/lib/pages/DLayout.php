<?php defined('ACCESS') or die("No direct script access allowed");

class DLayout extends CWidget
{	
	public $docType;
	public $head;
	public $body;
	public $content;
	public $htmlOptions=array();
	public $controller;
	
	public $template = "{doctype}\n{dhtml}\n";
	
	public function init()
	{
		parent::init();
		if($this->docType==null)
			$this->docType = array('class' => 'DDoctype', 'type' => 'html5');
		
		if($this->head == null)
			$this->head = array('class' => 'DHead', 'items' => array());
			
		$this->htmlOptions['lang'] = DBase::getApp('language');
		if(!isset($this->htmlOptions['id']))
			$this->htmlOptions['id'] = strtolower(get_class($this)) . '-' . $this->controller->getId() . '-' . $this->controller->getAction()->getId();
	}
	
	public function renderHtml()
	{
		ob_start();
		echo preg_replace_callback("/{(\w+)}/",array($this,'renderSection'),$this->template);
		ob_end_flush();
	}
	
	public function run()
	{
		echo $this->renderHtml( );
	}
	
	protected function renderSection($matches)
	{
		$method='render'.$matches[1];
		if(method_exists($this,$method))
		{
			$this->$method();
			$html=ob_get_contents();
			ob_clean();
			return $html;
		}
		else
			return $matches[0];
	}
	
	protected function renderDoctype()
	{
		$doc = array();
		$class = 'DDoctype';
		
		
		if(is_array($this->docType))
		{
			$doc = $this->docType;
			if(isset($doc['class']))
			{
				$class = $doc['class'];
				unset($doc['class']);
			}
		}
		$this->widget($class, $doc);
	}
	
	protected function renderDHtml()
	{
		echo DHtml::openTag('html', $this->htmlOptions)."\n";
		echo $this->renderHead();
		echo $this->renderBody();
		echo DHtml::closeTag('html');
	}
	
	protected function renderHead()
	{
		$head = array();
		$class = 'DHead';
		if(is_array($this->head))
		{
			$head = $this->head;
			if(isset($head['class']))
			{
				$class = $head['class'];
				unset($head['class']);
			}
		}
		$this->widget($class, $head);
	}
	
	protected function renderBody()
	{
		$class = 'DBody';
		$htmlOptions = array();
		
		$this->widget($class, array(
				'content' => $this->content,
				'htmlOptions' => $htmlOptions,
				'boostrap' => $this
			)		
		);
	}
	
}
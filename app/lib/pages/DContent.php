<?php defined('ACCESS') or die("No direct script access allowed");

class DContent extends DDraw
{
	public $layout = "{beginContent}\n{content}\n{endContent}\n";
	public $items;
	public $isAjax;
	public $dataType;
	public $controller;
	public $view;
	public $xtype;
	
	protected $_beginContent;
	protected $_endContent;
	protected $_content;
	protected $_formClass='DFormProcessor';
	protected $_nestedFormClass='DNestedFormClass';
	
	public function init()
	{
		$this->configure();
	}
	
	public function renderItems()
	{		
		$output = array(
			'{beginContent}' => $this->renderBeginContent(),
			'{content}' => $this->renderContent(),
			'{endContent}' => $this->renderEndContent(),
		);
		return strtr($this->layout,$output);
	}
	
	protected function renderBeginContent()
	{
		return '<p>test</p>';
	}
	
	protected function renderEndContent()
	{
		return '<p>test123</p>';
	}
	
	protected function renderContent()
	{
		if( $this->xtype==='form')
		{
			$f= new $this->_formClass($this->_content, $this->controller->loadModel('form'));
		}		
		return $f;
	}
	
	protected function configure()
	{
		$this->_beginContent = DBase::getApp('htmlProcessor')->getBeginContent();
		$this->_endContent = DBase::getApp('htmlProcessor')->getAfterContent();
		$this->_content = DBase::getApp('htmlProcessor')->getContent();
	}
	
	protected function validateRequest()
	{
		
	}
}
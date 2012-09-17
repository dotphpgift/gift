<?php defined('ACCESS') or die("No direct script access allowed");

class DView
{
	protected $_a, $_m, $_v;
	
	public function __construct(DPageView $view)
	{
		$this->_a = $view->getAction();
		$this->_v = $view;
	}
	
	public function render($view = '/content')
	{
		$controller = $this->getController();
		$controller->dispatch($this);	
		$controller->render($view);	
	}
	
	public function dispatch()
	{
		return $this->runProcess();
	}
	
	protected function getView()
	{
		return $this->_v;
	}
	
	public function getAction()
	{
		return $this->_a;
	}
	
	public function getModel()
	{
		return $this->_m;
	}
	
	public function getController()
	{
		return DBase::getApp()->getController();
	}
	
	
	public function runProcess()
	{
		return $this->getView()->runProcess();
	}
}
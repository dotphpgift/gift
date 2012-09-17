<?php defined('ACCESS') or die("No direct script access allowed");

abstract class DSecureModule extends DModule implements IDModule
{
	abstract protected function getInfo();
	
	public $pageSize = 15;
	
	public function setInfo() 
	{
        	$this->controllerMap = $this->getInfo();	
    }	
	
	public function onBeginController($event)
	{
		$this->raiseEvent('onBeginController', $event);
	}
	
	public function onEndController($event)
	{
		$this->raiseEvent('onEndController', $event);
	}
	
	public static function getUrlRules()
	{
		$urlManager = DBase::getUrlManager();		
		foreach(DBase::getApp('metadata')->getModules() as $key => $module)
		{
			if( method_exists($module, 'urlRules') )
				$urlManager->addRules($module->urlRules());
		}
		return true;
	}
	
	public function getMeta()
	{
		return array(
		);
	}
}
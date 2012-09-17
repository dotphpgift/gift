<?php defined('ACCESS') or die("No direct script access allowed");

class BegainControllerBehavior extends CBehavior
{
	public function attach($owner)
	{
		$owner->attachEventHandler('onBeginController', array( $this, 'handleBeginController') );
	}
	
	public function handleBeginController($e)
	{
		if(!$e->sender instanceof IDModule)
			throw new CException('The Controller behavior not installed');
		
		if(!$e->params['controller'] instanceof DSecureController)
		{	/* Checking for Secure Page*/
			
		}
		DBase::beginProfile($e->params['controller']->getId() . 'Controller Execution Time');
		$controller = $e->params['controller'];
		$action = $e->params['action'];
		if( property_exists($controller->getModule(),'enableCache') )
			DBase::getApp('cache')->caching = $controller->getModule()->enableCache;
			
			
	}
	
}
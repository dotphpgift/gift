<?php defined('ACCESS') or die("No direct script access allowed");

class EndControllerBehavior extends CBehavior
{
	public function attach($owner)
	{
		$owner->attachEventHandler('onBeginController', array( $this, 'handleEndController') );
	}
	
	public function handleEndController($e)
	{
		DBase::beginProfile($e->params['controller']->getId() . 'Controller Execution Time');
		$module = $e->params['controller']->getModule();
		
	}
}
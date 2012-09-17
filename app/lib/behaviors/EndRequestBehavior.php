<?php defined('ACCESS') or die("No direct script access allowed");

class EndRequestBehavior extends CBehavior
{
	const ON_END = 'onEndRequest';
	
	public function attach($owner)
	{
		if(DBase::getApp()->isApplicationInstalled())
		{
			$owner->attachEventHandler( self::ON_END, array($this, 'handleCfgEnd') );
		}
		$owner->attachEventHandler( self::ON_END, array($this, 'handleAppEnd') );
	}
	
	public function handleCfgEnd($event)
	{
		$cfg = DBase::getApp('cfg')->handleRequestEnds();
	}
	
	public function handleAppEnd($event)
	{
		//echo "ddd";
		//DBase::getApp()->end();
	}
}
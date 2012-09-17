<?php defined('ACCESS') or die("No direct script access allowed");

class BeginRequestBehavior extends CBehavior
{	
	public function attach($owner)
	{
		if(DBase::getApp()->isApplicationInstalled())
		{
			$owner->attachEventHandler('onBeginRequest', array($this, 'handleCfgBegin') );
			$owner->attachEventHandler('onBeginRequest', array($this, 'handleUrlRules') );
			//$owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
		}
		$owner->attachEventHandler('onBeginRequest', array($this, 'handleSetTimeZone') );
	}
	
	public function handleTest($event)
	{
		DBase::app()->params['d'] = 'Dhanapal';
	}
	
	public function handleCfgBegin($event)
	{
		$cfg = DBase::getApp('cfg');
		if(DBase::getApp()->isApplicationInstalled())
		{
			$cfg->setLoadDbItems(true);
			if($cfg->getLoadDbItems())
				$cfg->loadDbItems();
				
			$components = $cfg->getItem('components', array());
			
			$app = DBase::getApp();
			//$app->defaultController = $cfg->getItem('defaultController');
			
			$app->setComponents($components, true);			
			
		}		
	}
	
	public function handleBeginRequest($event)
	{
		if (DBase::getApp('user')->isGuest)
            {
                $allowedGuestUserUrls = array (
                    DBase::getApp()->createUrl('user/auth/login'),
                );
                $reqestedUrl = DBase::getApp('request')->getUrl();
                $isUrlAllowedToGuests = false;
                foreach ($allowedGuestUserUrls as $url)
                {
                    if (strpos($reqestedUrl, $url) === 0)
                    {
                        $isUrlAllowedToGuests = true;
                    }
                }
                if (!$isUrlAllowedToGuests)
                {
                    DBase::getApp('user')->loginRequired();
                }
            }
	}
	
	public function handleSetTimeZone($event)
	{
		if($timeZone = DBase::getApp()->params['timeZone'])
			DBase::getApp()->setTimeZone($timeZone);
		else
			DBase::getApp()->setTimeZone('UTC');
	}
	
	public function handleUrlRules($event)
	{
		return DSecureModule::getUrlRules();
	}
}
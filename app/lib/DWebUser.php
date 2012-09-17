<?php defined('ACCESS') or die("No direct script access allowed");

class DWebUser extends CWebUser
{
	public function init()
	{
		parent::init();
		
		if (! DBase::getApp('apiRequest')->isApiRequest())
		{
			
		}
	}
}
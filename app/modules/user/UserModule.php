<?php defined('ACCESS') or die("No direct script access allowed");

DBase::setPath('userMod', dirname(__FILE__));

class UserModule extends DSecureModule
{
	const LOGIN_BY_USERNAME		= 1;
	const LOGIN_BY_EMAIL		= 2;
	const LOGIN_BY_OPENID		= 4;
	const LOGIN_BY_FACEBOOK		= 8;
	const LOGIN_BY_TWITTER		= 16;
	
	public $enableCache = false;
	
	public function init()
	{
		$this->setInfo();
		parent::init();
		$this->setImport(array(
			'userMod.controllers.*',
			'userMod.models.*',
			'userMod.actions.*',
			'userMod.meta.*',
			'userMod.views.*')
		);
	}
	
	protected function getInfo()
	{
		return array(
			'modName' => get_class($this),
			'author' => 'Admin',
			'enableLogging' => true,
			'enableOnlineStatus' => true,
			'loginType' => 1,
			'offlineIndicationTime' => 300, // 5 Minutes
			'caseSensitiveUsers' => true,
			'passwordExpirationTime' => 30,
			'autoLogin' => false,
			'enableAuditTrail' => false,
			'enableCache' => true,
			'login' => array(
				'class' => 'LoginController',
				'pageTitle' => 'Welcome Page',
				'metaTag' => array(
					'keywords' => '',
					'description' => ''
				)
			)
		);
	}
	
	public function urlRules()
	{
		return array(
			'login' => 'user/login',
		);
	}
	
	public function __get($name)
	{
		if(isset($this->controllerMap[$name]))
			return $this->controllerMap[$name];
		else
			return parent::__get($name);
	}
	
	public function __set($name, $value)
	{
		if(isset($this->controllerMap[$name]))
			$this->controllerMap[$name] = $value; 
	}
}
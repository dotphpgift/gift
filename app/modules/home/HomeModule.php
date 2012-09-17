<?php defined('ACCESS') or die("No direct script access allowed");

DBase::setPath('homeMod', dirname(__FILE__));

class HomeModule extends DSecureModule
{
	public function init()
	{	
		$this->setInfo();
		parent::init();
		$this->setImport(array(
			'homeMod.controllers.*',
			'homeMod.models.*',
			'homeMod.actions.*',
			'homeMod.meta.*',
			'homeMod.views.*')
		);
		//DBase::getApp('clientScript')->registerMetaTag($this->author, 'author');
	}
	
	protected function getInfo()
	{
		return $info = array(
			'modName' => get_class($this),
			'author' => 'Admin',
			/*Module Desciption*/
			'description' => '',
			/*Controller*/
			'site' => array(
				'class' => 'SiteController',
				'pageTitle' => 'Welcome Page',
				'metaTag' => array(
					'keywords' => '',
					'description' => ''
				),
			),
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
	
	public function urlRules()
	{
		return array(
			'home' => 'home/site/default',
		);
	}
}
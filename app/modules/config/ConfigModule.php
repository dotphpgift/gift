<?php defined('ACCESS') or die("No direct script access allowed");

DBase::setPath('configMod', dirname(__FILE__));

class ConfigModule extends DSecureModule
{
	public function init()
	{	
		$this->setInfo();
		parent::init();
		$this->setImport(array(
			'configMod.controllers.*',
			'configMod.models.*',
			'configMod.actions.*',
			'configMod.metadata.*',
			'configMod.views.*')
		);
		//DBase::getApp('clientScript')->registerMetaTag($this->author, 'author');
	}
	
	protected function getInfo()
	{
		return $info = array(
			'name' => get_class($this),
			'author' => 'Admin',
			/*Module Desciption*/
			'description' => '',
			/*Controller*/
			'index' => array(
				'class' => 'IndexController',
				'pageTitle' => 'Welcome Page',
				'metaTag' => array(
					'keywords' => '',
					'description' => ''
				),
			),
		);		
	}
	
	public function getName()
	{
		return get_class($this);
	}
	
	public function __get($name)
	{
		if(isset($this->controllerMap[$name]))
			return $this->controllerMap[$name];
	}
	
	public function getMeta()
	{
		return array(			
		);
	}
}
<?php defined('ACCESS') or die("No direct script access allowed");

class DModule extends CWebModule 
{
	protected $_mdata;
	
	public function init()
	{		
		parent::init();
		$behaviors = array(
			'onBeginController' => array(
				'class' => 'application.lib.behaviors.BegainControllerBehavior'
			),
			'onEndController' => array(
				'class' => 'application.lib.behaviors.EndControllerBehavior'	
			),
		);
		$this->attachBehaviors($behaviors);		
		$this->setViewPath(DBase::getApp()->getBasePath().DS.'views');
	}
	
	public function getName()
	{
		return get_class($this);
	}
	
	public function beforeControllerAction($controller, $action)
	{
		if($this->hasEventHandler('onBeginController'))
		{
			$this->onBeginController(new CEvent($this, array(
					'controller' => $controller,
					'action' => $action					
					)
				)
			);
			return true;
		}
		else
			return false;
	}
	
	public function afterControllerAction($controller,$action)
	{
		if($this->hasEventHandler('onBeginController'))
		{
			$this->onEndController(new CEvent($this, array(
					'controller' => $controller,
					'action' => $action	
					)
				)			
			);
		}
	}
	
	public function loadMeta($metaname=null)
	{		
		if($metaname === null)
			$metaname = $this->getId().'.'.DBase::controller()->getId().'.'.DBase::controller()->getAction()->getId();
			
		$action = DBase::controller()->getAction();
		$actionID = $action->getId();
		
		if(strpos($metaname, '.') === false)
		{
			throw new CException('The Metaname does not exists in Module Controller');
		}
		else
		{
			$a = explode('.', $metaname);
			$moduleName = $a[0];
			$controllerName = $a[1];
			$actionName = $a[2];
		}
			
		if(!DBase::hasModule($moduleName))
			throw new CException('The Module name does not exists');
		else
		{
			if(! DBase::isController(DBase::module($moduleName)->getControllerPath(), $controllerName) )
				throw new CException('The ModuleController name does not exists');
		}
		
		$cacheGroup = 'modules.'.$moduleName.'.meta.'. $controllerName.$actionName;
		$datas=false;
		$cache = DBase::getApp('cache');		
		if(!is_null($cache))
			$datas = $cache->get($metaname.'Meta', $cacheGroup);
		
		if(!($datas))
		{
			$datas = array(); 
			$path = DBase::import('application.modules.'.$moduleName.'.meta.*');
			if(!is_dir($path))
				throw new CException($this->t('{path} is not a valid directory.', array('{path}'=> $path), 'error'));
				
			$dir = $controllerName.callFunc($actionName);
			foreach(array('rules','attributeLabels','relations','scenario','scopes','content') as $meta)
			{
				if(file_exists($metaFile = $path.DS.$dir.DS.$meta.'Meta'.EXT))  
				{ 
					$datas[$meta] = include($metaFile); 
				}
			}
			
			if(!is_null($cache))
				$cache->save($datas,$metaname.'Meta', $cacheGroup);
		}
		return  $this->_mdata = new CMap($datas);
	}
	
	public function __unset($offset)
	{
		$this->_mdata->remove($offset);
	}
	
	public function t($message, $params = array(), $category=null, $source=null,$language=null)
	{
		if(is_null($category))
			$category = strtolower($this->getName());	
		return DBase::t($category,$message,$params,$source,$language);
	}
}


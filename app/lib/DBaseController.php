<?php defined('ACCESS') or die("No direct script access allowed");

class DBaseController extends CController implements IDBaseController
{
	public $allowGuest = false, 
		   $params = array(), 
	       $menu=array(),
	       $defaultAction='default', 
	       $metaTag=array(),
	       $actionPath,
		   $isFixedWidth=false,
	       $layout='/main';
	protected $_prepareContent=array();
	
	public function init()
	{
		parent::init();
		$behaviors = array();
		$this->attachEventHandler('onBeforeRender',array($this,'registerCoreClientScripts'));
		$this->attachEventHandler('onBeforeRender',array($this,'loadPageLayout'));
	}
	
	public function beforeAction($action)
	{
		DBase::beginProfile($this->getId().$action->getId() . 'Action Execution Time');
		return true;
	}
	
	public function afterAction($action)
	{
		DBase::beginProfile($this->getId().$action->getId() . 'Action Execution Time');
	}
	
	public function designer($className,$properties=array())
	{
		return $this->widget($className, $properties, false);
	}
	
	public function onBeforeRender($event)
	{
		$this->raiseEvent('onBeforeRender', $event);
	}
	
	public function prepare($xtype, $action)
	{
		/*$model=null;
		if(in_array($xtype, $action->defaultXtype))
		{
			if($xtype==='form')
			{
				$model = $this->loadModel($xtype);
				$model->setScenario($action->scenario);
			}
		}*/
		return new DView($this->getViewClass($action));
	}
	
	public function loadModel($xtype='activeform', $module=null)
	{
		if($module === null)
			$module = $this->getModule();
			
		if($xtype==='form') 
			$modelClass = callFunc($module->getId()).callFunc($this->getId()).callFunc($xtype);
		else
			$modelClass = callFunc($module->getId());
			
		if(class_exists($modelClass))
			return $modelClass::getInstance();
	}
	
	public function getMeta($metaname=null)
	{
		return $this->getModule()->loadMeta($metaname);
	}
	
	public function beforeRender($view)
	{
		$this->onBeforeRender(new CEvent($this, array('action'=>$this->getAction())));
		return true;
	}
	
	public function dispatch(DView $view)
	{
		$this->_prepareContent = $view->dispatch();
	}
	
	public function prepareContent()
	{
		return $this->_prepareContent;
	}
	
	public function registerCoreClientScripts($event)
	{
		$cs=DBase::getApp('clientscript');
		$cs->registerCssFile(DBase::getApp('theme')->baseUrl.'/css/global.css');
		$cs->registerCoreScript('jquery');
	}
	
	protected function getViewClass($action, $needle='Action')
	{
		$name = get_class($action);
		if(($pos=strpos($name,$needle))!==false)
			$viewClass = substr_replace($name, 'View', $pos, strlen($needle));
		
		if(class_exists($viewClass))
			return new $viewClass($action);	
	}
	
	public function loadPageLayout($event)
	{
		$datas=false;
		if(DBase::getApp('request')->getIsAjaxRequest())
		{
			$action->controller->layout = false;
			return;
		}			
		$action = $event->params['action'];
		$moduleName = $action->controller->getModule()->getId();
		$path = DBase::import('application.modules.'.$moduleName.'.meta.*');
		$loc1 = $action->controller->getId().callFunc($action->getId()).DS.'layout.php';
		
		$path = DBase::getPath('application.views').DS.'layout';
		if(!$datas)
		{		
			$datas=array();
			foreach(DBase::getApp('htmlProcessor')->pageLayout as $pos)
			{
				if(file_exists($path.DS.$pos.EXT))
					DBase::getApp('htmlProcessor')->registerRows(require($path.DS.$pos.EXT), $pos);
			}
		}
	}
}
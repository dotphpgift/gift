<?php defined('ACCESS') or die("No direct script access allowed");

abstract class DPageView 
{
	protected $_parent;
	protected $_controller;
	protected $_model;
	protected $_mdata;	
	
		
	abstract public function getContext();
	
	public function __construct(DAction $parent, $metaname=NULL)
	{
		$this->_parent = $parent;
		$this->_controller = $parent->controller;
		
		if($metaname===null)
			$metaname = $this->getContext();
			
		$this->setMData($metaname); 
	}
	
	public function getMData($key=null)
	{
		$metaname = $this->getContext();
		return $key===null ? $this->_mdata[$metaname]:$this->_mdata[$metaname]->itemAt($key)===null ? array():$this->_mdata[$metaname]->itemAt($key);
	}
	
	public function getAction()
	{
		return $this->_parent;
	}
	
	public function getController()
	{
		return $this->_controller;
	}
	
	public function setMData($metaname)
	{
		$this->_mdata[$metaname] = DBase::controller()->getMeta($metaname);
	}
	
	public function runProcess($model=null)
	{		
		DBase::getApp('htmlProcessor')->registerContent($this->getMData('content'));		
		return array(
			'dataType' => $this->getAction()->dataType,
			'isAjax' => DBase::getApp('request')->getIsAjaxRequest(),
			'controller' => $this->getController(),
			'view' => $this->getController()->loadModel('form'),
			'xtype' => $this->getAction()->xtype
		);
	}	
}
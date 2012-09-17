<?php defined('ACCESS') or die("No direct script access allowed");

abstract class DBaseRecordModel extends CActiveRecord implements IBaseModel
{
	protected $_mdata;
	/*
		Model Initialize
		return object
	*/	
	public static function getInstance($className, $metaname)
	{		
		return parent::model($className); 
		//$model->setMData($metaname);
		/*if(isset(self::$_models[$className]))
			return self::$_models[$className];
		else
		{
			$model=self::$_models[$className]=new $className(null);
			$model->setMData($metaname);
			$model->_md=new CActiveRecordMetaData($model);
			$model->attachBehaviors($model->behaviors());
			return $model;
		}*/
	}
	
	public function __construct($scenario='insert', $metaname=NULL)
	{
		if($metaname===null)
			$metaname = $this->getContext();
			
		$this->setMData($metaname); 
		parent::__construct($scenario);
	}
	
	public function getMData($key=null)
	{
		$metaname = $this->getContext();
		return $key===null ? $this->_mdata[$metaname]:$this->_mdata[$metaname]->itemAt($key)===null ? array():$this->_mdata[$metaname]->itemAt($key);
	}
	
	public function setMData($metaname)
	{
		$this->_mdata[$metaname] = DBase::controller()->getMeta($metaname);
	}
	
	abstract public function getContext();
}
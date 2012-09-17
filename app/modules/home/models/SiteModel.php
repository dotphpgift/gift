<?php defined('ACCESS') or die("No direct script access allowed");

class SiteModel extends DBaseRecordModel
{
	const CONTEXT = 'home.site';
	
	public static function getInstance($className=__CLASS__, $metaname=SiteModel::CONTEXT)
	{
		return parent::getInstance($className, $metaname);
	}
	
	public function rules()
	{
		return $this->getMData('rules');
	}
	
	public function scopes()
	{
		return $this->getMData('scopes');
	}
	
	public function getContext()
	{
		return self::CONTEXT;
	}
	
	public function relations()
	{
		return $this->getMData('relations');
	}
	
	public function scenario()
	{
		return $this->getMData('scenario');
	}
	
	public function attributeLabel()
	{
		return $this->getMData('attributeLabel');
	}
	
	public function tableName()
	{
		return '{{config}}';
	}
}
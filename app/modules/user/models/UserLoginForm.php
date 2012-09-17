<?php defined('ACCESS') or die("No direct script access allowed");

class UserLoginForm extends DBaseFormModel
{
	const CONTEXT = 'user.login.default';
	
	public static function getInstance($scenario='',$classname = __CLASS__, $metaname=NULL)
	{
		return parent::getInstance($scenario, $classname, $metaname);
	}
	
	public function getContext()
	{
		return self::CONTEXT;
	}
	
	public function attributeLabels()
	{
		return $this->getMData('attributeLabels');
	}
	
	public function rules()
	{
		return $this->getMData('rules');
	}
	
	public function contentStructure()
	{
		return $this->getMData('contentStructure');
	}
}
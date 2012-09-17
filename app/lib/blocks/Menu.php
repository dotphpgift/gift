<?php defined('ACCESS') or die("No direct script access allowed");

class Menu extends TbMenu
{
	public function init()
	{
		parent::init();
		DBase::getApp('clientScript')->registerCssFile(DBase::getApp('theme')->baseUrl.'/css/navigation.css'); 
	}
}
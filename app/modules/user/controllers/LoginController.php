<?php defined('ACCESS') or die("No direct script access allowed");

class LoginController extends DBaseController
{
	public $allowGuest = true;	
	
	public function actions()
	{
		return array(
			'default' => array(
				'class' => 'LoginDefaultAction',
				'xtype' => 'form',
				'validate'=>true,
				'scenario' => 'login'
			),
		);
	}
}
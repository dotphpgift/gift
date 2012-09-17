<?php defined('ACCESS') or die("No direct script access allowed");

class SiteController extends DBaseController
{
	public $allowGuest = true;	
	
	public function actions()
	{
		return array(
			'default' => array(
				'class' => 'SiteDefaultAction'
			),
			'error'   => array(
				'class' => 'SiteErrorAction'
			)
		);
	}
}
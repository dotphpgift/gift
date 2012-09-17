<?php defined('ACCESS') or die("No direct script access allowed");

class TestController extends DBaseController
{
	public function actionIndex()
	{
		echo DBase::getApp('user')->getFlash('dhan');
	}
}
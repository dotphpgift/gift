<?php defined('ACCESS') or die("No direct script access allowed");

class SiteErrorAction extends DAction
{	
	public function run()
	{
		$controller=$this->getController();
		
		if($error=DBase::getApp('errorHandler')->error)
		{
			$controller->render('error', $error); die();
		}
	}
}
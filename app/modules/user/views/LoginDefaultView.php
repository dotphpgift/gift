<?php defined('ACCESS') or die("No direct script access allowed");

class LoginDefaultView extends DPageView
{
	const CONTEXT = 'user.login.default';  // ModuleName.Controller.Action
	
	public function __construct(DAction $parent, $model=null)
	{
		parent::__construct($parent, $model);
	}
	
	public function getContext()
	{
		return self::CONTEXT;
	}
}
<?php defined('ACCESS') or die("No direct script access allowed");

DBase::setPath('gnomeMod', dirname(__FILE__));

class GnomeModule extends DSecureModule
{
	public $password;
	public $ipFilters=array('127.0.0.1','::1');
}
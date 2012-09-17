<?php defined('ACCESS') or die("No direct script access allowed");

class DHttpRequest extends CHttpRequest
{
	public $csrfTokenName='dotPHP_CSRF_TOKEN';
}
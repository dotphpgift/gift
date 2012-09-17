<?php defined('ACCESS') or die("No direct script access allowed");

class DSession extends CHttpSession
{
	public static function createSessionToken($sessionId, $userPass)
	{
		$token = md5($sessionId . $userPass);
          return $token;
	}
}
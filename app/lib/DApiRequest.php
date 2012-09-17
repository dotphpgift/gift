<?php defined('ACCESS') or die("No direct script access allowed");

class DApiRequest extends CHttpRequest
{
	const REST           = 'REST';
     const SOAP           = 'SOAP';
     const JSON_FORMAT    = 'json';
     const XML_FORMAT     = 'xml';
	   
	public function isApiRequest()
	{
		try
		{
			$url = $this->getUrl();
		}
		catch(CException $e)
		{
			$url = '';
		}
		
		if (strpos($url, '/api/') !== false)
			return true;
		else
			return false;
	}
}
<?php defined('ACCESS') or die("No direct script access allowed");

require(DOTPHP);

class DBase extends Yii
{
	/*
	Setting Path Alias
	$alias: alias to the path
	$path: the path corresponding to the alias. If this is null, the corresponding path alias will be removed.
	return void 
	*/
	const CLIPWIDGET = "system.web.widgets.CClipWidget";
	
	public static function setPath($alias, $path)
	{
		parent::setPathOfAlias($alias, $path);
	}
	
	
	/**
	$alias: (e.g. system.web.Controller)
	return: file path corresponding to the alias, false if the alias is invalid.
	**/
	public static function getPath($alias)
	{
		return parent::getPathOfAlias($alias);
	}
	
	public static function getApp($key=NULL)
	{
		return ($key === NULL) ? parent::app() : parent::app()->$key;
	}
	
	public static function init()
	{		
		DBase::import('app.lib.DApplication', true);
		//DBase::import('app.lib.logging.DLogger', true);
	}
	
	public static function WebApp($config=null)
	{
		return parent::createApplication('DApplication',$config);
	}
	
	public static function Browser($name = null)
	{
		$b = parent::app()->getBrowser();
		if($name !== null)
		{
			if(is_array($name) && count($name))
			{
				$t=array();
				foreach($name as $k => $v)
				{
					$t[]= DBase::Browser($v);
				}
				return implode (' ', $t); 
			}
			$name = strtolower($name);
			if($pos = strpos($name, 'get') == 0)
				$method = 'get' . ucfirst(str_replace('get','',$name));
			else
				$method = 'get'. ucfirst($name);
			
			if(method_exists($b, $method))
				return $b->$method();		
		}
		return $b;
	}
	
	public static function getUser()
	{
		return self::getApp('user');
	}
	
	public static function getVersion($category='application',$repo=false)
	{
		if($category=='system')
			return parent::getVersion();
			
		return (!$repo) ? VERSION : substr(VERSION, 0, strpos(VERSION, '(') - (strlen(VERSION)+1) );
	}
	
	static public function generateId() 
	{
      	$id = uniqid() . time();
		$id = base_convert($id, 16, 2);
		$id = str_pad($id, strlen($id) + (8 - (strlen($id) % 8)), '0', STR_PAD_LEFT);

      	$chunks = str_split($id, 8);

		$id = array();
      	foreach ($chunks as $key => $chunk) 
		{
			if ($key & 1) // odd
            		array_unshift($id, $chunk);
          	else  // even
            		array_push($id, $chunk);
           }
		 return base_convert(implode($id), 2, 36);
   	}
	
	public static function getUrlManager()
	{
		return self::getApp('urlManager');
	}
	
	public static function hasModule($module)
	{
		return self::getApp('metadata')->hasModule($module);
	}
	
	public static function module($module) 
	{
		return self::getApp()->getModule($module);
	}
	
	public static function controller()
	{
		return self::getApp()->getController();
	}
	
	public static function isController($modulePath, $name)
    {
        $name = ucfirst(str_replace('Controller', '' , $name));
		return is_file($modulePath.DS.$name.'Controller.php');
    }
	
	public static function isPost()
	{
		return self::getApp('request')->getIsPostRequest();
	}
	
	
}

class DHtml extends CHtml
{	
}

function callFunc($id, $fn='ucfirst')
{
	if(function_exists($fn))
		return $fn($id);
	else
		return NULL;
}
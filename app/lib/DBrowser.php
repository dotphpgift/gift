<?php defined('ACCESS') or die("No direct script access allowed");


class DBrowser extends CApplicationComponent
{
	private $name;
     private $version;
     private $platform;
     private $userAgent;
	
	private static $_b=array();

     public function init()
     {
     	parent::init();
          $this->detect();
	}

     protected function detect()
     {
		$userAgent = null;
		if (isset($_SERVER['HTTP_USER_AGENT']))
		{
			$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
		}
		if (preg_match('/opera/', $userAgent))
          {
			$name = 'opera';
		}
		elseif (preg_match('/chrome/', $userAgent))
		{
			$name = 'chrome';
		}
		elseif (preg_match('/apple/', $userAgent))
		{
			$name = 'safari';
		}
		elseif (preg_match('/msie/', $userAgent))
		{
			$name = 'msie';
		}
		elseif (preg_match('/mozilla/', $userAgent) && !preg_match('/compatible/', $userAgent))
		{
			$name = 'mozilla';
		}
		else
		{
			$name = 'unrecognized';
		}
		
		if (preg_match('/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/', $userAgent, $matches)) // Not Coding Standard
		{
			$version = $matches[1];
		}
		else
		{
			$version = false;
		}
		
		if (preg_match('/linux/', $userAgent))
		{
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/', $userAgent))
		{
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/', $userAgent))
		{
			$platform = 'windows';
		}
		else
		{
			$platform = 'unrecognized';
		}
		$this->name         = $name;
		$this->version      = $version;
		$this->platform     = $platform;
		$this->userAgent    = $userAgent;
		
		self::setBrowser( array(
				'name' => $this->name,
				'version' => $this->version,
				'platform' => $this->platform,
				'userAgent' => $this->userAgent
			)
		);
	}

	public function getName()
	{
		return $this->name;
	}

	public function getVersion()
	{
		return $this->version;
	}

	public function getPlatform()
	{
		return $this->platform;
	}

	public function getUseragent()
	{
     	return $this->userAgent;
	}
	
	protected static function setBrowser($v)
	{
		self::$_b=$v;
	}
	
	public static function b()
	{
		return self::$_b;
	}
	
	public static function isFF()
	{
		return self::$_b['name'] === 'mozilla'; 
	}
	
	public static function firefoxVersion()
	{
		return DBrowser::isFF() ? DBrowser::$_b['version'] : NULL;
	}
	
	public static function isIE()
	{
		return self::$_b['name'] === 'msie'; 
	}
	
	public static function ieVersion()
	{
		return DBrowser::isIE() ? DBrowser::$_b['version'] : NULL;
	}
	
	public static function isIE7()
	{
		return DBrowser::isIE() && DBrowser::ieVersion() === '7.0';
	}
	
	public static function isIE8()
	{
		return DBrowser::isIE() && DBrowser::ieVersion() === '8.0';
	}
	
	public static function isIE9()
	{
		return DBrowser::isIE() && DBrowser::ieVersion() === '9.0';
	}
	
	public static function isOpera()
	{
		return self::$_b['name'] === 'opera'; 
	}
	
	public static function isSafari()
	{
		return self::$_b['name'] === 'safari'; 
	}
	
	public static function isEnableScript()
	{
		
	}
}
?>

<?php defined('ACCESS') or die("No direct script access allowed");

class DCaching extends CApplicationComponent
{
	public $driverMap = array(
		'cacheFile'  => 'DFileCache',
		'cacheApc'   => 'DApcCache',
		'cacheMem'   => 'DMemCache',
		'cacheDummy' => 'DDummyCache'
	);
	public $cacheId = 'file';	
	public $caching = false;
	public $expire = 3600;
	
	protected $_cache = array();	
	public $_id;
	public $_group;	
	
	public function init()
	{			
		parent::init();
		$this->setCacheId($this->cacheId);
	}
	
	public function setCacheId($driver = 'file')
	{
		$driver = 'cache' . ucfirst((strpos($driver, 'cache') == 0) ? str_replace('cache','',$driver) : $driver = strtolower($driver));
		if(array_key_exists($driver, $this->driverMap))
			$this->cacheId = $driver;
		else
			throw new CException('Invalid cacheId {example: file, apc, mem, dummy} ');
			
		if(!isset($this->_cache[$this->cacheId]))
			$this->_cache[$this->cacheId] = $this->createCacheInstance();
	}
	
	public function getCacheId()
	{
		return $this->cacheId;
	}
	
	public function getCache()
	{
		return $this->_cache[$this->cacheId];
	}
	
	private function createCacheInstance()
	{
		if($this->isCaching())
		{
			$cacheClass = $this->driverMap[$this->cacheId];
			DBase::import('system.caching.C' . substr($cacheClass, 1) );
			DBase::import('application.lib.caching.drivers.'.$cacheClass);
			return new $cacheClass($this);
		}
		return false;
	}
	
	public function isCaching()
	{
		return (bool)$this->caching;
	}
	
	public function getCachePath()
	{
		if($this->instance instanceof DFileCache)
			return $this->getCache()->cachePath;
		
		return null;
	}
	
	public function get($id, $group)
	{
		if(!$this->isCaching())
			return false;
			
		$this->_id = $id;
		$this->_group = $group;		
		return $this->getCache()->get($id);
	}
	
	public function save($data, $id, $group, $expire=null, $dependency=null)
	{
		if(!$this->isCaching())
			return false;

		$this->_id = $id;
		$this->_group = $group;
		if(is_null($expire))
			$expire = $this->expire;
		else if ($expire===0)
			$expire = time() + (365 * 24 * 60 * 60); // one year
		
		return $this->getCache()->set($id, $data, $expire, $dependency);
	}
	
	public function add($data, $id=null, $group=null)
	{
		if(!$this->isCaching())
			return false;			
		$this->getCache()->add($id, $data, $this->expire);
	}
	
	public function delete($id, $group)
	{
		if(!$this->isCaching())
			return false;
			
		$this->_id = $id;
		$this->_group = $group;
		
		$this->getCache()->delete($id);
	}
	
	public function flush()
	{
		if(!$this->isCaching())
			return false;
		$this->getCache()->flush();
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
	public function getGroup()
	{
		return $this->_group;
	}
}
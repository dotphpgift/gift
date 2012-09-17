<?php defined('ACCESS') or die("No direct script access allowed");


class DFileCache extends CFileCache
{
	protected $sourceSeparator = '.';
	protected $_cacheId;
	
	public function init()
	{
		parent::init();
	}
	
	public function __construct($id)
	{
		$this->_cacheId = $id;
		$this->init();
	}
	
	protected function getCacheFile($key)
	{
		$this->directoryLevel = 1;
		$group = trim($this->getGroup(), $this->sourceSeparator);
		$base = $this->cachePath . DIRECTORY_SEPARATOR . str_replace($this->sourceSeparator, DIRECTORY_SEPARATOR, $group).DIRECTORY_SEPARATOR.$key.$this->cacheFileSuffix;
		$this->reset();
		return $base;
	}
	
	public function getGroup()
	{
		return $this->getCacheId()->getGroup();
	}
	
	public function getId()
	{
		return $this->getCacheId()->getId();;
	}
	
	public function getCacheId()
	{
		return $this->_cacheId;
	}
	
	protected function reset()
	{
		$this->_cacheId->_id = null;
		$this->_cacheId->_group = null;
	}
}
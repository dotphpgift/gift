<?php defined('ACCESS') or die("No direct script access allowed");

class DUrlManager extends CUrlManager
{
	const CACHE_KEY='UrlRules';
	const CACHE_GROUP ='global';
	
	private $_rules=array();
	
	public function processRules()
	{
		if(empty($this->rules) || $this->getUrlFormat()===self::GET_FORMAT)
			return;
		if($this->cacheID!==false && ($cache=DBase::getApp()->getComponent($this->cacheID))!==null)
		{
			$hash=md5(serialize($this->rules));
			if(($data=$cache->get(self::CACHE_KEY, self::CACHE_GROUP))!==false && isset($data[1]) && $data[1]===$hash)
			{
				$this->_rules=$data[0];
				return;
			}
		}
		foreach($this->rules as $pattern=>$route)
			$this->_rules[]=$this->createUrlRule($route,$pattern);
		if(isset($cache))
			$cache->save(array($this->_rules,$hash),self::CACHE_KEY, self::CACHE_GROUP);
	}
}
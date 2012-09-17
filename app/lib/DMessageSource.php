<?php

class DMessageSource extends CMessageSource 
{

	const CACHE_KEY_PREFIX = 'messageSource.';

	/**
	 * @var integer the time in seconds that the messages can remain valid in cache.
	 * Defaults to 0, meaning the caching is disabled.
	 */
	public $cachingDuration = 0;
	
	/**
	 * @var string the base path for all translated messages. Defaults to null, meaning
	 * the "messages" subdirectory of the application directory (e.g. "protected/messages").
	 */
	public $basePath;

	/**
	 * @see		CMessageSource::init()
	 */
	public function init()
	{
		parent::init();
		if($this->basePath === null)
		{
			$this->basePath = DBase::getPath('application.language'); 
		}
	}

	/**
	 * Publishes all messages to JavaScript files to use on client side.
	 */
	public function publishJavaScriptMessages()
	{
		$language = Yii::app()->getLanguage();

		// Load date of last change
		$maxFiletime = null;
		$packages = array();
		$files = CFileHelper::findFiles($this->basePath . DIRECTORY_SEPARATOR . 'en');
		foreach($files as $file)
		{
			$basename = basename($file);
			$packages[] = substr($basename, 0, strpos($basename, '.'));
			$maxFiletime = max($maxFiletime, filemtime($file));
		}

		// Get asset manager
		$assetManager = Yii::app()->getAssetManager();
		$assetPath = $assetManager->getBasePath() . DIRECTORY_SEPARATOR . 'lang_js';

		// Check for changes
		$publish = false;
		if(!is_dir($assetPath))
		{
			mkdir($assetPath);
			$publish = true;
		}
		elseif(!is_file($assetPath . DIRECTORY_SEPARATOR . $language . '.js'))
		{
			$publish = true;
		}
		elseif(filemtime($assetPath . DIRECTORY_SEPARATOR . $language . '.js') < $maxFiletime)
		{
			$publish = true;
		}

		// Publish if needed
		if($publish || YII_DEBUG)
		{
			$code = '';
			foreach($packages as $package)
			{
				$code .= 'lang.' . $package . ' = [];' . "\n";
				$data = $this->loadMessages($package, $language);
				foreach($data AS $key => $value)
				{
					$code .= 'lang.' . $package . '["' . $key . '"] = ' . CJSON::encode($value) . ';' . "\n";
				}
			}
			file_put_contents($assetPath . DIRECTORY_SEPARATOR . $language . '.js', $code);
		}
	}

	/**
	 * @see		CMessageSource::loadMessages()
	 */
	public function loadMessages($category, $language)
	{
		// Caching things
		$cache = DBase::getApp()->getCache();
		$cacheKey = self::CACHE_KEY_PREFIX . $language . '.' . $category;
		$cacheGroup = self::CACHE_KEY_PREFIX . $language; 

		// Try to load messages from cache
		if(!is_null($cache) && ($data = $cache->get($cacheKey, $cacheGroup)) !== false)
		{
			return $data;
		}
		
		// Load parent messages
		/*if(strlen($language) > 2)
		{
			$messages = self::loadMessages($category, substr($language, 0, 2));
		}
		elseif($language != 'en')
		{
			$messages = self::loadMessages($category, 'en');
		}
		else
		{
			$messages = array();
		}*/

		// Try to load messages from file
		$messageFile = $this->basePath . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $category . '.xml'; 
		$content = null;
		if(is_file($messageFile))
			$content = file_get_contents($messageFile);
		elseif(is_file($messageFile . ".gz"))
			$content = gzuncompress(file_get_contents($messageFile . ".gz"));

		if($content)
		{
			$xml = simplexml_load_string($content);
			foreach($xml as $entry) 
			{
				$messages[(string)$entry->attributes()->id] = (string)$entry;
			}

			if(!is_null($cache))
				$cache->save($messages, $cacheKey, $cacheGroup, $this->cachingDuration);
		}		
		return $messages;
	}

}
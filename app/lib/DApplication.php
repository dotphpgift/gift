<?php defined('ACCESS') or die("No direct script access allowed");



class DApplication extends CWebApplication
{
	protected $installed;
	protected $license;
	
	private $_controllerPath;
	private $_viewPath;
	private $_systemViewPath;
	private $_layoutPath;
	private $_controller;
	private $_theme;
	
	
	public function __construct($config=null)
	{
		parent::__construct($config);		
		DBase::trace('Register ' . get_class($this), ' app.lib.DApplication');
	}
	
	protected function init()
	{
		parent::init();
		$this->sourceLanguage='xxx';		
		$asset = $this->getAssetManager();
			$asset->setBasePath(INSTANCE_ROOT.DS.'themes'. DS . DAssetManager::DEFAULT_BASEPATH);			
			$asset->setBaseUrl($this->getRequest()->getBaseUrl(). '/themes/' . DAssetManager::DEFAULT_BASEPATH);
			
			
		$this->setModules(array(
				'home' => array(), 
				'user' => array(),
				'config'=>array(),
				/*'gii' => array(
					'class' => 'system.gii.GiiModule',
					'password' => '04121976' 
				)*/
					
			)
		);
	}
	
	protected function registerCoreComponents()
	{
		$this->setRuntimePath(INSTANCE_ROOT.DS.'runtime');
		
		$this->setImport(
				array(
					'application.lib.*',
					'application.lib.behaviors.*',
					'application.lib.pages.*', 
					'application.lib.caching.DCaching',
					'application.extensions.debug.*',
					'application.lib.helpers.*',
					'application.lib.blocks.*',
					'application.lib.blocks.widgets.*'
				)
		);		
		parent::registerCoreComponents();
		$components = array(
			'themeManager' => array(
                'basePath' => INSTANCE_ROOT.DS.'themes',
            ),
			'browser' => array(
                	'class' => 'DBrowser',
            ),
			'assetManager' => array(
				'class' => 'DAssetManager'
			),
			'db' => array(
                'emulatePrepare' => true,
                'charset' => 'utf8',
				'enableProfiling' => true,
				'enableParamLogging' => true,
            ),
			'metadata' => array(
				'class' => 'DMetadata'
			),
			'session' => array(
				'savePath' => INSTANCE_ROOT.DS.'runtime'.DS.'session',
				'autoStart' => true,
			),
			'offline' => array(
				'class' => 'DOffline',
				'locked' => false,
				'allowedIPs' => array(),
				'redirectURL' => 'http://www.dotphp.in/maintence.html'
			),
			'crypt' => array(
				'class'=>'DEncrypter',
				'key'=>'dotPHP',
			),
			'cfg' => array(
				'class' => 'DCfg'
			),
			'cache' => array(
				'class' => 'DCaching',
				'caching' => true,
				'cacheId' => 'file'
			),
			'urlManager' => array(
				'class' => 'DUrlManager',
				'urlFormat' => 'path',
				'caseSensitive' => true,
				'showScriptName' => true,
			),
			'user' => array(
				'allowAutoLogin' => true,
				'loginUrl' => array('user/auth/login'),
				'class'	=> 'DWebUser'
			),
			'apiRequest' => array(
               	'class' => 'DApiRequest',
            	),
			'request' => array(
				'class' => 'DHttpRequest',
			),
			'messages' => array(
		    	'class' => 'DMessageSource',
				'cachingDuration' => 24 * 60 * 60, // 24h
			),
			'log'  => array(
				'class' => 'CLogRouter',
				'enabled' => true,
				'routes' => array(
					/*array(
						'class' => 'CFileLogRoute',
						'levels' => 'error, warning, trace, info',
						'logFile' => 'msg.log',
						'enabled' => true,
					),*/
					array(
						'class' => 'DDebugProfiler',
					),
					array(
						'class' => 'DDebug',
						'levels' => 'error, warning, trace, info',
						'config'=>'alignRight, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
						'allowedIPs'=>array('127.0.0.1','::1','192.168.1.12','192\.168\.1[0-5]\.[0-9]{3}'),						
						'profilerEnabled' => true
					),				
				)
			),/*
			'errorHandler' => array(
				'errorAction'=>'home/site/error',
			),*/
			'htmlProcessor' => array(
				'class' => 'DTag'
			),
			'viewRenderer' => array(
            'class' => 'CPradoViewRenderer',
        ),
			/*
			'bootstrap' => array(
				'class'=>'ext.bootstrap.components.Bootstrap'
			),*/
		);
		$this->setComponents($components);
		include('Constants.php');
	}
	
	public function getBrowser()
	{
		return $this->getComponent('browser');
	}
	
	public function getCrypt()
	{
		return $this->getComponent('crypt');
	}
	
	public function isApplicationInstalled()
     {
     	return $this->installed;
	}
	
	public function getLicense()
	{
		return $this->license;
	}
	
	public $preload=array('offline','browser','log', 'cfg','bootstrap');
	
	public $behaviors = array(
            'onBeginRequest' => array(
                'class' => 'application.lib.behaviors.BeginRequestBehavior'
            ),
            'onEndRequest' => array(
                'class' => 'application.lib.behaviors.EndRequestBehavior'
            )
     );
	
	public function onBeforeController($e)
	{
		$this->raiseEvent('onBeforeController', $e);
	}
	
	public function initLanguage($lang=null)
	{
		if($this->session->itemAt('language'))
			$this->setLanguage($this->session->itemAt('language'));
		elseif($this->request->getPreferredLanguage() && is_dir('language/' . $this->request->getPreferredLanguage()))
			$this->setLanguage($this->request->getPreferredLanguage());
		else
			$this->setLanguage('en_us');
		return $this;
	}
}

interface IDModule
{
	public function setInfo();
	public function onBeginController($e);
	public static function getUrlRules();
}
interface IDBaseController
{
	public function designer($className,$properties);
	//public function draw($view,$data, $metadata, $return);	
}

interface IBaseModel
{
	public function getMData($key=null);
}



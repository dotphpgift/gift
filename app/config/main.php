<?php

$instance = array();

if(is_file($f = COMMON_ROOT.DS.'config'.DS.FIRST_INSTANCE))
	require($f);


$config = array(
	'name' => 'DOTPHP Developers',
	'basePath' => COMMON_ROOT,
	'theme' => 'default',
	'installed' => isset($instance['installed']) ? true : false,
	'defaultController' => 'home/site',
	'params' => array(
		'timeZone' => 'Asia/Kolkata'
	),
);

return CMap::mergeArray($config, $instance);
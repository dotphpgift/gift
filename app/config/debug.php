<?php

/*Debug Mode for Development*/

$debugOn = true;
$forceNoFreeze = true;
$performanceOn = true;

if($debugOn)
	error_reporting(E_ALL | E_STRICT);

define('YII_DEBUG', $debugOn);
define('YII_TRACE_LEVEL', $debugOn ? 3 : 0);
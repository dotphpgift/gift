<?php

define('IS_DEVELOPMENT', true);

require('root.php');
require(COMMON_ROOT.DS.'lib' .DS. 'DBase.php');

DBase::setPath('app', COMMON_ROOT);

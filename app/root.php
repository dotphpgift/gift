<?php

define('IS_LINUX', PHP_OS == 'Linux');
define('IS_WINNT', PHP_OS == 'WINNT');
define('ACCESS', TRUE);
define('DS', DIRECTORY_SEPARATOR);
define('EXT', '.php');

define('DOTPHP', 'yii.php');
define('FIRST_INSTANCE', 'perInstance.php');

    // The application has only been tested in Linux, and
    // Windows variants identifying themselves as WINNT.
//assert(IS_LINUX || IS_WINNT); // Not Coding Standard

define('COMMON_ROOT',   dirname(__FILE__));
define('INSTANCE_ROOT', getcwd());

define('MAJOR_VERSION', 1);                             // Update for marketing purposes.
define('MINOR_VERSION', 2);                             // Update when functionality changes.
define('PATCH_VERSION', 1);                             // Update when fixes are made that does not change functionality.
define('REPO_ID',       '$Revision: 4ac904c62cc6 $');   // Updated by Mercurial. Numbers like 3650 have no meaning across
                                                        // clones. This tells us the actual changeset that is universally
                                                        // meaningful.

define('VERSION', join('.', array(MAJOR_VERSION, MINOR_VERSION, PATCH_VERSION)) . ' (' . substr(REPO_ID, strlen('$Revision: '), -2) . ')');

if(defined('IS_DEVELOPMENT') && IS_DEVELOPMENT === true )
{
	if( is_file($debug = COMMON_ROOT.DS.'config'.DS.'debug.php') )
		require($debug); 
}	


<?php


$instance['installed'] = 1;
$instance['language'] = 'en_us';
$instance['components'] = array(
	'db' => array(
		'connectionString' => 'mysql:host=localhost;port=3306;dbname=dotphp',
		'username' => 'dhanam',
		'password' => '04121976',
		'tablePrefix' => 'dphp_'
	),
	'session' => array(
		'class' => 'application.lib.DSession',
		'autoStart' => false,
      ),
	 'cache' => array(	 	
	 ),
);
$instance['license'] = 'demo';
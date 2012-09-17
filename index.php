<?php

include_once('app/final.php');
DBase::init();


$app = DBase::WebApp(DBase::import('app').DS.'config'.DS.'main.php'); 
$app->initLanguage()
    ->run();
<?php defined('ACCESS') or die("No direct script access allowed");

class DDataProvider extends CActiveDataProvider
{
	public function __construction($modelClass, $config=array())
	{
		$model = ucfirst($modelClass);
		parent::__construction($model, $config);
	}
}
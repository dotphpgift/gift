<?php defined('ACCESS') or die("No direct script access allowed");

abstract class DDraw extends CWidget
{
	abstract public function renderItems();
	
	
	public function run()
	{
		echo $this->renderItems();
	}
}
<?php defined('ACCESS') or die("No direct script access allowed");

class DColumn extends CComponent
{	
	public $id;
	public $cssClass=array('columns');
	public $htmlOptions=array();
	public $visiable = true;
	public $offset;
	public $tagName='div';
	public $totalColumns=12;
	public $mobileTotalColumns=4;
	public $noWrap=false;
	public $cssColumnName = array(
		1 => 'one',
		2 => 'two',
		3 => 'three',
		4 => 'four',
		5 => 'five',
		6 => 'six',
		7 => 'seven',
		8 => 'eight',
		9 => 'nine',
		10 => 'ten',
		11 => 'eleven',
		12 => 'twelve',
		13 => 'thirteen',
		14 => 'fourteen',
		15 => 'fifteen',
		16 => 'sixteen'
	);
	
	private $_row;
	private $_clips;
	
	public function __construct($rowClass)
	{
		$this->_row = $rowClass;
	}
	
	public function init()
	{
		
	}
	
	public function getTotalColumns()
	{
		return $this->totalColumns;
	}
	
	public function getRow()
	{
		return $this->_row;
	}
	
	public function createOffset($key, $offset)
	{
		if( is_array($offset) && isset($offset[$key]) )
			$this->cssClass[] = strtr($offset[$key], $this->cssColumnName);
	}
	
	public function getController()
	{
		return $this->getRow()->boostrap->controller;
	}
	
	public function renderRow()
	{
		
	}
}
<?php defined('ACCESS') or die("No direct script access allowed");

class DAction extends CAction
{
	protected $model;
	public $xtype, $validate, $scenario=null;
	public $defaultXtype = array(
		'form','nestedform','activeform','listview','detailedview','gridview'
	);
	public $dataType = 'html';
	public $isAjax = false;
}
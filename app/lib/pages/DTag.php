<?php defined('ACCESS') or die("No direct script access allowed");

class DTag extends CApplicationComponent
{
	const BEFORE_HEADER ='beforeHeader';
	const HEADER='header';
	const AFTER_HEADER='afterHeader';
	const CONTAINER='container';	
	const BEFORE_CONTENT='beforeContent';
	const AFTER_CONTENT='afterContent';
	const CONTENT='content';
	const BEFORE_FOOTER='beforeFooter';	
	const FOOTER='footer';
	const AFTER_FOOTER='afterFooter';
	
	protected $beforeHeader=array();
	protected $header=array();
	protected $afterheader=array();
	protected $container=array();
	protected $beforeContent=array();
	protected $content=array();
	protected $afterContent=array();
	protected $beforeFooter=array();
	protected $footer=array();
	protected $afterFooter=array();
	
	protected $newRow;
	
	public function init()
	{
		if($this->newRow === null)
			$this->newRow = array();
	}
	
	public function reset()
	{
		$this->beforeHeaderRow=array();
		$this->headerRow=array();
		$this->afterheaderRow=array();
		$this->containerRow=array();
		$this->beforeContentRow=array();
		$this->contentRow=array();
		$this->afterContentRow=array();
		$this->beforeFooterRow=array();
		$this->footerRow=array();
		$this->afterFooterRow=array();
		$this->newRow=array();
	}
	
	public function toArray()
	{
		return array('elements'=>$this->newRow);
	}
	
	public function registerRow($row, $position=self::HEADER)
	{
		if($this->validate($row))
			$this->newRow[$position][] = array('row' => $row);
		return $this;
	}
	
	public function registerRows($rows, $position=self::HEADER)
	{
		if(is_array($rows) && count($rows))
		{
			foreach($rows as $row)
				$this->registerRow($row,$position);
		}
		return $this;
	}
	
	public function registerContent($content=array(), $type='form')
	{
		if(is_array($content))
		{
			foreach(array(self::CONTENT, self::BEFORE_CONTENT, self::AFTER_CONTENT) as $pos)
				$this->$pos = isset($content[$pos]) ? $content[$pos] : array();
		}
		$this;
	}
	
	public function getLayoutAttributes()
	{
		return $this->newRow;
	}
	
	private function validate($row)
	{
		return true;
	}
	
	public function getBeginContent()
	{
		$content = $this->beforeContent;
		$this->beforeContent=array();
		return $content;
	}
	public function getAfterContent()
	{
		$content=$this->afterContent;
		$this->afterContent=array();
		return $content;
	}
	public function getContent()
	{
		$content = $this->content;
		$this->content = array();
		return $content;
	}	
	
	public $pageLayout = array(
			self::BEFORE_HEADER,
			self::HEADER,
			self::AFTER_HEADER,
			//self::CONTAINER,
			self::BEFORE_FOOTER,
			self::FOOTER,
			self::AFTER_FOOTER
		);
}
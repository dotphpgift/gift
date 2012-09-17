<?php defined('ACCESS') or die("No direct script access allowed");

class DHead extends DDraw
{
	public $tagName = 'head';
	public $items=array();
	public $htmlOptions=array();
	
	public function init()
	{
		$this->htmlOptions['id']=$this->getId();
		DBase::getApp('clientScript')->registerMetaTag('text/html; charset='.DBase::getApp('charset'),null,'Content-Type');
	}
	
	public function renderItems()
	{
		echo DHtml::openTag($this->tagName, $this->htmlOptions);
		echo $this->renderContent();
		echo DHtml::closeTag($this->tagName);
	}
	
	public function renderContent()
	{
		$content  = '';		
		$content .= DHtml::openTag('title');
		$content .= DBase::app()->getController()->getPageTitle();
		$content .= DHtml::closeTag('title');
		$content .= "\n";
		return $content;
	}
}
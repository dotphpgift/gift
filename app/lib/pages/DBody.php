<?php defined('ACCESS') or die("No direct script access allowed");

class DBody extends DDraw
{
	public $htmlOptions = array('class'=>'col123');
	public $tagName = 'body';
	public $content;
	public $dataProvider;
	public $headerView;
	public $footerView;
	public $containerView;
	public $boostrap;
	
	public function renderItems()
	{
		echo "\n" . DHtml::openTag($this->tagName, $this->htmlOptions);
		echo $this->renderContent();
		echo "\n" . DHtml::closeTag($this->tagName) . "\n";
	}
	
	protected function renderContent()
	{
		$this->beginWidget(DBase::CLIPWIDGET, array('id'=>'content'));
		echo $this->content;
		$this->endWidget();
		
		$html = $this->renderLayout();		
		return $html;
	}
	
	protected function renderLayout()
	{
		/*$className = 'DRow';
		$this->widget($className, array(
			'boostrap' => $this
		));*/
		$g= new DHtmlProcessor(DBase::getApp('htmlProcessor')->toArray(), $this->getController());
		//($g->render());
		echo $g->output();
	}
}
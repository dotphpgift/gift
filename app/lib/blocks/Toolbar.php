<?php defined('ACCESS') or die("No direct script access allowed");

class Toolbar extends DDraw
{
	public $brand;
	public $brandUrl;
	public $brandOptions = array();
	public $items = array();
	public $htmlOptions = array();
	public $logo='logo.png';
	
	private $_viewPort='browser';
	
	public function init()
	{
		DBase::getApp('clientScript')->registerCssFile(DBase::getApp('theme')->baseUrl.'/css/toolbar.css');		
		if(isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] = 'dp_toolbar navbar ' . $this->htmlOptions['class'];
		else
			$this->htmlOptions['class'] = 'dp_toolbar navbar';
	}
	
	public function renderItems()
	{
		$output='';
		$output.=DHtml::openTag('div', $this->htmlOptions);
		
		if(!$this->controller->isFixedWidth)
			$output.='<div class="dp_wrapper">';
		
		$output.='<div class="dp_gd">';
		$output.=$this->renderBrand();
		$output.='</div>';
		
		if(!$this->controller->isFixedWidth)
			$output.='</div>';	
			
		$output.=DHtml::closeTag('div');
		return $output;		
	}
	
	protected function renderBrand()
	{
		$output='';
		if($this->brand !== false)
		{
			if(!isset($this->brandUrl))
				$this->brandUrl = DBase::getApp()->homeUrl;
			$this->brandOptions['href'] = DHtml::normalizeUrl($this->brandUrl);
			
			$output .= DHtml::openTag('div', array('class'=>'logo dp_gl'));
			$output .= DHtml::openTag('a', $this->brandOptions);
			$output .= DHtml::image(DBase::getApp('theme')->baseUrl.'/img/'.$this->logo, $this->brand, array('id'=>'brandLogo'));
			$output .= DHtml::closeTag('a');			
			$output .= DHtml::closeTag('div');
		}
		$output .= $this->renderItem();
		return $output;
	}
	
	protected function renderItem()
	{
		$items = array(
                array('label'=>'Home', 'url'=>'#', 'active'=>true),
                array('label'=>'Link', 'url'=>'#'),
                array('label'=>'Dropdown', 'url'=>'#', 'items'=>array(
                    array('label'=>'Action', 'url'=>'#'),
                    array('label'=>'Another action', 'url'=>'#'),
                    array('label'=>'Something else here', 'url'=>'#'),
                    '---',
                    array('label'=>'NAV HEADER'),
                    array('label'=>'Separated link', 'url'=>'#'),
                    array('label'=>'One more separated link', 'url'=>'#'),
                )),
            );
		return $this->controller->widget('Menu', array('items'=>$items), true);
	}
}
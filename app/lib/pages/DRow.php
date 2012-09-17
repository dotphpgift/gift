<?php defined('ACCESS') or die("No direct script access allowed");

class DRow extends DDraw
{
	public $tagName = 'div';
	public $CssClass = 'dp_gd';
	public $columns = array();
	public $columnsCssClass;
	public $columnsHtmlOptions=array();
	public $firstRowCssClass;
	public $lastRowCssClass;
	public $rowHtmlOptions=array();
	public $items=array();
	public $nullDisplay='&nbsp;';
	public $totalColumns=12;
	public $mobileTotalColumns=4;
	public $boostrap;
	public $fluidWidth=true;
	public $containerWrapper=true;
	
	private static $_counter=0;
	private $_pageLayout=array();
	private $_formatter;
	private $_colOffset;
	private $_isMobile=false;
	
	public function init()
	{
		$this->_colOffset = new CMap();		
		if(! count($this->items) )
			$this->items = $this->loadLayout();
			
		$this->createLayout();
		$this->initCols();
	}
	
	protected function reset()
	{
		self::$_counter=0;
		//$this->items=array();
		$this->columns=array();
		$this->columnsCssClass=null;
		$this->columnsHtmlOptions=array();
		$this->firstRowCssClass=null;
		$this->lastRowCssClass=null;
		$this->rowHtmlOptions=array();
		$this->id=null;
	}
	
	public function renderItems()
	{
		$html = $this->renderRows();
		$this->reset();
		return $html;
	}
	
	protected function renderRows()
	{
		if(count($this->_pageLayout)):		
			return $this->renderHeaderRow().$this->renderContainerRow();
		endif;
	}
	
	protected function createLayout()
	{
		foreach(DBase::getApp('htmlRow')->htmlLayout as $key => $value)
		{			
			$val = str_replace('pos_','',$value);
			if(isset($this->items[$val]))
				$this->_pageLayout[$value] = $this->items[$val];
			continue;
		}
		
		if($nrow = DBase::getApp('htmlRow')->getLayoutAttributes())
			$this->_pageLayout = CMap::mergeArray($this->_pageLayout, $nrow);
		
		unset($this->items);
	}
	
	public function getPageLayout()
	{
		return $this->_pageLayout;
	}
	
	protected function initCols()
	{
		$columns = array();
		foreach($this->_pageLayout as $pos => $row)
		{
			if(is_array($row) && $c=count($row))
			{
				for($i=0; $c>$i; ++$i)
				{
					if(isset($row[$i]['columns']))
						$columns = $row[$i]['columns'];						
					$this->columns[$pos][$i] = $this->createColumns($columns,$row, $pos, $i);
				}
			}
		}		
	}
	
	protected function createColumns($columns, $row, $position, $rowKey)
	{
		$col=array();
		$mapOffset=array();
		
		
		foreach($columns as $i => $column)
		{
			if(is_string($column )) {
				$column = array('value'=>$column, 'noWrap'=>true);
			}
			
			if(is_array($column))
			{
				if(!isset($column['class']))
					$column['class'] = 'DColumnData';					
				$column = DBase::createComponent($column, $this);
				
				if(!$column->visiable)
					$column->htmlOptions=CMap::mergeArray($column->htmlOptions,array('style'=>array('display:none')));
					
				if($column->id===null)
					$column->id = strtolower($column->name).'_c_'.count($row) . $i;
				
				if(!$column->offset)
					$column->offset = 0;		
				$column->init();
				
				$mapOffset[] = (int)$column->offset;
				//print_r($column);				
			}
			$col[$i] = $column;//$this->resolveColumnWidth($column, $row);
		}
		$this->_colOffset->add($position.'_'.$rowKey, $mapOffset);
		$mapOffset=array(); 
		return $col;
	}
	
	protected function resolveColumnWidth(DColumnData $column, $row)
	{
		$colWidth = $column->getTotalColumns() / count($row);		
		$column->cssClass[] = $colWidth; 
		return $column;
	}
	
	protected function resolveColumnOffset($key)
	{
		$colOffset = $this->_colOffset->itemAt($key);
		$colSum        = array_sum($colOffset);
		$numColumns    = count($colOffset);
		$totalOffset   = !$this->_isMobile ? $this->totalColumns : $this->mobileTotalColumns;
		$defaultOffset = $totalOffset / $numColumns; 
		
		$remainingOffset  = $totalOffset;
		$adj=array();
		
		if($numColumns > 1 && $totalOffset > ($colSum*$numColumns))
		{
			for($i=0; $i<$numColumns; ++$i)
			{ 
				if($colOffset[$i] > 0)
				{
					$colOffset[$i]   += $defaultOffset;
					$remainingOffset -= $colOffset[$i];
				}
				else
				{
					$adj[$i] = $colOffset[$i];
					unset($colOffset[$i]);
				}		
			}
			$diff = ($remainingOffset - ($remainingOffset % count($adj))) / count($adj);
			
			foreach(array_keys($adj) as $k) 
			{
				$adj[$k] = $diff;
			}
			$new_diff = $totalOffset - array_sum($colOffset + $adj);
		
			if($new_diff > 0)
			{
				$share = ($new_diff>count($colOffset)) ? $new_diff/count($colOffset) : $new_diff;
			}
		}
		else
		{
			for($i=0; $i<$numColumns; ++$i)
			{
				$colOffset[$i] = $defaultOffset;
			}
		}
		$built = $colOffset + $adj; //sort($built);
		$this->_colOffset->remove($key);
		//$this->_colOffset->add($key, $built);
		return $built;
	}
	
	public function renderRow($pos)
	{
		foreach($this->columns[$pos] as $row => $columns)
		{ 
			$getOffsets = $this->resolveColumnOffset($pos.'_'.$row);
			//echo DHtml::openTag($this->tagName, array('id'=>$pos.'_'.$row, 'class'=>$this->CssClass));
			
			$count=0;
			$n=count($columns);
			foreach($columns as $i => $column)
			{
				$count++;
				if($count===1 && !$column->noWrap)
					echo DHtml::openTag($this->tagName, array('id'=>$pos.'_'.$row, 'class'=>$this->CssClass));
				$column->createOffset($i, $getOffsets);
				$column->renderColumn();
				if($count===$n && !$column->noWrap)
					echo DHtml::closeTag($this->tagName);
			}
			//echo DHtml::closeTag($this->tagName);
		}
	}
	
	protected function renderHeaderRow()
	{
		$html='';
		if($this->containerWrapper)
			echo DHtml::openTag('div', array('id'=>'header_wrapper', 'class'=>'dp_wrapper'));
		if(isset($this->_pageLayout[DTag::BEFORE_HEADER]))
			$html .= $this->renderRow(DTag::BEFORE_HEADER);
		if(isset($this->_pageLayout[DTag::HEADER]))
			$html .= $this->renderRow(DTag::HEADER);
		if(isset($this->_pageLayout[DTag::AFTER_HEADER]))
			$html .= $this->renderRow(DTag::AFTER_HEADER);
		if($this->containerWrapper)
			echo DHtml::closeTag('div');			
		return $html;
	}
	
	protected function renderContainerRow()
	{
		$html='';
		if(isset($this->_pageLayout[DTag::CONTAINER]))
			$html .= $this->renderRow(DTag::CONTAINER);
		return $html;
	}
	
	public function getFormatter()
	{
		if($this->_formatter===null)
			$this->_formatter=DBase::getApp('format');
		return $this->_formatter;
	}
	
	public function loadLayout()
	{
		/*supported types: raw, text, ntext, html, date, time, datetime, bool, email, image, ulr, number*/
		return array(
			'header' => 
			array(
				array(
					'columns' => 
					array(
						array(
						'name' => 'Dhanam',
						'offset' => 2,
						'htmlOptions'=> array('class'=>'test')
						),
						array(
						'name' => 'Sakthi',
						'value' => 'Sakthi'
						),
						array(
						'name' => 'Sakthi',
						'value' => 'Sakthi'
						)
					),
				),
				array(
					'columns' =>
					array(
						array(
						'name' => 'Kasthuri'
						),
						array(
						'name'=>'Prabha'
						)
					),				
				),
			),
			'footer'=>
			array(
				array(
					'columns' => 
					array(
						array(
						'name' => 'Dhanam',
						
						'type' => 'text',
						'value' => 'd',
						)
					),
				),
			),
			'container' => 
			array(
				array(
					'columns' => 
					array(
						array(
						'name' => 'content',
						'offset' => '1',
						'type' => 'raw',						
						)
					),
				), 
			)		
		);	
	}
}
/*
CREATE TABLE IF NOT EXISTS `menu` (
  `menu_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  PRIMARY KEY  (`menu_id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
 
CREATE TABLE IF NOT EXISTS `menu_item` (
  `item_id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) default NULL,
  `menu_id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `description` text NOT NULL,
  `date_added` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `sort_order` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  PRIMARY KEY  (`item_id`),
  KEY `fk_menu_item_menu1` (`menu_id`),
  KEY `fk_menu_item_menu_item1` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
 
ALTER TABLE `menu_item`
  ADD CONSTRAINT `fk_menu_item_menu1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_menu_item_menu_item1` FOREIGN KEY (`parent_id`) REFERENCES `menu_item` (`item_id`) ON DELETE SET NULL ON UPDATE NO ACTION;
 */
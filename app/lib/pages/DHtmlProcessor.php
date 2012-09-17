<?php defined('ACCESS') or die("No direct script access allowed");

class DHtmlProcessor extends DHtmlEngine 
{
	public $attributes=array();
	public $isFixedWidth=false;
	
	private $_elements;
	private $_beforeHeader;
	private $_afterHeader;
	private $_header;
	private $_footer;
	private $_container;
	private $_columns;
	private $_rows;
	
	public function __construct($config, $parent=null)
	{
		parent::__construct($config, $parent);
		$this->isFixedWidth = $this->getController()->isFixedWidth;
		$this->init();
	}
	
	public function init()
	{
		
	}
	
	public function getRow()
	{
		if($this->_rows===null)
			$this->_rows=new DCollections($this,null,true,false);
		return $this->_rows;
	}
	
	public function getColumns()
	{
		if($this->_columns===null)
			$this->_columns=new DCollections($this,null,false,true);
		return $this->_columns;
	}
	
	public function setColumns($columns)
	{ 
		$collection=$this->getColumns();
		foreach($columns as $name=>$config)
			$collection->add($name,$config);
	}
	
	public function setRow($row)
	{ 
		$row=array($row);
		$collection=$this->getRow();
		foreach($row as $name=>$config)
			$collection->add($name,$config);
	}
	
	public function getElements()
	{
		if($this->_elements===null)
			$this->_elements=new DCollections($this,null);
		return $this->_elements;
	}
	
	public function setElements($elements)
	{
		
		$collection=$this->getElements();
		foreach($elements as $name=>$config)
			$collection->add($name,$config);
	}
	
	public function getHeader()
	{
		if($this->_header===null)
			$this->_header=new DCollections($this,'header');
		return $this->_header;
	}
	
	public function setHeader($elements)
	{ 
		$collection=$this->getHeader();
		foreach($elements as $name=>$config)
			$collection->add($name,$config);
	}
	
	public function getBeforeHeader()
	{
		if($this->_beforeHeader===null)
			$this->_beforeHeader=new DCollections($this,'beforeHeader');
		return $this->_beforeHeader;
	}
	public function setBeforeHeader($elements)
	{ 
		$collection=$this->getBeforeHeader();
		foreach($elements as $name => $config)
			$collection->add($name,$config);
	}
	
	public function getAfterHeader()
	{
		if($this->_afterHeader===null)
			$this->_afterHeader=new DCollections($this,'afterHeader');
		return $this->_afterHeader;
	}
	public function setAfterHeader($elements)
	{ 
		$collection=$this->getAfterHeader();
		foreach($elements as $name => $config)
			$collection->add($name,$config);
	}	
	
	public function render()
	{		
		$output='';
		if($this->isFixedWidth===true){
			$output.=DHtml::openTag('div',array('class'=>'dp_wrapper') );
		}
		$output.= $this->renderElements();
		if($this->isFixedWidth===true){
			$output.=DHtml::closeTag('div');
		}
		return $output;
	}
	
	protected function renderElements()
	{
		$output='';
		foreach($this->_elements as $element)
		{
			$output.=$this->renderBeforeHeader($element);
			$output.=$this->renderHeader($element);
			$output.=$this->renderAfterHeader($element);
		}
		return $output;
	}
	
	protected function renderBeforeHeader($element)
	{
		$count=$element->getBeforeHeader()->getCount();
		$id='beforeHeader_wrapper';
		$output='';
		$cnt=1;
		foreach($element->getBeforeHeader() as $k => $beforeHeader)
		{
			if($cnt===1)
				$output.=DHtml::openTag('div',array('id'=>$id) );				
			$output.= $this->renderRow($beforeHeader, 'row'.$id.'_'.$k);				
			if($cnt===$count)
				$output.= DHtml::closeTag('div');
			$cnt++;
		}		
		return $output;
	}
	
	protected function renderAfterHeader($element)
	{
		$count=$element->getAfterHeader()->getCount();
		$id='afterHdeader_wrapper';
		$output='';
		$cnt=1;
		foreach($element->getAfterHeader() as $k => $afterHeader)
		{
			if($cnt===1)
				$output.=DHtml::openTag('div',array('id'=>$id) );				
			$output.= $this->renderRow($afterHeader, 'row'.$id.'_'.$k);				
			if($cnt===$count)
				$output.= DHtml::closeTag('div');
			$cnt++;
		}		
		return $output;
	}
	
	protected function renderHeader($element)
	{
		$count=$element->getHeader()->getCount();
		$id='header_wrapper';
		$output='';
		$cnt=1;
		foreach($element->getHeader() as $k => $header)
		{
			if($cnt===1)
				$output.=DHtml::openTag('div',array('id'=>$id) );				
			$output.= $this->renderRow($header, 'row'.$id.'_'.$k);				
			if($cnt===$count)
				$output.= DHtml::closeTag('div');
			$cnt++;
		}		
		return $output;
	}
	
	protected function renderRow($rows, $key=null)
	{
		$output='';
		$cnt=1;
		foreach ($rows->getRow() as $row)
		{
			if($row instanceof DRowEl && $this->isFixedWidth===false) {
				$output.=DHtml::openTag('div',array('class'=>'dp_wrapper') );
			}
			$row->id=$key;
			$output.= $row->render();
			if($row instanceof DRowEl && $this->isFixedWidth===false) {
				$output.= DHtml::closeTag('div');
			}
		}
		return $output;
	}
	
	public function getController()
	{
		if($this->getParent() instanceof DBaseController)
			return $this->getParent();
		else
			return DBase::getApp()->getController();
	}
}

class DRowEl extends DHtmlProcessor
{
	public $htmlOptions=array();
	public $className='dp_gd';
	public $noWrap=false;
	public $id;
	public $name;
	public $elName='div';
	public $key;
	public $rowCssClass=array('odd','even');
	public $visible=true;
	
	private $_count=0;
	
	public function render()
	{	
		$output=DHtml::openTag($this->elName, $this->setOptions());
		foreach($this->getColumns() as $column)
		{
			$column->id = 'cols_'.$this->_count;
			$output.=$column->render();
			$this->_count++;
		}
		$output.=DHtml::closeTag($this->elName);
		return $output;		
	}
	
	public function getNumColumns()
	{
		return $this->getColumns()->getCount();
	}
	
	protected function setOptions()
	{
		$options = $this->htmlOptions;
		if(isset($options['class']))
			$options['class'] = is_array($options['class'])?$this->className.' '.implode(' ', $options['class']):$this->className.' '.$options['class'];
		$options['id']=$this->id;
		$style='';
		if(isset($options['style']))
		{
			if(is_array($options['style']))
				$style = implode(' ',$options['style']);
		}
		if(!$this->visible)
			$options['style'] = 'display:none;'.$style;
		return $options;
	}
	
	protected function getCount()
	{
		return $this->getColumns()->getCount();
	}
}

class DColumnsEl extends DHtmlProcessor
{
	public $htmlOptions=array();
	public $className;
	public $name;
	public $value;
	public $id;
	public $elName='div';
	public $type='raw';
	public $key;
	public $width;
	
	public function init()
	{
		if($this->name===null && $this->value===null)
			throw new CException('Either "name" or "value" must be specified for colums array');
	}
	
	public function render()
	{
		$output='';
		if($this->value !== null)
			$value = $this->value;
		else if($this->name !== null)
			$value = $this->getController()->clips[$this->name];
			
		$output.=DHtml::openTag($this->elName, $this->setOptions());
		$output.=$value;
		$output.=DHtml::closeTag($this->elName);
		return $output;
	}
	
	protected function setOptions()
	{
		$options = $this->htmlOptions;
		if(isset($options['class']))
			$options['class'] = is_array($options['class'])?$this->className.' '.implode(' ', $options['class']):$this->className.' '.$options['class'];
		else
			$options['class'] = $this->className;
			
		$options['id']=$this->id;
		$style='';
		if(isset($options['style']))
		{
			if(is_array($options['style']))
				$style = implode(' ',$options['style']);
		}
		return $options;
	}
}

class DStringEl extends DHtmlProcessor
{
	public $content;
	public $type='text';
	public $id;
	
	private $_input;
	private $_output;	
	
	public function render()
	{
		return $this->generateBlock();
	}
	
	protected function generateBlock()
	{
		static $regexRules = array(
			'<%=?\s*(.*?)\s*%>', // PHP statements or expressions
			'<\/?(dp|com|cache|clip):([\w\.]+)\s*((?:\s*\w+\s*=\s*\'.*?(?<!\\\\)\'|\s*\w+\s*=\s*".*?(?<!\\\\)"|\s*\w+\s*=\s*\{.*?\})*)\s*\/?>', // component tags
			'<!---.*?--->',	// template comments
		);
		$this->_input = $this->content;
		if($this->_input==null)
			return null;
		$n=preg_match_all('/'.implode('|',$regexRules).'/msS',$this->_input,$matches,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		
		$textStart=0;
		$this->_output= ''; 
		
		for($i=0; $i < $n; ++$i)
		{
			$match=&$matches[$i];
			$str=$match[0][0];
			$matchStart=$match[0][1];
			$matchEnd=$matchStart+strlen($str)-1; 
			
			if($matchStart>$textStart)
				$this->_output.=substr($this->_input,$textStart,$matchStart-$textStart);
			$textStart=$matchEnd+1;
			
			if(strpos($str, '<dp:') === 0)
			{
				$type = $match[3][0];
				$this->_output.=$this->processWidget($type,$match[4][0],$match[2][1]);
			}
		}
		
		if($this->_output=='')
			$this->_output = $this->_input;
		return $this->_output;		
	}
	
	private function processWidget($type,$attributes,$offset)
	{
		$attrs=$this->processAttributes($attributes);
		if(empty($attrs))
			return $this->generatePhpCode("\$this->widget('$type');",$offset);
		else
			return $this->generatePhpCode("\$this->widget('$type', array($attrs));",$offset);
	}
	
	private function processAttributes($str)
	{
		static $pattern='/(\w+)\s*=\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)"|\{.*?\})/msS';
		$attributes=array();
		$n=preg_match_all($pattern,$str,$matches,PREG_SET_ORDER);
		for($i=0;$i<$n;++$i)
		{
			$match=&$matches[$i];
			$name=$match[1];
			$value=$match[2];
			if($value[0]==='{')
				{$attributes[]="'$name'=>".substr($value,1,-1);}
			else
				$attributes[]="'$name'=>$value";
		}
		return implode(', ',$attributes);
	}
	
	private function generatePhpCode($code,$offset)
	{
		$line=$this->getLineNumber($offset);
		return $this->evaluateExpression("$code");
	}
	
	private function getLineNumber($offset)
	{
		return count(explode("\n",substr($this->_input,0,$offset)));
	}
	
	public function widget($className,$properties=array(),$captureOutput=true)
	{
		return $this->getController()->widget($className,$properties,$captureOutput);
	}
}
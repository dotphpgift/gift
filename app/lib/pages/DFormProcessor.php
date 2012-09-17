<?php defined('ACCESS') or die("No direct script access allowed");

class DFormProcessor extends DFormElements implements ArrayAccess
{
	public $title;
	public $description;
	public $method='post';
	public $action='';
	public $attributes=array();
	public $showErrorSummary=false;
	public $inputElementClass='DFormInputElement';
	public $buttonElementClass='DFormButtonElement';
	public $activeForm=array('class'=>'CActiveForm','htmlOptions'=> array('class'=>'dp_form'));
	public $formStyle = array();

	private $_model;
	private $_elements;
	private $_items;
	private $_buttons;
	private $_columns;
	private $_rows;
	private $_activeForm;
	private $_id;

	public function __construct($config,$model=null,$parent=null)
	{
		$this->setModel($model); 
		if($parent===null)
			$parent=DBase::getApp()->getController();
		parent::__construct($config,$parent);
		$this->init();
	}
	
	protected function init()
	{
	}
	
	public function getButtons()
	{
		if($this->_buttons===null)
			$this->_buttons=new DButtonCollection($this,true);
		return $this->_buttons;
	}
	
	public function setButtons($buttons)
	{
		$collection=$this->getButtons();
		foreach($buttons as $name=>$config)
			$collection->add($name,$config);
	}
	
	public function getElements()
	{
		if($this->_elements===null)
			$this->_elements=new DElementCollection($this,false);		
		return $this->_elements;
	}
	
	public function setElements($elements)
	{
		$collection=$this->getElements(); 
		foreach($elements as $name=>$config)
			$collection->add($name,$config);
	}
	
	public function getRows()
	{
		if($this->_rows===null)
			$this->_rows=new DElementCollection($this,false);
		return $this->_rows;
	}
	
	public function setRows($row)
	{
		$collection=$this->getRows();
		foreach($row as $name=>$config)
			$collection->add($name,$config);
	}
	
	public function setColumns($columns)
	{
		$collection=$this->getColumns();
		foreach($columns as $name=>$config)
			$collection->add($name,$config);
	}
	
	public function getColumns()
	{
		if($this->_columns===null)
			$this->_columns=new DElementCollection($this,true);
		return $this->_columns;
	}
	
	public function getItems()
	{
		if($this->_items===null)
			$this->_items=new DElementCollection($this,false);
		return $this->_items;
	}
	
	public function setItems($items)
	{
		$collection=$this->getItems(); 
		foreach($items as $name=>$config)
			$collection->add($name,$config);
	}
	
	public function getModel($checkParent=true)
	{
		if(!$checkParent)
			return $this->_model;
		$form=$this;
		while($form->_model===null && $form->getParent() instanceof self)
			$form=$form->getParent();
		return $form->_model;
	}
	
	public function setModel($model)
	{
		$this->_model=$model;
	}
	
	public function renderBegin()
	{ 
		if($this->getParent() instanceof self)
			return '';
		else
		{
			$options=$this->activeForm;
			if(isset($options['class']))
			{
				$class=$options['class'];
				unset($options['class']);
			}
			$options['action']=$this->action;
			$options['method']=$this->method;
			if(isset($options['htmlOptions']))
			{
				foreach($this->attributes as $name=>$value)
					$options['htmlOptions'][$name]=$value;
			}
			else
				$options['htmlOptions']=$this->attributes;
			
			ob_start();
			$this->_activeForm=$this->getOwner()->beginWidget($class, $options);
			return ob_get_clean() . "<div style=\"visibility:hidden\">".DHtml::hiddenField($this->getUniqueID(),1)."</div>\n";
		}
	}
	
	public function renderEnd()
	{
		if($this->getParent() instanceof self)
			return '';
		else
		{
			ob_start();
			$this->getOwner()->endWidget();
			return ob_get_clean();
		}
	}
	
	public function renderElement($element)
	{
		return $element->render();
	}
	
	public function renderElements()
	{
		$output='';
		foreach($this->getElements() as $element)
		{
			if($element->title!==null)
			{
				if($element->getParent() instanceof self)
				{
					$attributes=$element->attributes;
					unset($attributes['name'],$attributes['type']);
					$output=DHtml::openTag('fieldset', $attributes)."<legend>".$element->title."</legend>\n";
				}
				else
					$output="<fieldset>\n<legend>".$element->title."</legend>\n";
					
				if($element->description!==null)
					$output.="<div class=\"description\">\n".$element->description."</div>\n";
			}
			
			if($element->getItems()->getCount())
			{
				foreach($element->getItems() as $rows)
				{
					$output.= $rows->render();
				}
			}
			
			if($this->getButtons()->getCount())
			{
				$output.= DHtml::openTag('div', array('class'=> 'dp_fbox-button'));
				foreach($this->getButtons() as $buttons)
					$output.= $buttons->render();
				$output.= DHtml::closeTag('div');
			}
				
			if($element->title!==null)
				$output.="</fieldset>\n";
		}
		return $output;
	}
	
	public function renderBody()
	{
		return $this->renderElements();
	}
	
	public function render()
	{
		return  $this->renderBegin() . $this->renderBody() . $this->renderEnd();
	}
	
	/**
	 * @return CBaseController the owner of this form. This refers to either a controller or a widget
	 * by which the form is created and rendered.
	 */
	public function getOwner()
	{
		$owner=$this->getParent();
		while($owner instanceof self)
			$owner=$owner->getParent();
		return $owner;
	}
	
	public function getActiveFormWidget()
	{
		if($this->_activeForm!==null)
			return $this->_activeForm;
		else
			return $this->getRoot()->_activeForm;
	}
	
	public function getRoot()
	{
		$root=$this;
		while($root->getParent() instanceof self)
			$root=$root->getParent();
		return $root;
	}
	
	protected function getUniqueId()
	{
		if(isset($this->attributes['id']))
			return 'dpform_'.$this->attributes['id'];
		else
			return 'dpform_'.sprintf('%x',crc32(serialize(array_keys($this->getElements()->toArray()))));
	}
	
	public function offsetExists($offset)
	{
		return $this->getElements()->contains($offset);
	}

	public function offsetGet($offset)
	{
		return $this->getElements()->itemAt($offset);
	}

	public function offsetSet($offset,$item)
	{
		$this->getElements()->add($offset,$item);
	}

	public function offsetUnset($offset)
	{
		$this->getElements()->remove($offset);
	}
	
	public function removedElement($name,$element,$forButtons)
	{
	}
}

class DFormRowsEl extends DFormProcessor
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
	
	public function init()
	{
		if($this->id===null)
			$this->id = 'form_'.$this->key;
	}
	
	public function render()
	{		
		$elements = $this->getColumns();
		$output = '';
		
		if(!$elements->getCount())
			return '';
		else if($elements->getCount()===1)
			$this->noWrap=true;			
		
			$output .= DHtml::openTag($this->elName, $this->setOptions());		
			foreach($elements as $column)
			{
				if(!$this->noWrap)
					$output .= DHtml::openTag($this->elName, array('class'=> 'dp_g50 dp_gl'));
				$output .= $column->render();
				if(!$this->noWrap)
					$output .= DHtml::closeTag($this->elName);
			}
			$output .= DHtml::closeTag($this->elName);
			
		return $output;
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
}

class DFormInputElement extends DFormElements
{
	public $type;
	public $name;
	public $hint;	
	public $layout="{label}\n{input}\n{hint}\n{error}";
	public $key;
	public $items=array();
	
	public $errorOptions=array();
	public $enableAjaxValidation=true;
	public $enableClientValidation=true;
	public $classPrefix='dp_fbox-';

	private $_label;
	private $_required;
	
	public function renderHint()
	{
		return $this->hint===null ? '' : '<div class="hint">'.$this->hint.'</div>';
	}	
	
	public function render()
	{ 
		if($this->type==='hidden')
			return $this->renderInput();
		$render=array(
			'{label}'=>$this->renderLabel(),
			'{input}'=>$this->renderInput(),
			'{hint}'=>$this->renderHint(),
			'{error}'=>$this->getParent()->showErrorSummary ? '' : $this->renderError(),
		);
		$output='';
		$output .= DHtml::openTag('div', array('class'=>$this->classPrefix.$this->type));
		$output .= strtr($this->layout,$render);
		$output .= DHtml::closeTag('div');
		return $output;
	}
	
	public function getRequired()
	{
		if($this->_required!==null)
			return $this->_required;
		else
			return $this->getParent()->getModel()->isAttributeRequired($this->name);
	}
	
	public function setRequired($value)
	{
		$this->_required=$value;
	}
	
	public function getLabel()
	{
		if($this->_label!==null)
			return $this->_label;
		else
			return $this->getParent()->getModel()->getAttributeLabel($this->name);
	}
	
	public function setLabel($value)
	{
		$this->_label=$value;
	}
	
	public function renderLabel()
	{ 
		$options = array(
			'label'=>$this->getLabel(),
			'required'=>$this->getRequired()
		);
		if(!empty($this->attributes['id']))
        {
            $options['for'] = $this->attributes['id'];
        }
		
		return DHtml::activeLabel($this->getParent()->getModel(), $this->name, $options);
	}
	
	public function renderInput()
	{
		if(isset(self::$coreTypes[$this->type]))
		{
			$method=self::$coreTypes[$this->type];
			if(strpos($method,'List')!==false)
				return CHtml::$method($this->getParent()->getModel(), $this->name, $this->items, $this->attributes);
			else
				return CHtml::$method($this->getParent()->getModel(), $this->name, $this->attributes);
		}
		else
		{
			$attributes=$this->attributes;
			$attributes['model']=$this->getParent()->getModel();
			$attributes['attribute']=$this->name;
			ob_start();
			$this->getParent()->getOwner()->widget($this->type, $attributes);
			return ob_get_clean();
		}
	}
	
	public function renderError()
	{
		$parent=$this->getParent();
		return $parent->getActiveFormWidget()->error($parent->getModel(), $this->name, $this->errorOptions, $this->enableAjaxValidation, $this->enableClientValidation);
	}
	
	protected function evaluateVisible()
	{
		return $this->getParent()->getModel()->isAttributeSafe($this->name);
	}
	
	public static $coreTypes=array(
		'text'=>'activeTextField',
		'hidden'=>'activeHiddenField',
		'password'=>'activePasswordField',
		'textarea'=>'activeTextArea',
		'file'=>'activeFileField',
		'radio'=>'activeRadioButton',
		'checkbox'=>'activeCheckBox',
		'listbox'=>'activeListBox',
		'dropdownlist'=>'activeDropDownList',
		'checkboxlist'=>'activeCheckBoxList',
		'radiolist'=>'activeRadioButtonList',
		'url'=>'activeUrlField',
		'email'=>'activeEmailField',
		'number'=>'activeNumberField',
		'range'=>'activeRangeField',
		'date'=>'activeDateField'
	);
}

class DFormButtonElement extends DFormElements
{
	public static $coreTypes=array(
		'htmlButton'=>'htmlButton',
		'htmlSubmit'=>'htmlButton',
		'htmlReset'=>'htmlButton',
		'button'=>'button',
		'submit'=>'submitButton',
		'reset'=>'resetButton',
		'image'=>'imageButton',
		'link'=>'linkButton',
	);
	public $type;
	public $name;
	public $label;
	
	private $_on;
	
	public function getOn()
	{
		return $this->_on;
	}
	
	public function render()
	{
		$attributes=$this->attributes;
		if(isset(self::$coreTypes[$this->type]))
		{
			$method=self::$coreTypes[$this->type];
			if($method==='linkButton')
			{
				if(!isset($attributes['params'][$this->name]))
					$attributes['params'][$this->name]=1;
			}
			else if($method==='htmlButton')
			{
				$attributes['type']=$this->type==='htmlSubmit' ? 'submit' : ($this->type==='htmlReset' ? 'reset' : 'button');
				$attributes['name']=$this->name;
			}
			else
				$attributes['name']=$this->name;
			if($method==='imageButton')
				return DHtml::imageButton(isset($attributes['src']) ? $attributes['src'] : '',$attributes);
			else
				return DHtml::$method($this->label,$attributes);
		}
		else
		{
			$attributes['name']=$this->name;
			ob_start();
			$this->getParent()->getOwner()->widget($this->type, $attributes);
			return ob_get_clean();
		}
	}
}

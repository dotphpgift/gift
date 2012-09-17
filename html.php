<?php

function pre($_value) { if($_value === null || $_value === false || $_value === true) { var_dump($_value); } else { echo "<pre>"; print_r($_value); echo "</pre>";	}}
function predie($_value) { pre($_value); exit(); }

class CMapIterator implements Iterator
{
	/**
	 * @var array the data to be iterated through
	 */
	private $_d;
	/**
	 * @var array list of keys in the map
	 */
	private $_keys;
	/**
	 * @var mixed current key
	 */
	private $_key;

	/**
	 * Constructor.
	 * @param array $data the data to be iterated through
	 */
	public function __construct(&$data)
	{
		$this->_d=&$data;
		$this->_keys=array_keys($data);
		$this->_key=reset($this->_keys);
	}

	/**
	 * Rewinds internal array pointer.
	 * This method is required by the interface Iterator.
	 */
	public function rewind()
	{
		$this->_key=reset($this->_keys);
	}

	/**
	 * Returns the key of the current array element.
	 * This method is required by the interface Iterator.
	 * @return mixed the key of the current array element
	 */
	public function key()
	{
		return $this->_key;
	}

	/**
	 * Returns the current array element.
	 * This method is required by the interface Iterator.
	 * @return mixed the current array element
	 */
	public function current()
	{
		return $this->_d[$this->_key];
	}

	/**
	 * Moves the internal pointer to the next array element.
	 * This method is required by the interface Iterator.
	 */
	public function next()
	{
		$this->_key=next($this->_keys);
	}

	/**
	 * Returns whether there is an element at current position.
	 * This method is required by the interface Iterator.
	 * @return boolean
	 */
	public function valid()
	{
		return $this->_key!==false;
	}
}

class CMap implements IteratorAggregate,ArrayAccess,Countable
{
	/**
	 * @var array internal data storage
	 */
	private $_d=array();
	/**
	 * @var boolean whether this list is read-only
	 */
	private $_r=false;

	public function __construct($data=null,$readOnly=false)
	{
		if($data!==null)
			$this->copyFrom($data);
		$this->setReadOnly($readOnly);
	}

	/**
	 * @return boolean whether this map is read-only or not. Defaults to false.
	 */
	public function getReadOnly()
	{
		return $this->_r;
	}


	protected function setReadOnly($value)
	{
		$this->_r=$value;
	}

	public function getIterator()
	{
		return new CMapIterator($this->_d);
	}

	public function count()
	{
		return $this->getCount();
	}

	public function getCount()
	{
		return count($this->_d);
	}

	public function getKeys()
	{
		return array_keys($this->_d);
	}
	public function itemAt($key)
	{
		if(isset($this->_d[$key]))
			return $this->_d[$key];
		else
			return null;
	}

	public function add($key,$value)
	{
		if(!$this->_r)
		{
			if($key===null)
				$this->_d[]=$value;
			else
				$this->_d[$key]=$value;
		}
		else
			throw new CException(Yii::t('yii','The map is read only.'));
	}

	public function remove($key)
	{
		if(!$this->_r)
		{
			if(isset($this->_d[$key]))
			{
				$value=$this->_d[$key];
				unset($this->_d[$key]);
				return $value;
			}
			else
			{
				// it is possible the value is null, which is not detected by isset
				unset($this->_d[$key]);
				return null;
			}
		}
		else
			throw new CException(Yii::t('yii','The map is read only.'));
	}

	public function clear()
	{
		foreach(array_keys($this->_d) as $key)
			$this->remove($key);
	}

	public function contains($key)
	{
		return isset($this->_d[$key]) || array_key_exists($key,$this->_d);
	}

	public function toArray()
	{
		return $this->_d;
	}

	public function copyFrom($data)
	{
		if(is_array($data) || $data instanceof Traversable)
		{
			if($this->getCount()>0)
				$this->clear();
			if($data instanceof CMap)
				$data=$data->_d;
			foreach($data as $key=>$value)
				$this->add($key,$value);
		}
		else if($data!==null)
			throw new CException(Yii::t('yii','Map data must be an array or an object implementing Traversable.'));
	}

	public function mergeWith($data,$recursive=true)
	{
		if(is_array($data) || $data instanceof Traversable)
		{
			if($data instanceof CMap)
				$data=$data->_d;
			if($recursive)
			{
				if($data instanceof Traversable)
				{
					$d=array();
					foreach($data as $key=>$value)
						$d[$key]=$value;
					$this->_d=self::mergeArray($this->_d,$d);
				}
				else
					$this->_d=self::mergeArray($this->_d,$data);
			}
			else
			{
				foreach($data as $key=>$value)
					$this->add($key,$value);
			}
		}
		else if($data!==null)
			throw new CException(Yii::t('yii','Map data must be an array or an object implementing Traversable.'));
	}
	public static function mergeArray($a,$b)
	{
		$args=func_get_args();
		$res=array_shift($args);
		while(!empty($args))
		{
			$next=array_shift($args);
			foreach($next as $k => $v)
			{
				if(is_integer($k))
					isset($res[$k]) ? $res[]=$v : $res[$k]=$v;
				else if(is_array($v) && isset($res[$k]) && is_array($res[$k]))
					$res[$k]=self::mergeArray($res[$k],$v);
				else
					$res[$k]=$v;
			}
		}
		return $res;
	}

	public function offsetExists($offset)
	{
		return $this->contains($offset);
	}

	public function offsetGet($offset)
	{
		return $this->itemAt($offset);
	}

	public function offsetSet($offset,$item)
	{
		$this->add($offset,$item);
	}

	/**
	 * Unsets the element at the specified offset.
	 * This method is required by the interface ArrayAccess.
	 * @param mixed $offset the offset to unset element
	 */
	public function offsetUnset($offset)
	{
		$this->remove($offset);
	}
}

class Collections extends CMap
{
	private $_el;
	private $_isColumns;
	
	public function __construct($el, $isColumns=false)
	{
		parent::__construct();
		$this->_el=$el;
		$this->_isColumns = $isColumns;
	}
	
	public function add($key,$value)
	{
		if(is_array($value))
		{			
			if(is_string($key))
			{
				$value['name']=$key; 
			}
			
			if($this->_isColumns)
				$element = new DColumnsEl($value, $this->_el); 
			else
			{
				if(isset($value['row']))
					$element = new DRowEl($value['row'], $this->_el);
			}
		}
		else
			$element = new DStringEl(array('content'=>$value), $this->_el);
		parent::add($key,$element);
	}
}

abstract class HtmlEngine
{
	private $_parent;
	public $attributes=array();
	
	abstract public function render();
	
	public function __construct($config, $parent)
	{
		$this->configure($config);
		$this->_parent=$parent;
	}
	
	public function configure($config)
	{
		if(is_array($config))
		{
			foreach($config as $name=>$value)
				$this->$name=$value;
		}
	}
	
	public function getParent()
	{
		return $this->_parent;
	}	
	
	public function __toString()
	{
		return $this->render();
	}
	
	public function __set($name,$value)
	{
		$setter='set'.$name;
		if(method_exists($this,$setter))
			$this->$setter($value);
		else
			$this->attributes[$name]=$value;
	}
	
	public function __get($name)
	{
		$getter='get'.$name;
		if(method_exists($this,$getter))
			return $this->$getter();
		else if(isset($this->attributes[$name]))
			return $this->attributes[$name];
		else
			die('error');
	}
}


class Html extends HtmlEngine
{
	public $attributes=array();
	private $_elements;
	private $_columns;
	private $_row;
	
	public function __construct($config, $parent=null)
	{
		parent::__construct($config, $parent);
	}
	
	public function getHeaders()
	{
		if($this->_elements===null)
			$this->_elements=new Collections($this,false);
		return $this->_elements;
	}
	
	public function getColumns()
	{
		if($this->_columns===null)
			$this->_columns=new Collections($this,true);
		return $this->_columns;
	}
	
	public function getRow()
	{
		if($this->_row===null)
			$this->_row=new Collections($this,false);
		return $this->_row;
	}
	
	public function setHeaders($elements)
	{
		$collection=$this->getHeaders();
		foreach($elements as $name=>$config)
			$collection->add($name,$config);
	}
	
	public function setColumns($columns)
	{
		$collection=$this->getColumns();
		foreach($columns as $name=>$config)
			$collection->add($name,$config);
	}
	
	public function setRow($row)
	{
		$collection=$this->getRow();
		foreach($row as $name=>$config)
			$collection->add($name,$config);
	}	
	
	public function render()
	{
		ob_start();
		foreach($this->getHeaders() as $rows)
		{
			echo($rows->render());
		}
		return ob_get_clean();
	}
}

class DRowEl extends Html
{
	public $htmlOptions;
	public $class;
	public $noWrap=false;
	public $id;
	public $name;
	
	public function render()
	{
		ob_start();
		echo "<div>sadasd";
		foreach($this->getColumns() as $columns)
			echo $columns->render();
		echo "</div>";
		return ob_get_clean();
	}
}

class DColumnsEl extends Html
{
	public $htmlOptions;
	public $class;
	public $name;
	public $value;
	public $id;
	
	public function render()
	{
		ob_start();
		echo "<div>" . $this->name . " = "  . $this->value . "</div>";
		return ob_get_clean();
		
	}
}

class DStringEl extends Html
{
	public $content;
	public $type='text';
	
	public function render()
	{
		ob_start();
		echo $this->content;
		return ob_get_clean();
	}
}

$html = new Html(
	array(
		'headers' => array(
			array(
				'row' => array(
					'htmlOptions'=>'dhanapal',
					'columns' => array(
				 		'block1' => array (
							'name' => 'blockId',
							'value' => 'Some.....',
						),
						'block2' => array (
							'name' => 'blockId2',
							'value' => 'DhanamKas',
						),
						'<p>Paragrapth</p>'
					)
				)
			),
			'<com:WigetClass name1="value1" name3={value3} />',		
		)
	)
);
echo $html;

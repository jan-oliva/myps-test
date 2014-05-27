<?php
namespace PM\DataSource\Proxy;

/**
 * Description of DoctrineArrayAccess
 *
 * @author oli
 */
class DoctrineEntityArrayAccess implements \ArrayAccess , \Iterator ,  \Countable
{

	protected $position = 0;	//position of iterator

	/**
	 * callbacks for special getters.
	 * @var array
	 */
	protected $getterCallbacks = array();
	
	/**
	 * Entity
	 * @var object
	 */
	protected $entity;
	
	protected $entityDataKeys = array();


	public function setEntityDataKeys($entityDataKeys)
	{
		$this->entityDataKeys = $entityDataKeys;
		return $this;
	}
	
	
	/**
	 * Add special callback for column name
	 * @param string $colName
	 * @param callback $callback
	 * @return DoctrineArrayAccess 
	 */
	public function addGetterCallback($colName,$callback)
	{
		$this->getterCallbacks[$colName] = $callback;
		return $this;
	}

	

	/**
	 * set all getter callbacks
	 * @param type $callbacks
	 * @return DoctrineArrayAccess 
	 */
	public function setGetterCallbacks($callbacks)
	{
		$this->getterCallbacks = $callbacks;
		return $this;
	}
	
	public function setEntity($entity)
	{
		$this->entity = $entity;
		return $this;
	}
	
	/**
	 * Set value for offset
	 * @param string $offset
	 * @param type $value
	 * @return type 
	 */
	public function offsetSet($offset, $value)
	{
		return $this->callSetter($offset, $value);
	} 
	
	/**
	 * called by isset() empty().
	 * 
	 * Attention: Not called by array_key_exists !!!
	 * 
	 * @param type $offset
	 * @return type 
	 */
	public function offsetExists($offset)
	{
		return method_exists($this->entity, $this->getterName($offset)) || isset($this->getterCallbacks[$offset]);
	}
	
	public function offsetUnset($offset)
	{
		return $this->callSetter($offset, null);
	}
	
	/**
	 * get value of offset
	 * @param type $offset
	 * @return type 
	 */
	public function offsetGet($offset)
	{
		return  $this->callGetter($offset);
	}

	

	/**
	 * Makes getter name for offset.
	 * Expects that getter name is 'get"EntityItem"'
	 * @param type $offset
	 * @return string
	 */
	private function getterName($offset)
	{
		$method = sprintf("get%s",ucfirst($offset));
		return $method;
	}
	
	private function setterName($offset)
	{
		$method = sprintf("set%s",ucfirst($offset));
		return $method;
	}
	
	/**
	 * Execute getter method.
	 * @param type $offset
	 * @return type 
	 */
	private function callGetter($offset)
	{
		$getter = $this->getterName($offset);
		if(isset($this->getterCallbacks[$offset]))
		{
			return call_user_func($this->getterCallbacks[$offset],$this->entity) ;
		}		
		
		if(method_exists($this->entity,$getter))
		{
			return call_user_func(array($this->entity,$getter));
		}		
		throw new \RuntimeException(sprintf("call undefine method %s on %s ",$getter,  get_class($this->entity)));
	}
	
	/**
	 * Execute setter method
	 * @param type $offset
	 * @param type $val
	 * @return type 
	 */
	private function callSetter($offset,$val)
	{
		$setter = $this->setterName($offset);
		if(method_exists($this->entity, $setter))
		{
			return call_user_func(array($this->entity,$setter),$val);
		}		
		throw new \RuntimeException(sprintf("call undefine method %s on %s ",$getter,  get_class($this->entity)));
	}

	/**
	 * (non-PHPdoc)
	 * @see Countable::count()
	 */
	public function count()
	{
		return sizeof($this->entityDataKeys);
	}

	/**
	 * (non-PHPdoc)
	 * @see Iterator::current()
	 */
	public function current()
	{
		return $this->callGetter($this->key());
	}

	/** (non-PHPdoc)
	 * @see Iterator::key()
	 */
	public function key()
	{
		$keys =  array_keys($this->entityDataKeys);
		return $keys[$this->position];
	}

	/**
	 * (non-PHPdoc)
	 * @see Iterator::next()
	 */
	public function next()
	{
		++$this->position;
	}

	/**
	 * (non-PHPdoc)
	 * @see Iterator::rewind()
	 */
	public function rewind()
	{
		$this->position = 0;
	}

	/**
	 * (non-PHPdoc)
	 * @see Iterator::valid()
	 */
	public function valid()
	{
		return array_key_exists($this->position, array_keys($this->entityDataKeys));
	}

	public function __get($offset)
	{
		return $this->callGetter($offset);
	}
	
	public function toArray()
	{
		$out = array();
		foreach($this->entityDataKeys as $offset=>$xx)
		{
			$out[$offset] = $this->callGetter($offset);
		}	
		return $out;
	}
}
?>
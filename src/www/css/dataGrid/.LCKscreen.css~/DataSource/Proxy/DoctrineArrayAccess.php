<?php
namespace PM\DataSource\Proxy;

/**
 * Description of DoctrineArrayAccess
 *
 * @author oli
 */
class DoctrineArrayAccess implements \ArrayAccess
{
	/**
	 * callbacks for special getters.
	 * @var array
	 */
	protected $getterCallbacks = array();
	
	/**
	 * Entity
	 * @var type 
	 */
	protected $data;
	
	public function __construct($data)
	{
		$this->setData($data);
		
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
	
	public function setData($data) 
	{
		$this->data = $data;
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
		return method_exists($this->data, $this->getterName($offset)) || isset($this->getterCallbacks[$offset]);
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
			return call_user_func($this->getterCallbacks[$offset],$this->data) ;
		}		
		
		if(method_exists($this->data,$getter))
		{
			return call_user_func(array($this->data,$getter));
		}		
		throw new \RuntimeException(sprintf("call undefine method %s on %s ",$getter,  get_class($this->data)));
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
		if(method_exists($this->data, $setter))
		{
			return call_user_func(array($this->data,$setter),$val);
		}		
		throw new \RuntimeException(sprintf("call undefine method %s on %s ",$getter,  get_class($this->data)));
	}
}
?>
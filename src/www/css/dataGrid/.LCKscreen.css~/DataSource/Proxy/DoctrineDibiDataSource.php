<?php
namespace PM\DataSource\Proxy;
/**
 * Description of EntityDibiDataSource
 *
 * @author Jan Oliva
 * @version 0.1.0
 */
class DoctrineDibiDataSource /*extends \DibiDataSource*/ implements IDataSource
{
	

	protected $result;

	protected $limit=10;

	protected $offset = null;

	protected $sorting = array();

	protected $filter = array();

	/**
	 * Special gettr callbacks
	 * @var type 
	 */
	protected $getterCallbacks = array();
	
	/**
	 * Add special callback for getting some not scallar value.
	 * @param type $colName
	 * @param callback $callback
	 * @return DoctrineDibiDataSource 
	 */
	public function addGetterCallback($colName,$callback)
	{
		$this->getterCallbacks[$colName] = $callback;
		return $this;
	}
	
	public function getGetterCallbacks()
	{
		return $this->getterCallbacks;
	}
	
	/**
	 *
	 * @var object  - Entita
	 */
	protected $entity;
	
	/**
	 *
	 * @var string
	 */
	protected $entitytName;
	
	public function setEntitytName($entitytName) 
	{
		$this->entitytName = $entitytName;
		return $this;
	}
	
	
	/**
	 *
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $entityManager;
	
	public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager) 
	{
		$this->entityManager = $entityManager;
		return $this;
	}

	/**
	 * Returns the number of rows in a given data source.
	 * @return int
	 */	
	public function count()
	{
		return $this->getTotalCount();
	}
	
	public function getIterator()
	{
		return $this->getResult();
	}
	
	public function select($col, $as = NULL)
	{
		return $this;
	}
	
	public function where($cond)
	{
		return $this;
	}
	
	public function orderBy($row, $sorting = 'ASC')
	{
		$this->sorting[$row] = $sorting;
		return $this;
	}
	
	public function applyLimit($limit, $offset = NULL)
	{
		$this->limit = $limit;
		$this->offset = $offset;
		//print_r(func_get_args());
		return $this;
	}
	
	final public function getConnection()
	{
		
	}
	
	/**
	 * Returns (and queries) DibiResult.
	 * @return DibiResult
	 */
	public function getResult()
	{
		//print(__METHOD__."<br/>");
		$debug = new \stdClass();
		$debug->limit = $this->limit;
		$debug->offset = $this->offset;
		///print_r($debug);
		$ret = array();
		if ($this->result === null)
		{
			//predefined arrayObject
			$arrAccess = new DoctrineEntityArrayAccess();
			$arrAccess->setEntityDataKeys($this->entityManager->getClassMetadata($this->entitytName)->fieldNames)
				->setGetterCallbacks($this->getGetterCallbacks());

			$res = $this->entityManager->getRepository($this->entitytName)->findBy($this->filter,$this->sorting,$this->limit,$this->offset);

			foreach ($res as $key=>$entity)
			{
				$ret[$key] = clone($arrAccess);	//clone predefined arrayAccess
				$ret[$key]->setEntity($entity);	//set entity to arrayAccess object
			}	
		}
		$this->result = new \ArrayIterator($ret);
		//print("<pre>");
		//print_r($this->entityManager->getClassMetadata($this->entitytName));
		//die();
		return $this->result;
	}
	
	public function fetch()
	{
		return $this->getResult()->current();
	}
	
	public function fetchSingle()
	{
		
	}
	
	/**
	 * Fetches all records from table.
	 * @return array
	 */
	public function fetchAll()
	{
		
	}
	
	public function fetchAssoc($assoc)
	{
		
	}
	public function fetchPairs($key = NULL, $value = NULL)
	{
		
	}
	
	/**
	 * Discards the internal cache.
	 * @return void
	 */
	public function release()
	{
		$this->result = $this->count = $this->totalCount = NULL;
		return $this;
	}
	
	/**
	 * Returns the number of rows in a given data source without limit.
	 * @return int
	 */
	public function getTotalCount()
	{
		return 1000;
		//return $this->result->count();
	}
}

?>

<?php
namespace PM\DataSource\Proxy;

interface IDataSource extends \Countable, \IteratorAggregate
{
	const SORT_ASC = 'ASC';
	const SORT_DESC = 'DESC';

	/**
	 * set where condition
	 */
	public function where($cond);

	/**
	 * Set limit
	 */
	public function applyLimit($limit, $offset = NULL);

	/**
	 * Retun results
	 */
	public function getResult();

	/**
	 * Set sorting
	 */
	public function orderBy($row, $sorting = 'ASC');
	
	public function getTotalCount();
}
?>
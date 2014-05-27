<?php

namespace Entity\User;

use Kdyby\Doctrine\EntityDao;


/**
 * Description of RightResourceRepository
 *
 * @author Jan Oliva
 */
class RightResourceRepository extends EntityDao
{

	/**
	 *
	 * @return \Grido\DataSources\Doctrine
	 */
	public function getGridoDataSource()
	{
		$qb = $this->createQueryBuilder('rrsc');
		$ds = new \Grido\DataSources\Doctrine($qb);
		return $ds;
	}



	public function getSelectOptions()
	{
		$res = $this->select()->execute();

		$options = array();
		foreach($res as $item){
			$options[$item->getId()] = $item->getName();
		}

		return $options;
	}
}

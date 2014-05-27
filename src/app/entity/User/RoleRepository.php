<?php

namespace Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Grido\DataSources\Doctrine;
use Kdyby\Doctrine\EntityDao;
use PM\DataGrid\DataSources\Doctrine\QueryBuilder;

/**
 * Description of RoleRepository
 * Entity for user role
 *
 * @author Jan Oliva
 */
class RoleRepository extends EntityDao
{
	/**
	 *
	 * Returns data source for Dastagrid
	 * @return Doctrine
	 *
	 */
	public function getDatagridoDatasource()
	{
		$qb = $this->createQueryBuilder('role');
		$dataSource = new \Grido\DataSources\Doctrine($qb,array(

		));
		return $dataSource;
	}


	/**
	 * Returns array of roleID=>name for select box
	 * @return array
	 */
	public function getSelectOptions()
	{
		$options = array();

		foreach($this->createQueryBuilder('role')->getQuery()->execute() as $item){
				$options[$item->getId()] = $item->getName()." (".$item->getLabel().")";
		}

		return $options;
	}

	/**
	 * Returns data source unassigned roles for given user id
	 * @return QueryBuilder
	 *
	 */
//	public function getUnassignedRolesDataSource($userID)
//	{
//		$roles = $this->getEntityManager()->getRepository('\Entity\User\User')->getRoleIDs($userID);
//		/* @var $roles ArrayCollection */
//
//		$ex = $this->createQueryBuilder()->expr()->notIn('role.id', $roles->getValues());
//		$fluent = $this->createDSquery()->where($ex);
//
//		$ds = new QueryBuilder($fluent);
//		$this->setDSMapping($ds);
//
//		return $ds;
//	}

	/**
	 *
	 * @param type $userID
	 * @return \Grido\DataSources\Doctrine
	 */
	public function getUnassignedRolesODataSource($userID)
	{
		$roles = $this->getEntityManager()->getRepository('\Entity\User\User')->getRoleIDs($userID);
		/* @var $roles ArrayCollection */

		$fluent = $this->createQueryBuilder('role');

		if($roles->count() > 0){
			$ex = $this->createQueryBuilder()->expr()->notIn('role.id', $roles->getValues());
			$fluent->where($ex);
		}

		$ds = new \Grido\DataSources\Doctrine($fluent);

		return $ds;
	}
}

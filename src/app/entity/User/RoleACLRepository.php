<?php

namespace Entity\User;

use Kdyby\Doctrine\EntityDao;
use PM\DataGrid\DataSources\Doctrine\QueryBuilder;

/**
 * Description of RoleACLRepository
 *
 * @author Jan Oliva
 */
class RoleACLRepository extends EntityDao
{

	public function getGridoDataSource()
	{
		$qb = $this->createQueryBuilder('acl')
				->addSelect('role')
				->addSelect('resource')
				->leftJoin('acl.role', 'role')
				->leftJoin('acl.rightResource', 'resource');

		$ds = new \Grido\DataSources\Doctrine($qb, array(
			'role_name'=>'role.name',
			'resource_name'=>'resource.name'
		));

		return $ds;
	}

	/**
	 *
	 * @param RoleACL $aclEntity
	 * @param int $roleID
	 * @param int $resourceID
	 */
	public function addRecord($aclEntity,$roleID,$resourceID)
	{
		$role = $this->getEntityManager()->find('\Entity\User\Role', $roleID);
		$resource = $this->getEntityManager()->find('\Entity\User\RightResource', $resourceID);
		$aclEntity->setRole($role);
		$aclEntity->setRightResource($resource);
		$this->save($aclEntity);
	}
}

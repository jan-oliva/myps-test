<?php

namespace TestModule\UsersModule\Components\Acl;

use JO\Nette\Doctrine\EntityGridoManager;

/**
 * Description of GridBuilder
 *
 * @author Jan Oliva
 */
class GridoBuilder extends EntityGridoManager
{

	/**
	 *
	 * @return Grido\Grid
	 */
	public function createGrid()
	{
		$this->dg->model = $this->em->getRepository($this->entity)->getGridoDataSource();

		$this->dg->addColumnText('role_name', 'role')->setCustomRender(function($entity){
			return $entity->getRole()->getName();
		})->setFilterText();



		$this->dg->addColumnText('resource_name', 'zdroj oprávnění')->setCustomRender(function($entity){
			return $entity->getRightResource()->getName();
		});

		$this->dg->getFilter('role_name')->setWhere(function($val,\Kdyby\Doctrine\QueryBuilder $qb){
			$qb->where("role.name=?1",$val);

		});
		//automaticke pridani cols ze zakladni entity
		$this->addCols(array('privilege','allow'));

		return $this->dg;
	}

}

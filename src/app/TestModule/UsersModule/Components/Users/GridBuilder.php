<?php

namespace TestModule\UsersModule\Components\Users;

use JO\Nette\Doctrine\EntityGridoManager;

/**
 * Description of GridoBuilder
 *
 * @author Jan Oliva
 */
class GridBuilder extends EntityGridoManager
{

	public function createGrid()
	{

		$this->dg->model = $this->em->getRepository($this->entity)->getGridoDataSource();
		$this->dg->setPrimaryKey('id');

		$mng1 = new EntityGridoManager('\Entity\Person\Person', $this->em,$this->dg);
		//pridani sloupcu dle entity Person
		$mng1->addCols(array('name','surname'));


		$this->dg->getColumn('name')->setCustomRender(function($entity){
			/* @var $entity \Entity\User\User */
			return $entity->getPerson()->getName();
		});
		$this->dg->getColumn('name')
				->setSortable()
				->setFilterText();

		$this->dg->getColumn('surname')->setCustomRender(function($entity){
			/* @var $entity \Entity\User\User */
			return $entity->getPerson()->getSurname();
		});
		$this->dg->getColumn('surname')
				->setSortable()
				->setFilterText();

		//pridani sloupcu dle entity User
		$this->addCols(array('login','active'));

		$this->dg->getColumn('login')
				->setSortable()
				->setFilterText();

		return $this->dg;
	}

}

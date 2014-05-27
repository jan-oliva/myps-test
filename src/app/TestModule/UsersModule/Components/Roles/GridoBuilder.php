<?php

namespace TestModule\UsersModule\Components\Roles;

use JO\Nette\Doctrine\EntityGridoManager;

/**
 * Description of GridBuilder
 *
 * @author Jan Oliva
 */
class GridoBuilder extends EntityGridoManager
{

	public function createGrid()
	{
		$this->dg->model = $this->em->getRepository($this->entity)->getDatagridoDatasource();

		$this->dg->setPrimaryKey('id');
		//pridani sloupcu dle entity Role
		$this->addCols(array('name','label'),true);
		$this->cols['name']
				->setSortable();

		return $this->dg;
	}

}

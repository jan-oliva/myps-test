<?php

namespace TestModule\UsersModule\Components\Roles;

use JO\Nette\Doctrine\EntityGridManager;

/**
 * Description of GridBuilder
 *
 * @author Jan Oliva
 */
class GridBuilder extends EntityGridManager
{

	public function createGrid()
	{
		$this->dg->setDataSource($this->em->getRepository($this->entity)->getDatagridDatasource());
		$this->dg->keyName = 'id';

		//pridani sloupcu dle entity User
		$this->addCols(array('name','label'));

		$this->dg['name']->addFilter();
		$this->dg['label']->addFilter();
		$this->dg->addActionColumn('action');
		return $this->dg;
	}

}

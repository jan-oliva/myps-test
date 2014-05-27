<?php

namespace TestModule\UsersModule\Components\RightResources;

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
	 * @return \PM\DataGrid\DataGrid
	 */
	public function createGrid()
	{
		$this->dg->model = $this->em->getRepository($this->entity)->getGridoDataSource();

		//pridani sloupcu dle entity User
		$this->addCols(array('name','label'));
		$this->cols['name']->setFilterText();

		return $this->dg;
	}

}

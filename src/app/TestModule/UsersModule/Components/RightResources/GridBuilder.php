<?php

namespace TestModule\UsersModule\Components\RightResources;

use JO\Nette\Doctrine\EntityGridManager;

/**
 * Description of GridBuilder
 *
 * @author Jan Oliva
 */
class GridBuilder extends EntityGridManager
{

	/**
	 *
	 * @return \PM\DataGrid\DataGrid
	 */
	public function createGrid()
	{
		$this->dg->setDataSource($this->em->getRepository($this->entity)->getDatagridDatasource());
		$this->dg->keyName = 'id';

		//pridani sloupcu dle entity User
		$this->addCols(array('name','label'));

		$this->dg['name']->addFilter();
		$this->dg['label']->addFilter();
		$this->dg->addActionColumn('action');

		//$this->dg->setRenderer(new \PM\DataGrid\Renderers\Bootstrap\SbAdmin());

		return $this->dg;
	}

}

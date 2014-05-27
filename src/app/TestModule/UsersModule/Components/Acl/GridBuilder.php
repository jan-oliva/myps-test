<?php

namespace TestModule\UsersModule\Components\Acl;

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
		$this->dg->rememberState = true;

		$this->dg->addColumn('role_name', 'role');

		$this->dg->addColumn('resource_name', 'zdroj oprávnění');

		//automaticke pridani cols ze zakladni entity
		$this->addCols(array('privilege','allow'));

		$this->dg['role_name']->addFilter();
		$this->dg['resource_name']->addFilter()->getFormControl()->getControlPrototype();
		/* @var $f \Nette\Utils\Html */
		//$f->maxlength = 20;
		///\Nette\Diagnostics\Debugger::barDump($f);
		$this->dg['privilege']->addFilter();

		$this->dg->addActionColumn('action');

		return $this->dg;
	}

}

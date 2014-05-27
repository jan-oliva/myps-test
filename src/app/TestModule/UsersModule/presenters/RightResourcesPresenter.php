<?php

namespace TestModule\UsersModule;

use Grido\Grid;
use JO\Nette\Application\UI\SbAdmin\Icons\Icon;
use TestModule\UsersModule\Components\RightResources\FormBuilder;
use TestModule\UsersModule\Components\RightResources\GridoBuilder;
use Nette\Application\UI\Form;
use Nette\Application\UI\Link;
use Nette\Forms\Controls\Button;
use PM\DataGrid\DataGrid;

/**
 * Description of RightResourcesPresenter
 * Administrace zdroju opravneni pro ACL
 *
 * @author Jan Oliva
 */
class RightResourcesPresenter extends AUsersPresenter
{

	protected $entityName = '\Entity\User\RightResource';

	/**
	 * Definice akce pro pridani zaznamu
	 */
	public function actionAdd()
	{
		$form = $this->getComponent('rightResourceForm');
		/* @var $form Form */

		$submit = $form->addSubmit(FormBuilder::SUBMIT_ADD, 'přidat zdroj oprávnění');
		$submit->onClick[] = callback($this,'add');

		$form->addSubmit('stortno', "storno")->setValidationScope(false);
	}

	/**
	 * Definice akce pro editaci zanamu
	 * @param type $id
	 */
	public function actionEdit($id)
	{
		$form = $this->getComponent('rightResourceForm');
		/* @var $form Form */

		$submit = $form->addSubmit(FormBuilder::SUBMIT_EDIT, 'upravit zdroj oprávnění');
		$submit->onClick[] = callback($this,'edit');

		$form->addSubmit('stortno', "storno")->setValidationScope(false)->onClick[] = callback($this,'formStorno');
	}

	public function formStorno()
	{
		$this->redirect('list');
	}

	/**
	 * Zobrazeni editacniho formulare
	 * @param type $id
	 */
	public function renderEdit($id)
	{
		$form = $this->getComponent('rightResourceForm');
		/* @var $form Form */

		$entity = $this->entityManager->find($this->entityName, $id);
		$this->getFormManager($form, $this->entityName)->fillFormFromEntity($entity);
	}

	/**
	 * Pridani zdroje opravneni
	 * @param Button $button
	 */
	public function add(Button $button)
	{
		$entity = $this->getFormManager($button->getForm(), $this->entityName)->createEntityFromForm();
		$this->entityManager->persist($entity);
		$this->entityManager->flush();
		$this->flashMessage("Oprávnění '{$entity->getName()}' bylo přidáno", 'alert-success');
		$this->redirect('edit',array('id'=>$entity->getId()));
	}

	/**
	 * Uprava opravneni
	 * @param Button $button
	 */
	public function edit(Button $button)
	{
		$entity = $this->entityManager->find($this->entityName, $this->request->parameters['id']);
		$this->getFormManager($button->getForm(), $this->entityName)->fillEntityFromForm($entity, array());

		$this->entityManager->persist($entity);
		$this->entityManager->flush();
		$this->redirect('list');
	}

	public function renderList()
	{
		$dg = $this->getComponent('rightResourcesGrid');
		/* @var $dg \Grido\Grid */
		$dg->addActionHref('edit', '', 'edit')->setIcon('pencil');

	}

	/**
	 * formular zdroje opravneni
	 * @param type $name
	 * @return Form
	 */
	protected function createComponentRightResourceForm($name)
	{
		$builder = new FormBuilder($this->context, new Form($this,$name));
		$builder->createForm();

		return $builder->getForm();
	}


	/**
	 *
	 * Komponenta datagridu
	 * @param type $name
	 * @return DataGrid
	 */
	protected function createComponentRightResourcesGrid($name)
	{
		$builder = new GridoBuilder($this->entityName, $this->entityManager, new Grid($this, $name));
		$dg = $builder->createGrid();

		return $dg;
	}
}

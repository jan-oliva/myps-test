<?php

namespace TestModule\UsersModule;

use Entity\User\RoleRepository;
use Entity\User\UserRepository;
use Grido\Grid;
use JO\Nette\Application\UI\SbAdmin\Icons\Icon;
use TestModule\UsersModule\Components\Roles\FormBuilder;
use TestModule\UsersModule\Components\Roles\GridBuilder;
use TestModule\UsersModule\Components\Roles\GridoBuilder;
use Nette\Application\UI\Form;
use Nette\Diagnostics\Debugger;
use Nette\Forms\Controls\Button;
use Nette\Utils\Html;

/**
 * Description of RolesPresenter
 *
 * Presenter pro administraci uzivatelskych roli
 *
 * @author Jan Oliva
 */
class RolesPresenter extends AUsersPresenter
{

	protected $entitytName = '\Entity\User\Role';

	/**
	 *
	 * @var UserRepository
	 */
	protected $userRoleRepository;

	public function injectUserRoleRepository(RoleRepository $repository)
	{
		$this->userRoleRepository = $repository;
	}

	/**
	 * Nastaveni akce pridavaciho formulare
	 */
	public function actionAdd()
	{
		$form = $this->getComponent('userRoleForm');
		/* @var $form Form */
		$form->addSubmit(FormBuilder::SUBMIT_ADD, "přidat")
				->onClick[] = callback($this,'add');
		$form->addSubmit("storno", "storno")->validationScope = false;

	}

	/**
	 * Nastaveni akce editacniho formulare
	 * @param type $id
	 */
	public function actionEdit($id)
	{
		$form = $this->getComponent('userRoleForm');
		/* @var $form Form */
		$form->addSubmit(FormBuilder::SUBMIT_EDIT, "upravit")
				->onClick[] = callback($this,'edit');
		$form->addSubmit("storno", "storno")->validationScope = false;


	}

	/**
	 * Zobrazeni a doplneni editacniho formulare.
	 * @param type $id
	 */
	public function renderEdit($id)
	{
		$form = $this->getComponent('userRoleForm');
		/* @var $form Form */
		$entity = $this->userRoleRepository->find($id);

		$this->getFormManager($form,$this->entitytName)->fillFormFromEntity($entity);
	}

	/**
	 * Zobrazeni data gridu
	 */
	public function renderList()
	{
		$dg = $this->getComponent('userRolesGrid');
		/* @var $dg Grid */
		//$_this = $this;
		$dst = $dg->addActionHref('edit', 'editace','edit');
		Debugger::barDump($dst);


	}

	/**
	 * callback pro upravu role
	 * @param Button $button
	 *
	 */
	public function edit(Button $button)
	{
		$entity = $this->entityManager
				->find('\Entity\User\Role',$this->request->parameters['id']);

		$this->getFormManager($button->getForm(),$this->entitytName)
				->fillEntityFromForm($entity);

		$this->userRoleRepository->save($entity);

		$this->flashMessage("Role '{$entity->getName()}' byla uložena", 'alert-success');
		$this->redirect('list');
	}

	/**
	 * callback pro pridani role
	 * @param Button $button
	 */
	public function add(Button $button)
	{
		$entity = $this->getFormManager($button->getForm(),$this->entitytName)->createEntityFromForm();
		$this->userRoleRepository->save($entity);

		$this->flashMessage("role byla uložena", 'alert-success');
		$this->redirect('list');
	}

	/**
	 * Data grid roli
	 * @param string $name
	 * @return \Nette\Application\UI\Control
	 */
	protected function createComponentUserRolesGrid($name)
	{
		$builder = new GridoBuilder('\Entity\User\Role', $this->entityManager,new Grid($this,$name));
		$dg = $builder->createGrid();
		return $dg;
	}


	/**
	 * Formular role
	 * @param type $name
	 * @return type
	 */
	protected function createComponentUserRoleForm($name)
	{
		$builder = new FormBuilder($this->context, new Form($this, $name));
		return $builder->createForm();

	}


}

<?php

namespace TestModule\UsersModule;

use Doctrine\Common\Collections\ArrayCollection;
use Entity\User\RoleRepository;
use Entity\User\UserRepository;
use JO\Nette\Application\UI\Dialog\Bootstrap\Modal;
use JO\Nette\Application\UI\SbAdmin\Icons\Icon;
use JO\Nette\Doctrine\EntityFormManager;
use JO\Security\Authenticator\IPasswordGenerator;
use TestModule\UsersModule\Components\FormBuilder;
use TestModule\UsersModule\Components\GridBuilder;
use Nette\Application\UI\Form;
use Nette\Application\UI\Link;
use Nette\Forms\Controls\Button;
use PM\DataGrid\DataGrid;

/**
 * Description of Users
 * Sprava uzivatelu
 *
 * @author Jan Oliva
 */
class UsersPresenter extends AUsersPresenter
{

	protected $formWithPass = false;

	/**
	 *
	 * @var UserRepository
	 */
	protected $userRepository;

	/**
	 *
	 * @var RoleRepository
	 */
	protected $roleRepository;

	public function injectUserRepository(UserRepository $ur,IPasswordGenerator $passwordGenerator)
	{
		$this->userRepository = $ur;
		$this->userRepository->setPasswordGenerator($passwordGenerator);
	}

	public function injectRoleRepository(RoleRepository $roleRep)
	{
		$this->roleRepository = $roleRep;
	}

	public function renderList()
	{
		$dg = $this->getComponent('usersGrid');
		/* @var $dg \Grido\Grid */
		$dg->addActionHref('edit','', 'edit')

				->setIcon('pencil');

	}

	/**
	 * Akce pridani uzivatele
	 */
	public function actionAdd()
	{
		$this->formWithPass = true;

		$form = $this->getComponent('userForm');
		/* @var $form Form */

		$form->addSubmit(FormBuilder::SUBMIT_ADD, "Vytvořit uživatele")
				->onClick[] = callback($this, 'add');
	}

	/**
	 *
	 * @param type $id
	 */
	public function actionEdit($id)
	{

		$form = $this->getComponent('userForm');
		/* @var $form Form */

		$form->addSubmit(FormBuilder::SUBMIT_EDIT, "Upravit uživatele")
				->onClick[] = callback($this, 'edit');
	}

	/**
	 * Zobrazeni editace uzivatele
	 * @param int $id - idecko editovaneho uzivatele
	 */
	public function renderEdit($id)
	{
		$form = $this->getComponent('userForm');
		/* @var $form Form */

		$entity = $this->userRepository->find($id);
		if(is_null($entity)){

			$this->flashMessage('uživatel nebyl nalezen', 'alert-warning');
			\Nette\Diagnostics\Debugger::log("User id {$id} not found", \Nette\Diagnostics\Debugger::INFO);
			$this->redirect('list');
			return;
		}

		/* @var $entity \Entity\User\User */
		$this->getUserFormManager($form)->fillFormFromEntity($entity);
		$this->getPersonFormManager($form)->fillFormFromEntity($entity->getPerson());
		$roles = $entity->getRoles();
		/* @var $roles ArrayCollection */

		$this->template->roles = $roles->getIterator();

		$this->template->userID = $id;

		$dg = $this->getComponent('userPossibleRolesGrid');
		/* @var $dg DataGrid */

		/* @var $dg \Grido\Grid */
		$dg->model = $this->roleRepository->getUnassignedRolesODataSource($this->request->parameters['id']);
		$_this = $this;
		//link na akci prirazeni role
		$dg->addActionHref('assign', '')
				->setCustomHref(function(\Entity\User\Role $role)use($_this,$id){
					$link = $_this->link('assign!',array(
						'id'=>$id,
						'roleID'=>$role->getId(),
					));
					return $link;
				})->setIcon(Icon::SAVE);
		$dg->getAction('assign')->getElementPrototype()->attrs['title'] = 'přiřadit roli ';

		if($this->getUser()->isAllowed('Test:Users:Roles',':edit')){
			//link na editacin role
			$dg->addActionHref('roleEdit', '','Roles:edit')
					->setIcon(Icon::EDIT);
			$dg->getAction('roleEdit')->getElementPrototype()->attrs['title'] = 'editace role';
		}
		if($this->isAjax()){
			$this->redrawControl('rolesAdmin');
		}
	}

	/**
	 * callback na upravu uzivatele
	 * @param Button $button
	 */
	public function edit(Button $button)
	{

		$user = $this->userRepository->find($this->request->parameters['id']);
		/* @var $user \Entity\User\User */
		$person = $user->getPerson();

		$this->getUserFormManager($button->getForm())->fillEntityFromForm($user,array('F_pass'));
		$this->getPersonFormManager($button->getForm())->fillEntityFromForm($person);
		$values = $button->getForm()->getValues();

		$changePass = !empty($values['F_pass']);
		$this->userRepository->update($user, $person,$changePass,$values['F_pass']);
		$this->flashMessage("Uživatel '{$person->getName()} {$person->getSurname()}' byl upraven");

		$this->redirect('list');
	}

	/**
	 * callback pridani uzivatele
	 * @param Button $button
	 */
	public function add(Button $button)
	{
		$person = $this->getPersonFormManager($button->getForm())->createEntityFromForm();
		$user = $this->getUserFormManager($button->getForm())->createEntityFromForm();

		$user->setPerson($person);

		$this->userRepository->insert($user);
		$this->flashMessage("Uživatel '{$person->getName()} {$person->getSurname()}' byl přidán");
		$this->redirect('list');
	}

	/**
	 *
	 * @param string $name
	 * @return \Nette\Application\UI\Control
	 */
	protected function createComponentUserForm($name)
	{
		$builder = new FormBuilder($this->context, new Form($this, $name));

		return $builder->createForm($this->formWithPass);
	}

	/**
	 * Odebrani role uzivateli
	 * @param int $roleID
	 * @param int $id - user id
	 * @return type
	 */
	public function handleUnassign($roleID,$id)
	{
		$this->userRepository->removeRole($id, $roleID);
		if($this->isAjax()){
			$this->redrawControl('rolesAdmin');
			return;
		}
		$this->redirect('edit',array('id'=>$id));
	}

	/**
	 * Prirazeni role uzivateli
	 * @param int $roleID
	 * @param int $id - user id
	 * @return type
	 */
	public function handleAssign($roleID,$id)
	{
		try{
			$this->userRepository->addRole($id, $roleID);
			//$this->flashMessage('Role byla přidána','alert-success');
		} catch (\Kdyby\Doctrine\DuplicateEntryException $e) {
			$this->flashMessage('Role je již přižazena','alert-danger');
		}


		if($this->isAjax()){
			$this->redrawControl('rolesAdmin');
			return;
		}
		$this->redirect('edit',array('id'=>$id));
	}

	/**
	 * Grid vypisu uzivatelu
	 * @param string $name
	 * @return \Nette\Application\UI\Control
	 */
	protected function createComponentUsersGrid($name)
	{
		$builder = new Components\Users\GridBuilder('\Entity\User\User', $this->entityManager, new \Grido\Grid($this, $name));
		$dg = $builder->createGrid();

		return $dg;
	}

	/**
	 * Grid neprirazenych roli
	 * @param type $name
	 * @return \Nette\Application\UI\Control
	 */
	protected function createComponentUserPossibleRolesGrid($name)
	{
		$builder = new Components\Roles\GridoBuilder('\Entity\User\Role', $this->entityManager,new \Grido\Grid($this,$name));
		$dg = $builder->createGrid();

		return $dg;

	}

	/**
	 *
	 * @param Form $form
	 * @return EntityFormManager
	 */
	protected function getPersonFormManager(Form $form)
	{
		return new EntityFormManager($form, '\Entity\Person\Person', $this->entityManager);
	}

	/**
	 *
	 * @param Form $form
	 * @return EntityFormManager
	 */
	protected function getUserFormManager(Form $form)
	{
		return new EntityFormManager($form, '\Entity\User\User', $this->entityManager);
	}

}

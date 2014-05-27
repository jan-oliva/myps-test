<?php

namespace TestModule\UsersModule;

use Entity\User\RoleACLRepository;
use JO\Nette\Application\UI\Dialog\Bootstrap\Modal;
use JO\Nette\Application\UI\SbAdmin\Icons\Icon;
use JO\Nette\Doctrine\EntityFormManager;
use TestModule\UsersModule\Components\Acl\FormBuilder;
use TestModule\UsersModule\Components\Acl\GridBuilder;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\Button;
use Nette\Forms\Controls\SubmitButton;
use PM\DataGrid\DataGrid;

/**
 * Description of AclPresenter
 *
 * Presenter pro spravu ACL (access control list)
 *
 * @author Jan Oliva
 */
class AclPresenter extends AUsersPresenter
{

	/**
	 *
	 * @var string
	 */
	protected $entityName = '\Entity\User\RoleACL';

	/**
	 *
	 * @var RoleACLRepository
	 */
	protected $roleAclRepository;

	/**
	 *
	 * @param RoleACLRepository $repository
	 */
	public function injectRoleAclRepository(RoleACLRepository $repository)
	{
		$this->roleAclRepository = $repository;
	}

	/**
	 * Vypis ACL
	 * @return type
	 */
	public function renderList()
	{
		$dg = $this->getComponent('groupAclGrid');
		/* @var $dg \Grido\Grid */

		$dg->addActionHref('remove', '', 'remove')
				->setIcon(Icon::DELETE)
				->setConfirm(function($item){
				return "Opravdu odebrat acl '{$item->getRole()->getName()} {$item->getRightResource()->getName()}' ?";
			});
		$dg->getAction('remove')->getElementPrototype()->attrs['title'] = 'odebrat záznam ACL !!';

	}

	/**
	 * Akce odebrani ACL
	 * @param type $id
	 * @return type
	 */
	public function actionRemove($id)
	{
		$entity = $this->roleAclRepository->find($id);
		\Nette\Diagnostics\Debugger::barDump($entity);
		if(is_null($entity)){
			$this->flashMessage("ACL id '{$id}' neexistuje", 'alert-warning');
			$this->redirect('list');
			return;
		}
		$this->roleAclRepository->delete($entity);
		$this->flashMessage("ACL '{$entity->getPrivilege()}' bylo smazáno", 'alert-success');
		$this->redirect('list');
	}

	/**
	 * Definice akce pro pridani ACL
	 */
	public function actionAdd()
	{
		$form = $this->getComponent('aclForm');
		/* @var $form Form */
		$save = $form->addSubmit(FormBuilder::SUBMIT_ADD, 'přidat ACL');
		/* @var $save SubmitButton */
		$save->onClick[] = callback($this,'add');
	}

	/**
	 * Pridani zaznamu do ACL
	 * @param Button $button
	 */
	public function add(Button $button)
	{
		$aclEntity = $this->getAclFormManager($button->getForm())->createEntityFromForm(array());

		$formData = $button->getForm()->getValues();

		try{
			$this->roleAclRepository->addRecord($aclEntity, $formData['F_role'], $formData['F_rightResource']);

			$this->flashMessage("Acl přidáno",'alert-success');
			$this->redirect('list');
		}catch(\Kdyby\Doctrine\DuplicateEntryException $e){
			$this->flashMessage("Acl již existuje",'alert-danger');
		}
	}

	/**
	 * Inicializace formulare pro ACL
	 * @param string $name
	 * @return type
	 */
	protected function createComponentAclForm($name)
	{
		$builder = new FormBuilder($this->context, new Form($this, $name));
		$builder->createForm();

		return $builder->getForm();
	}

	/**
	 * Data grid vypisu acl
	 * @param type $name
	 * @return \Nette\Application\UI\Control
	 */
	protected function createComponentGroupAclGrid($name)
	{
		$builder = new Components\Acl\GridoBuilder($this->entityName, $this->entityManager, new \Grido\Grid($this, $name));
		$dg = $builder->createGrid();
		return $dg;
	}

	public function createComponentRemoveAclDialog($name)
	{
		$control = new Modal($this->translator, $this, $name);
		$control->setTitle('potvrzení odebrání ACL')
				->setContent('Opravdu chcete odebrat ACL ?')
				->setId('acl-remove-dialog');
		return $control;
	}

	/**
	 *
	 * @param Form $form
	 * @return EntityFormManager
	 */
	public function getAclFormManager($form)
	{
		return new EntityFormManager($form, $this->entityName, $this->entityManager);
	}

	/**
	 *
	 * @param type $target
	 * @return \Nette\Utils\Html
	 */
	protected function generateRemoveIcon($target)
	{
		$icon = Icon::createIcon(Icon::DELETE);
		$icon->addAttributes(array('data-toggle'=>"modal",
				'data-target'=>$target
			));
		return $icon;
	}
}

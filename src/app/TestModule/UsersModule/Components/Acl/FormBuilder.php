<?php

namespace TestModule\UsersModule\Components\Acl;

use JO\Nette\Application\UI\FormBootstrapRenderer;
use JO\Nette\Application\UI\FormFactory;
use JO\Nette\Doctrine\EntityFormManager;

/**
 * Description of FormBuilder
 *
 * @author Jan Oliva
 */
class FormBuilder extends FormFactory
{


	public function createForm()
	{
		$rscRepository = $this->context->getService('RightResourceRepository');
		/* @var $rscRepository \Entity\User\RightResourceRepository */
		$rightOtions = $rscRepository->getSelectOptions();

		$roleRepository = $this->context->getService('RoleRepository');
		/* @var $roleRepository \Entity\User\RoleRepository */

		$roleOptions = $roleRepository->getSelectOptions();
		$man = new EntityFormManager($this->form, '\Entity\User\RoleACL', $this->context->EntityManager);

		$man->createFields(array('id'));

		$resource = $this->form['F_rightResource'];
		/* @var $resource \Nette\Forms\Controls\SelectBox */
		$resource
				->setItems(array(''=>'-- Výběr oprávnění --') + $rightOtions)
				->setRequired("Vyber oprávnění");

		$role = $this->form['F_role'];
		/* @var $role \Nette\Forms\Controls\SelectBox */
		$role
				->setItems(array(''=>'-- Výběr role --') + $roleOptions)
				->setRequired("Vyber roli");

		$this->form['F_privilege']
				->setRequired("Vyplňte privilegium")
				->getControlPrototype()->title = "Např. 'list' nebo 'add' 'edit' ....";

		$this->form['F_allow']
				->getControlPrototype()->title = "povolení , nebo zákaz privilegia";

		$this->form->setRenderer(new FormBootstrapRenderer());

		return $this->getForm();
	}
}

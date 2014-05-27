<?php

namespace TestModule\UsersModule\Components\Roles;

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

	const SUBMIT_ADD = 'SUBMIT_ADD';
	const SUBMIT_EDIT = 'SUBMIT_EDIT';

	public function createForm()
	{
		$man = new EntityFormManager($this->form, '\Entity\User\Role', $this->context->EntityManager);

		$man->createFields(array('id','users'));

		$this->form['F_name']->setRequired("Vyplňte název role");
		$this->form->setRenderer(new FormBootstrapRenderer());
		return $this->getForm();
	}
}

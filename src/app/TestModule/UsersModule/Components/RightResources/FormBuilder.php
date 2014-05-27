<?php

namespace TestModule\UsersModule\Components\RightResources;

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
		$man = new EntityFormManager($this->form, '\Entity\User\RightResource', $this->context->EntityManager);

		$this->form->addGroup('zdroj oprávnění');
		$man->createFields(array('id'));

		$this->form['F_name']->setRequired("Vyplňte název oprávnění");
		$this->form->setRenderer(new FormBootstrapRenderer());
		return $this->getForm();
	}
}

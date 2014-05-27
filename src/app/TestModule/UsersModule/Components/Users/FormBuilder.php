<?php

namespace TestModule\UsersModule\Components;

use JO\Nette\Application\UI\FormBootstrapRenderer;
use JO\Nette\Application\UI\FormFactory;
use JO\Nette\Doctrine\EntityFormManager;
use Nette\Application\UI\Form;

/**
 * Description of FormBuilder
 *
 * @author Jan Oliva
 */
class FormBuilder extends FormFactory
{

	const SUBMIT_ADD = 'SUBMIT_ADD';
	const SUBMIT_EDIT = 'SUBMIT_EDIT';

	/**
	 *
	 * @param type $passRequired
	 * @return type
	 */
	public function createForm($equiredPassword)
	{
		$manP = new EntityFormManager($this->form, '\Entity\Person\Person', $this->context->EntityManager);

		$manP->createFields(array('id'));

		$manU = new EntityFormManager($this->form, '\Entity\User\User', $this->context->EntityManager);
		$manU->createFields(array('id', 'roles', 'person', 'pass','active'));

		$this->form['F_name']->setRequired("Vyplňte jméno");
		$this->form['F_email']->addCondition(Form::FILLED)->addRule(Form::EMAIL,"Vložte korektní email");
		$this->form['F_surname']->setRequired("Vyplňte příjmení");

		$this->form['F_login']->setRequired("Vyplňte přihlašovací jméno");
		$this->form['F_login']->getControlPrototype()->autocomplete = 'off';

		$this->form->addPassword('F_pass', 'heslo');
		$this->form["F_pass"]
					->addCondition(Form::FILLED)
					->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaků', 6);

		if ($equiredPassword === true) {

			$this->form["F_pass"]->setRequired("vyplňte heslo");

		}

		$this->form->addPassword('F_passRepeat', 'kontrola hesla')
					->addConditionOn($this->form["F_pass"], Form::FILLED)
					->addRule(Form::EQUAL, "heslo a kontrola hesla se neshodují", $this->form["F_pass"])
					->setRequired("vyplňte heslo pro kontrolu");

		$manU->createFields(array('active'),true);

		$this->form->setRenderer(new FormBootstrapRenderer());
		return $this->getForm();
	}

	public function createPartPassInsert()
	{
		$this->form->addPassword('F_pass', 'heslo');
			$this->form['F_login']->setRequired("Vyplňte přihlašovací jméno");
			$this->form["F_pass"]->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaků', 6)
					->setRequired("vyplňte heslo");

			$this->form->addPassword('F_passRepeat', 'kontrola hesla')
					->addConditionOn($this->form["F_pass"], Form::FILLED)
					->addRule(Form::EQUAL, "heslo a kontrola hesla se neshodují", $this->form["F_pass"])
					->setRequired("vyplňte heslo pro kontrolu");
	}

	public function createPartPassUpdate()
	{
		$this->form->addPassword('F_pass', 'heslo');
			$this->form['F_login']->setRequired("Vyplňte přihlašovací jméno");
			$this->form["F_pass"]
					->addCondition(Form::FILLED)
					->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaků', 6);


			$this->form->addPassword('F_passRepeat', 'kontrola hesla')
					->addConditionOn($this->form["F_pass"], Form::FILLED)
					->addRule(Form::EQUAL, "heslo a kontrola hesla se neshodují", $this->form["F_pass"])
					->setRequired("vyplňte heslo pro kontrolu");
	}
}

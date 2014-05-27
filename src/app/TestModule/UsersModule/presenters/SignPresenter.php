<?php

namespace TestModule\UsersModule;

use JO\Nette\Application\UI\Login\Login;
use Nette\Security\IAuthenticator;

/**
 * Description of SignPresenter
 * Prihlaseni uzivatele
 * @author Jan Oliva
 */
class SignPresenter extends \TestModule\ABasePresenter
{
	/**
	 * Prihlaseni
	 */
	public function actionIn()
	{
		//aktivace komponenty
		$form = $this->getComponent('login')->getComponent('loginForm');
		$this->getUser()->onLoggedIn[] = callback($this,'onLogin');
	}

	/**
	 * Odhlaseni
	 */
	public function actionOut()
	{
		$this->getUser()->onLoggedOut[] = callback($this,'onLogout');
		$this->getUser()->logout();
	}

	public function onLogout()
	{

	}

	public function onLogin()
	{
		$this->redirect('Users:list');
	}

	/**
	 * Neuspesne prihlaseni
	 */
	public function onInvalidLogin()
	{
		$this->flashMessage("Uživatel neexistuje", "alert-danger");
	}

	/**
	 * Kompoenta pro prihlasovani uzivatele
	 * @param name $name
	 * @return \JO\Nette\Application\UI\Login\Login
	 */
	public function createComponentLogin($name)
	{
		$login = new Login($this->getUser(),$this->translator, $this, $name);
		$login->setButtonLabel("přihlásit")
				->setTitle("přihlášení")
				->setLoginLabel("uživatelské jméno")
				->setRememberMeLabel("zapamatuj si mě")
				->setPasswordLabel("heslo");

		$login->onLoginFilure[] = callback($this,'onInvalidLogin');

		return $login;
	}
}

<?php

namespace TestModule;



/**
 * Description of AAclPresenter
 * Spolecny predek pro stranky se zabezpecenim pomoci ACL
 *
 * @author Jan Oliva
 */
abstract class AACLPresenter extends \ACLPresenter
{

	public function startup()
	{
		$this->loginLink = ":Test:Users:Sign:in";
		parent::startup();	//zavolani predka, ktery overuje ACL
	}

	/**
	 * komponenta horniho menu
	 * @param type $name
	 * @return Nette\Application\UI\Control
	 */
	public function createComponentTopMenu($name)
	{
		$factory = new Components\TopMenuFactory($this->context);
		return $factory->createInstance($this, $name);
	}

	/**
	 * Komponenta ukazatele stavu skype uctu
	 * @param string $name
	 * @return Nette\Application\UI\Control
	 */
	public function createComponentSkypeStatus($name)
	{
		$factory = new Components\SkypeStatusFactory($this->context);
		return $factory->createInstance($this, $name);
	}
}

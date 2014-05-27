<?php

namespace TestModule;

use JO\Nette\Application\UI\SbAdmin\Menu\SideMenu;
use JO\Nette\Application\UI\SbAdmin\Menu\TopMenu;
use TestModule\Components\Menu\YamlBuilder;

/**
 * Description of AAclPresenter
 * Spolecny predek pro nezabezpecene stranky
 * @author Jan Oliva
 */
abstract class ABasePresenter extends \BasePresenter
{
	/**
	 *
	 * @var type
	 */
	protected $translator;

	public function startup()
	{
		parent::startup();
		$this->getUser()->getStorage()->setNamespace('jobs');

	}

	public function injectTranslator(\Nette\Localization\ITranslator $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * Komponenta horniho menu
	 * @param string $name
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

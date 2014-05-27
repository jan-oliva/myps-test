<?php

namespace TestModule\Components;

/**
 * Description of TopMenuFactory
 *
 * @author Jan Oliva
 */
class TopMenuFactory
{
	protected $context;

	function __construct($context)
	{
		$this->context = $context;
	}


	/**
	 *
	 * @param type $parent
	 * @param string $name
	 * @return \JO\Nette\Application\UI\SbAdmin\Menu\TopMenu
	 */
	public function createInstance($parent, $name)
	{
		$control = new \JO\Nette\Application\UI\SbAdmin\Menu\TopMenu($this->context->getService('ITranslator'),$parent, $name);
		$control->setNavbarLabel('MYPS test projekt')	;
		return $control;
	}
}

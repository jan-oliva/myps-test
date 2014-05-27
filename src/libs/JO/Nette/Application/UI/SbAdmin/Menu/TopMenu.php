<?php

namespace JO\Nette\Application\UI\SbAdmin\Menu;

/**
 * Description of TopMenu
 *
 * @author Jan Oliva
 */
class TopMenu extends AMenu
{
	protected $navbarLabel;

	protected function initTemplate()
	{
		$this->templatePath = dirname(__FILE__)."/topMenu.latte";

		parent::initTemplate();

	}

	/**
	 *
	 * @param string $navbarLabel
	 * @return \JO\Nette\Application\UI\SbAdmin\Menu\TopMenu
	 */
	public function setNavbarLabel($navbarLabel)
	{
		$this->navbarLabel = $navbarLabel;
		return $this;
	}


	public function render()
	{
		$this->template->items = $this->getItems();
		$this->template->control = $this;
		$this->template->navbarLabel = $this->navbarLabel;
		return parent::render();
	}
}

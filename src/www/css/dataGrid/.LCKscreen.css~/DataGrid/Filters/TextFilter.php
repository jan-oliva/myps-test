<?php

namespace PM\DataGrid\Filters;
use Nette;
use Nette\Forms\Controls\TextInput ;
/**
 * Representation of data grid column textual filter.
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář (http://romansklenar.cz)
 * @license    New BSD License
 * @example    http://addons.nette.org/datagrid
 * @package    Nette\Extras\DataGrid
 */
class TextFilter extends ColumnFilter
{
	/**
	 * Returns filter's form element.
	 * @return Nette\Forms\FormControl
	 */
	public function getFormControl()
	{
		//if ($this->element instanceof Nette\Forms\FormControl) return $this->element;
		if ($this->element instanceof Nette\Forms\Controls\BaseControl) return $this->element;

		//$this->element = new Nette\Forms\TextInput($this->getName(), 5);
		$this->element = new TextInput($this->getName(), 5);
		return $this->element;
	}
}
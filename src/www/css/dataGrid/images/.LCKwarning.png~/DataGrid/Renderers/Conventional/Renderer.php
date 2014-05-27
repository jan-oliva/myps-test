<?php
namespace PM\DataGrid\Renderers\Conventional;

use Nette, PM\DataGrid,
	Nette\Utils\Html,
	PM\DataGrid\Columns,
	Nette\Iterators\CachingIterator as SmartCachingIterator,
	PM\DataGrid\Action,
	PM\DataGrid\Renderers\IRenderer;

/**
 * Popis třídy Renderer
 *
 * @author Jan Oliva <jan.oliva@clapix.com>
 */
class Renderer extends RendererAbstract
{


	/**
	 * Generates datagrid row content.
	 * @param  \Traversable|array data
	 * @return Nette\Web\Html
	 */
	protected function generateContentRow($data)
	{
		$form = $this->dataGrid->getForm(TRUE);
		$row = $this->getWrapper('row.content container');

		if ($this->dataGrid->hasOperations() || $this->dataGrid->hasActions()) {
			$primary = $this->dataGrid->keyName;
			if (!isset($data[$primary])) {
				throw new \InvalidArgumentException("Invalid name of key for group operations or actions. Column '" . $primary . "' does not exist in data source.");
			}
		}

		// checker
		if ($this->dataGrid->hasOperations()) {
			$value = $form['checker'][$data[$primary]]->getControl();
			$cell = $this->getWrapper('row.content cell container')->setHtml((string)$value);
			$cell->addClass('checker');
			$row->add($cell);
		}

		// content
		foreach ($this->dataGrid->getColumns() as $column) {
			$cell = $this->getWrapper('row.content cell container');
			$cell->attrs = $column->getCellPrototype()->attrs;

			if ($column instanceof Columns\ActionColumn) {
				$value = '';
				foreach ($this->dataGrid->getActions() as $action) {
					if (!is_callable($action->ifDisableCallback) || !callback($action->ifDisableCallback)->invokeArgs(array($data))) {
						$html = $action->getHtml();
						$html->title($this->dataGrid->translate($html->title));
						$action->parseDynamicLinkParamsData($data);
						$action->generateLink(array($primary => $data[$primary]));
						$this->onActionRender($html, $data);
						$value .= $html->render() . ' ';
					} else
						$value .= Html::el('span')->setText($this->dataGrid->translate($action->getHtml()->title))->render() . ' ';
				}
				$cell->addClass('actions');

			} else {
				if (!array_key_exists($column->getName(), $data)) {
					throw new \InvalidArgumentException("Non-existing column '" . $column->getName() . "' in datagrid '" . $this->dataGrid->getName() . "'");
				}
				$value = $column->formatContent($data[$column->getName()], $data);
			}

			$cell->setHtml((string)$value);
			$this->onCellRender($cell, $column->getName(), !($column instanceof Columns\ActionColumn) ? $data[$column->getName()] : $data);
			$row->add($cell);
		}
		unset($form, $primary, $cell, $value, $action);
		$this->onRowRender($row, $data);
		return $row;
	}
}


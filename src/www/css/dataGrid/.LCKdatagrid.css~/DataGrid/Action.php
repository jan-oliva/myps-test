<?php
namespace PM\DataGrid;
use Nette;
use Nette\ComponentModel\Component;
use Nette\Utils\Html;

/**
 * Popis třídy Action
 *
 * @author Jan Oliva <jan.oliva@clapix.com>
 */
class Action extends ActionAbstract
{
	protected $linkParams;
	protected $dynamicLinkParams;
	protected $actionLinkName = null;

	/**
	 * Prida do url parametr s nazvem $key a s kazdym radkem bude dynamicky doplena jeho odpovidajici hodota ze zobrazovanych dat.
	 *
	 * @param string $key
	 * @param string $dataKey
	 * @return DataGridActionProfimedia
	 */
	public function addDynamicLinkParam($key,$dataKey)
	{
		$this->dynamicLinkParams[$key] = $dataKey;
		return $this;
	}

	/**
	 * Prida do url parametr s nazvem $key a statuckou hodnotou $value (v kazdem radku stejna hodnota)
	 * @param string $key
	 * @param string $value
	 * @return DataGridActionProfimedia
	 */
	public function addLinkParam($key,$value)
	{
		$this->linkParams[$key] = $value;
		return $this;
	}


	/**
	 * Pod timto nazvem je url doplnen parametr $keyName s hodnotou $radekData[$dataGrid->keyName].
	 * @param type $keyName
	 * @return DataGridActionProfimedia
	 * @todo se vznikem metody addDynamicLinkParam() to mozna postrada smysl , protoze si muzu pridat libovolne pojmenovany parametr s libovolnou hodotou z radku dat.
	 *		Asi je to jeste k zamysleni.
	 */
	public function setRequestParamName($keyName)
	{
		$this->actionLinkName = $keyName;
		return $this;
	}

	/**
	 * uprava linku
	 * @param array $args - data zpracovavaneho radku dataGridu
	 */
	public function generateLink(array $args = NULL)
	{
		$dataGrid = $this->lookup('PM\DataGrid\DataGrid', TRUE);
		$control = $dataGrid->lookup('Nette\Application\UI\Control', TRUE);

		switch ($this->key) {
		case self::WITHOUT_KEY:
			$link = $control->link($this->destination); break;
		case self::WITH_KEY:
		default:
			$key = $this->key == NULL || is_bool($this->key) ? $dataGrid->keyName : $this->key;

			if(empty($this->linkParams) && empty($this->dynamicLinkParams) && empty($this->actionLinkName))
			{
				$link = $control->link($this->destination, array($key => $args[$key])); break;
			}
			else
			{
				$linkArgs = array($key => $args[$dataGrid->keyName]);
				$linkArgs = array_merge($linkArgs,$this->linkParams);

				if(!empty($this->actionLinkName))
				{
					$linkArgs[$this->actionLinkName] = $args[$dataGrid->keyName];
					unset($linkArgs[$dataGrid->keyName]);
				}
				$link = $control->link($this->destination, $linkArgs); break;
			}
		}
		$this->html->href($link);
	}
	
	/**
	 * Doplneni dat do dynamickych url parametru action.
	 * @param type $data - data jednoho radku data gridu
	 */
	public function parseDynamicLinkParamsData($data)
	{
		if(!empty($this->dynamicLinkParams))
		{
			foreach ($this->dynamicLinkParams as $key=>$dataKey)
			{
				$this->linkParams[$key] = $data[$dataKey];
			}
		}
	}
}


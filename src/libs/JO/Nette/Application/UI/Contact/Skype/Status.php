<?php

namespace JO\Nette\Application\UI\Contact\Skype;

use Nette\Localization\ITranslator;

/**
 * Description of Status
 * Component show status icon of skype account
 *
 * @author Jan Oliva
 */
class Status extends \JO\Nette\Application\UI\AComponent
{
	protected $account;
	protected $accountLabel;
	protected $url = 'http://mystatus.skype.com';

	/**
	 *
	 * @param \Nette\Localization\ITranslator $translator
	 * @param type $parent
	 * @param string $name
	 * @param string $account
	 */
	function __construct(ITranslator $translator, $parent = null, $name = null,$account)
	{
		parent::__construct($translator, $parent, $name);

		$this->account = $account;
	}

	protected function initTemplate()
	{
		$this->templatePath = dirname(__FILE__)."/status.latte";

		parent::initTemplate();
	}

	public function setAccountLabel($accountLabel)
	{
		$this->accountLabel = $accountLabel;
		return $this;
	}

	public function render()
	{
		$this->template->account = $this->account;
		$this->template->accountLabel = $this->accountLabel;
		$this->template->url = $this->url;
		$this->template->render();
	}
}

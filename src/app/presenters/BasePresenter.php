<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	public function startup()
	{
		parent::startup();
		$this->template->setTranslator($this->context->getService('ITranslator'));
		if($this->isAjax()){
			$this->redrawControl('flashMessages');
		}
	}

}

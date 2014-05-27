<?php

use Doctrine\ORM\EntityManager;
use JO\Nette\Doctrine\EntityFormManager;
use Nette\Diagnostics\Debugger;
use Nette\Forms\Form;
use Nette\Localization\ITranslator;


/**
 * Description of ACLPresenter
 * Rodic pro presentery, ktere budou pouzivat ACL autorizaci
 *
 * @author Jan Oliva
 */
abstract class ACLPresenter extends BasePresenter
{
	/**
	 *
	 * @var string
	 */
	protected $loginLink = "Sign:in	";


	/**
	 *
	 * @var EntityManager
	 */
	protected $entityManager;
	/**
	 *
	 * @var \Nette\Diagnostics\Logger
	 */
	protected $syslogger;

	protected $translator;

	public function startup()
	{
		parent::startup();
		$this->getUser()->getStorage()->setNamespace('jobs');

		$this->checkAcl();
	}

	/**
	 * Overeni ACL na url pozadavek
	 */
	protected function checkAcl()
	{
		$resource = $this->getName();
		$privilege = $this->getAction();

		if($this->getUser()->isAllowed($resource, $privilege) === false || $this->getUser()->isLoggedIn() === false){
			//throw new \Nette\Application\BadRequestException("access denied",403);
			$userInfo = ($this->getUser()->isLoggedIn()) ? "user id '{$this->getUser()->getId()}'" : "not logged user";

			Debugger::log("Acess denied for '{$userInfo}'", Debugger::WARNING);

			$this->redirect($this->loginLink);

		}

	}
	public function injectSyslogger(\Nette\Diagnostics\Logger $logger)
	{
		$this->syslogger = $logger;
	}

	public function injectEntityManager(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function injectTranslator(ITranslator $translator)
	{
		$this->translator = $translator;
	}

	/**
	 *
	 * @param Form $form
	 * @param string $entitytClassName
	 * @return EntityFormManager
	 */
	protected function getFormManager($form,$entitytClassName,$prefix=  EntityFormManager::FORM_FIELD_PREFIX)
	{
		return new EntityFormManager($form, $entitytClassName, $this->entityManager,$prefix);
	}
}

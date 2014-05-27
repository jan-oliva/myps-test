<?php

namespace TestModule\Components;

use Symfony\Component\Yaml\Parser;

/**
 * Description of SkypeStatusFactory
 * Create Skype status
 * @author Jan Oliva
 */
class SkypeStatusFactory
{
	protected $context;

	function __construct($context)
	{
		$this->context = $context;
	}

	/**
	 * Load configu and create instance
	 *
	 * %appPAth%/TestModule/config/contact.yml
	 *
	 * @param type $parent
	 * @param string $name
	 * @return \JO\Nette\Application\UI\Conact\Skype\Status
	 */
	public function createInstance($parent, $name)
	{
		//%appPAth%/TestModule/config/contact.yml
		$file = __DIR__."/../config/contact.yml";

		$parser = new Parser();
		$data = $parser->parse(file_get_contents($file), true);
		$account = $data['skype']['account'];

		$control = new \JO\Nette\Application\UI\Contact\Skype\Status($this->context->getService('ITranslator'),$parent, $name,$account);
		$control->setAccountLabel($data['skype']['label']);
		return $control;
	}
}

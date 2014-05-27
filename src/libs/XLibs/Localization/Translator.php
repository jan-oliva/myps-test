<?php

namespace XLibs\Localization;

use Nette\Localization\ITranslator;

/**
 * Description of Translator
 *
 * @author Jan Oliva
 */
class Translator implements ITranslator
{
	function __construct()
	{

	}

	function translate($message, $count = NULL)
	{
		//\Nette\Diagnostics\Debugger::barDump($message,__METHOD__);
		return $message;
	}
}

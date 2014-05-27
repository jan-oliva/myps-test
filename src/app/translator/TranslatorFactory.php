<?php

use XLibs\Localization\Translator;



/**
 * Description of TranslatorFactory
 *
 * @author Jan Oliva
 */
class TranslatorFactory
{
	/**
	 *
	 * @return \XLibs\Localization\Translator
	 */
	public function createInstance()
	{
		return new Translator();
	}
}

<?php

/**
 * Test: Nette\Templating\Helpers::nl2br()
 */

use Nette\Templating\Helpers,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$input = "Hello\nmy\r\nfriend\n\r";

Nette\Utils\Html::$xhtml = TRUE;
Assert::same( "Hello<br />\nmy<br />\r\nfriend<br />\n\r", Helpers::nl2br($input) );

Nette\Utils\Html::$xhtml = FALSE;
Assert::same( "Hello<br>\nmy<br>\r\nfriend<br>\n\r", Helpers::nl2br($input) );

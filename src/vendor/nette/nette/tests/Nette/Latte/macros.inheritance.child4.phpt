<?php

/**
 * Test: Nette\Latte\Engine: {extends ...} test IV.
 */

use Nette\Latte,
	Nette\Templating\FileTemplate,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$template = new FileTemplate;
$template->setFile(__DIR__ . '/templates/inheritance.child4.latte');
$template->registerFilter(new Latte\Engine);

Assert::match(<<<EOD
	Content
EOD
, $template->__toString(TRUE));

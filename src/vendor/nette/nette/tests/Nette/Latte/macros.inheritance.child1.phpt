<?php

/**
 * Test: Nette\Latte\Engine: {extends ...} test I.
 */

use Nette\Latte,
	Nette\Templating\FileTemplate,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';

require __DIR__ . '/Template.inc';


$template = new FileTemplate(__DIR__ . '/templates/inheritance.child1.latte');
$template->setCacheStorage($cache = new MockCacheStorage);
$template->registerFilter(new Latte\Engine);

$template->people = array('John', 'Mary', 'Paul');

$path = __DIR__ . '/expected/' . basename(__FILE__, '.phpt');
Assert::matchFile("$path.child.phtml", $template->compile());
Assert::matchFile("$path.html", $template->__toString(TRUE));
Assert::matchFile("$path.parent.phtml", $cache->phtml['inheritance.parent.latte']);

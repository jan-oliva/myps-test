<?php

/**
 * Test: Nette\Templating\Helpers::bytes()
 */

use Nette\Templating\Helpers,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( "0 B", Helpers::bytes(0.1), "TemplateHelpers::bytes(0.1)" );


Assert::same( "-1.03 GB", Helpers::bytes(-1024 * 1024 * 1050), "TemplateHelpers::bytes(-1024 * 1024 * 1050)" );


Assert::same( "8881.78 PB", Helpers::bytes(1e19), "TemplateHelpers::bytes(1e19)" );

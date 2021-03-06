<?php

/**
 * Test: Nette\Diagnostics\Dumper::toText() depth & truncate
 */

use Nette\Diagnostics\Dumper,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = array(
	'long' => str_repeat('Nette Framework', 1000),

	array(
		array(
			array('hello' => 'world'),
		),
	),

	'long2' => str_repeat('Nette Framework', 1000),

	(object) array(
		(object) array(
			(object) array('hello' => 'world'),
		),
	),
);
$arr[] = &$arr;


Assert::match( 'array (5)
   long => "Nette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette Framework ... " (15000)
   0 => array (1)
   |  0 => array (1)
   |  |  0 => array (1)
   |  |  |  hello => "world" (5)
   long2 => "Nette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette Framework ... " (15000)
   1 => stdClass #%h%
   |  0 => stdClass #%h%
   |  |  0 => stdClass #%h%
   |  |  |  hello => "world" (5)
   2 => array (5)
   |  long => "Nette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette Framework ... " (15000)
   |  0 => array (1)
   |  |  0 => array (1)
   |  |  |  0 => array (1) [ ... ]
   |  long2 => "Nette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette FrameworkNette Framework ... " (15000)
   |  1 => stdClass #%h%
   |  |  0 => stdClass #%h%
   |  |  |  0 => stdClass #%h% { ... }
   |  2 => array (5) [ RECURSION ]
', Dumper::toText($arr) );


Assert::match( 'array (5)
   long => "Nette FrameworkNette FrameworkNette FrameworkNette ... " (15000)
   0 => array (1)
   |  0 => array (1) [ ... ]
   long2 => "Nette FrameworkNette FrameworkNette FrameworkNette ... " (15000)
   1 => stdClass #%h%
   |  0 => stdClass #%h% { ... }
   2 => array (5)
   |  long => "Nette FrameworkNette FrameworkNette FrameworkNette ... " (15000)
   |  0 => array (1) [ ... ]
   |  long2 => "Nette FrameworkNette FrameworkNette FrameworkNette ... " (15000)
   |  1 => stdClass #%h% { ... }
   |  2 => array (5) [ RECURSION ]
', Dumper::toText($arr, array(Dumper::DEPTH => 2, Dumper::TRUNCATE => 50)) );

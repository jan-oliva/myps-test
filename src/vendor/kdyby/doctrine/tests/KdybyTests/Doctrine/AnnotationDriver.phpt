<?php

/**
 * Test: Kdyby\Doctrine\Mapping\AnnotationDriver.
 *
 * @testCase Kdyby\Doctrine\AnnotationDriverTest
 * @author Filip Procházka <filip@prochazka.su>
 * @package Kdyby\Doctrine
 */

namespace KdybyTests\Doctrine;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Kdyby;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class AnnotationDriverTest extends Tester\TestCase
{

	protected function setUp()
	{
		AnnotationRegistry::registerLoader('class_exists');
	}



	public function testGetAllClassNames()
	{
		$driver = new Kdyby\Doctrine\Mapping\AnnotationDriver(array(
			__DIR__ . '/models/AnnotationDriver/App/*Entity.php',
			__DIR__ . '/models/AnnotationDriver/Something',
		), new AnnotationReader());

		Assert::same(array(
			'KdybyTests\Doctrine\AnnotationDriver\App\FooEntity',
			'KdybyTests\Doctrine\AnnotationDriver\Something\Baz'
		), $driver->getAllClassNames());

		Assert::true(!in_array('KdybyTests\Doctrine\AnnotationDriver\App\Bar', $driver->getAllClassNames()));
	}

}

\run(new AnnotationDriverTest());

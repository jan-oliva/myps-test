<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace KdybyTests\Doctrine;

use Doctrine\ORM\Tools\SchemaTool;
use Kdyby;
use Nette;
use Nette\PhpGenerator as Code;
use Tester;



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
abstract class ORMTestCase extends Tester\TestCase
{

	/**
	 * @var \Nette\DI\Container|\SystemContainer
	 */
	protected $serviceLocator;



	/**
	 * @return Kdyby\Doctrine\EntityManager
	 */
	protected function createMemoryManager()
	{
		$rootDir = __DIR__ . '/../../';

		$config = new Nette\Configurator();
		$container = $config->setTempDirectory(TEMP_DIR)
			->addConfig(__DIR__ . '/../nette-reset.neon')
			->addConfig(__DIR__ . '/config/memory.neon')
			->addParameters(array(
				'appDir' => $rootDir,
				'wwwDir' => $rootDir,
			))
			->createContainer();
		/** @var Nette\DI\Container $container */

		$em = $container->getByType('Kdyby\Doctrine\EntityManager');
		/** @var Kdyby\Doctrine\EntityManager $em */

		$schemaTool = new SchemaTool($em);
		$schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());

		$this->serviceLocator = $container;

		return $em;
	}



	/**
	 * @param string $className
	 * @param array $props
	 * @return object
	 */
	protected function newInstance($className, $props = array())
	{
		return Code\Helpers::createObject($className, $props);
	}

}

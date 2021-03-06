<?php

// Load Nette Framework or autoloader generated by Composer

error_reporting(-1);

require __DIR__ . '/../vendor/autoload.php';


$configurator = new \Nette\Config\Configurator;


// Enable Nette Debugger for error visualisation & logging
$configurator->setDebugMode(TRUE);
$configurator->setDebugMode($configurator->detectDebugMode(array("10.99.0.1")));
$configurator->enableDebugger();
$configurator->enableDebugger(__DIR__ . '/../log');

// Specify folder for cache
$configurator->setTempDirectory(__DIR__ . '/../temp');

// Enable RobotLoader - this will load all classes automatically
$configurator->createRobotLoader()
		->addDirectory(__DIR__)
		->addDirectory(__DIR__ . '/../libs')
		->register();


// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon', $configurator::NONE); // none section


Kdyby\Annotations\DI\AnnotationsExtension::register($configurator);
Kdyby\Console\DI\ConsoleExtension::register($configurator);
Kdyby\Events\DI\EventsExtension::register($configurator);
Kdyby\Doctrine\DI\OrmExtension::register($configurator);

$container = $configurator->createContainer();

return $container;

<?php
use Nette\Diagnostics\Debugger,
Nette\Application\Routers\Route;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;

// Load Nette Framework
$params['libsDir'] = __DIR__ . '/../libs';
$params['appDir'] = realpath(__DIR__ . '/../app');
require $params['libsDir'] . '/Nette/loader.php';
require_once $params['libsDir'] . '/Doctrine/Common/ClassLoader.php';

$configurator = new Nette\Configurator('\Nette\DI\Container');

$configurator->container->params += $params;

$configurator->container->params['tempDir'] = __DIR__ . '/../temp';

$container = $configurator->loadConfig(__DIR__ . '/../app/config.neon');
//doctrine
$loader = new Doctrine\Common\ClassLoader('Doctrine');
$loader->register();

$config = new Doctrine\ORM\Configuration;

$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(array($configurator->container->params['doctrine']['entityDir'])));

$config->setProxyDir($configurator->container->params['doctrine']['proxyDir']);

$config->setProxyNamespace('Proxies');

$configurator->getContainer()->addService('DoctrineLogger',new Doctrine\DBAL\Logging\DebugStack);
$config->setSQLLogger($configurator->getContainer()->getService('DoctrineLogger'));
$em = Doctrine\ORM\EntityManager::create($configurator->container->params['doctrine']['connection'], $config);
$em->getConnection()->query('SET NAMES utf8');
$em->getConnection()->query('SET CHARACTER SET utf8');

$configurator->getContainer()->addService('DoctrineEM',$em);

?>

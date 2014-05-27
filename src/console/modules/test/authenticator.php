<?php

use Doctrine\ORM\EntityManager;
use Entity\User\UserRepository;
use JO\Security\Authenticator\Credentials;

require_once dirname(__FILE__) . '/../../bootstrap.php';

$em = $container->EntityManager;
/* @var $em EntityManager */

$repo = $em->getRepository('\Entity\User\User');
/* @var $repo UserRepository */
$ret =  $repo->authenticate(new Credentials('admin','neco'));
var_dump($ret);
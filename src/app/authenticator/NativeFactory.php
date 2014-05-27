<?php

namespace Authenticator;

use Entity\User\UserRepository;
use JO\Nette\Security\Authenticator\Generic;
use JO\Security\Authenticator\IPasswordGenerator;

/**
 * Description of NativeFactory
 *
 * @author Jan Oliva
 */
class NativeModelFactory
{
	public static function createInstance(UserRepository $repository,  IPasswordGenerator $passwordGenerator)
	{
		$auth = new Generic($passwordGenerator, $repository);
		return $auth;
	}
}

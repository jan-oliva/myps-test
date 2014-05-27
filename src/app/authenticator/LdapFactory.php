<?php

namespace Authenticator;

/**
 * Description of LdapFactory
 * Factory for LDAP authenticator
 *
 * @author Jan Oliva
 */
class LdapFactory
{

	public static function createInstance(IPasswordGenerator $passwordGenerator)
	{
		//@todo - ldap model neni
		$model = new \JO\Security\Authenticator\model\Ldap();
		$auth = new Generic($passwordGenerator, $model);
		return $auth;
	}
}

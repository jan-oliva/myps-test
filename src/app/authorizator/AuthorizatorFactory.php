<?php

namespace Authorizator;

use Entity\User\RightResource;
use Entity\User\RightResourceRepository;
use Entity\User\Role;
use Entity\User\RoleACLRepository;
use Entity\User\RoleRepository;
use Nette\Diagnostics\Debugger;
use Nette\Diagnostics\Dumper;
use Nette\Security\Permission;

/**
 * Description of AuthorizatorFactory
 *
 * @author Jan Oliva
 * @todo zakesovat role,resources a acl per user
 */
class AuthorizatorFactory
{

	/**
	 *
	 * @var RoleACLRepository
	 */
	protected $roleACLRepository;

	/**
	 *
	 * @var RightResourceRepository
	 */
	protected $resourceRepository;

	/**
	 *
	 * @var RoleRepository
	 */
	protected $roleRepository;

	/**
	 *
	 * @param \Entity\User\RoleACLRepository $roleACLRepository
	 * @param \Entity\User\RoleRepository $roleRepository
	 * @param \Entity\User\RightResourceRepository $rightRepos
	 */
	function __construct(RoleACLRepository $roleACLRepository, RoleRepository $roleRepository, RightResourceRepository $rightRepos)
	{
		$this->roleACLRepository = $roleACLRepository;
		$this->roleRepository = $roleRepository;
		$this->resourceRepository = $rightRepos;
	}

	/**
	 * VYtvoreni instance permission
	 * @return \Nette\Security\Permission
	 */
	public function createInstance()
	{
		$perm = new Permission();

//		$roles = $this->roleRepository->findAll();
//
//		//registrace roli
//		foreach ($roles as $role){
//			/* @var $role Role */
//			$perm->addRole($role->getName());
//		}

		$this->loadRoles($perm);
		$resources = $this->resourceRepository->findAll();

		//registrace reources
		foreach ($resources as $resource){
			/* @var $resource RightResource */
			$perm->addResource($resource->getName());
		}

		$groupAcl = $this->roleACLRepository->findAll();

		//allow/deny dle ACL
		foreach($groupAcl as $acl){
			/* @var $acl \Entity\User\RoleACL */
			$method = 'deny';
			if($acl->getAllow()){
				$method = 'allow';
			}

			$resource = $acl->getRightResource()->getName() == '*' ? Permission::ALL : $acl->getRightResource()->getName();
			$privilege = $acl->getPrivilege() == '*' ? Permission::ALL: $acl->getPrivilege() ;

			call_user_func(array($perm,$method),$acl->getRole()->getName(),$resource,$privilege);
		}
		//$perm->allow('admin', Permission::ALL);
		return $perm;
	}

	protected function loadRoles(Permission $perm)
	{
		$roles = $this->roleRepository->findAll();
		$perm->addRole('guest');
		//registrace roli
		foreach ($roles as $role){
			/* @var $role Role */
			$perm->addRole($role->getName());
		}
	}
}

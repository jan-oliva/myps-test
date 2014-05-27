<?php

namespace Entity\User;

use JO\Security\Authenticator\ICredentials;
use JO\Security\Authenticator\IPasswordGenerator;
use Kdyby\Doctrine\EntityDao;
use Nette\Utils\Strings;
use PM\DataGrid\DataSources\Doctrine\QueryBuilder;

/**
 * Description of UserRepository
 *
 * @author Jan Oliva
 */
class UserRepository extends EntityDao implements IUserRepository,  \JO\Security\Authenticator\model\IModel
{
	/**
	 *
	 * @var IPasswordGenerator
	 */
	protected $passwordGenerator;


	public function setPasswordGenerator(IPasswordGenerator $passwordGenerator)
	{
		$this->passwordGenerator = $passwordGenerator;
		return $this;
	}

	public function getGridoDataSource()
	{
		$qb = $this->createQueryBuilder('usr')
				->addSelect('person')
				->join('usr.person', 'person');

		$ds = new \Grido\DataSources\Doctrine($qb,array(
			'name'=>'person.name',
			'surname'=>'person.surname'
		));

		return $ds;
	}

	public function insert(User $entity)
	{
		$ret =  $this->save($entity);
		$salt = $entity->getId();
		$salt = is_null($salt) ? Strings::random(10) : $salt  ;

		$entity->setPass($this->passwordGenerator->crypt($entity->getPass(), $salt));
		$this->getEntityManager()->persist($entity);
		$this->getEntityManager()->flush();

		return $ret;
	}

	/**
	 *
	 * @param \Entity\User\User $entity
	 * @param type $person
	 * @param bool $changePass
	 */
	public function update(User $entity,$person,$changePass,$pass)
	{
		if($changePass){
			$entity->setPass($this->passwordGenerator->crypt($pass, $entity->getId()));
		}
		$this->save($entity, $person);
	}
	/**
	 * Autenticates user account
	 * @param \JO\Security\Authenticator\ICredentials $credentials
	 */
	public function authenticate(ICredentials $credentials)
	{
		$res = $this->findBy(array('login'=>$credentials->getUsername()));

		if(empty($res)){
			throw new \Nette\Security\AuthenticationException('Uživatel nenalezen', \Nette\Security\IAuthenticator::IDENTITY_NOT_FOUND);
		}
		foreach($res as $entity){
			/* @var $entity User */
			if($entity->getActive() === false){
				continue;
			}

			if($this->passwordGenerator->crypt($credentials->getPassword(), $entity->getId()) === $entity->getPass()){

				$roles = array();
				foreach ($entity->getRoles()->getIterator() as $role){
					$roles[$role->getId()] = $role->getName();
				}
				$data = array();
				$data['name'] = $entity->getPerson()->getName();
				$data['surname'] = $entity->getPerson()->getSurname();
				$data['login'] = $entity->getLogin();
				$identity = new \Nette\Security\Identity($entity->getId(),$roles,$data);

				return $identity;
			}
		}
		throw new \Nette\Security\AuthenticationException('Uživatel nenalezen', \Nette\Security\IAuthenticator::IDENTITY_NOT_FOUND);
	}

	public function getRoleIDs($userID)
	{
		$user = $this->find($userID);
		/* @var $user User */
		$roleIDs = $user->getRoles()->map(function(Role $role){
			return  $role->getId();
		});

		//\Nette\Diagnostics\Debugger::barDump($roleIDs);
		return $roleIDs;
	}

	public function removeRole($userID,$roleID)
	{
		$user = $this->find($userID);
		/* @var $user User */

		$role = $this->getRole($roleID);
		/* @var $role Role */

		$user->getRoles()->getIterator();
		$user->removeRole($role);

		$this->getEntityManager()->flush();

	}

	public function addRole($userID,$roleID)
	{
		$user = $this->find($userID);
		/* @var $user User */

		$role = $this->getRole($roleID);
		/* @var $role Role */
		$user->addRole($role);
		$this->getEntityManager()->flush();
	}

	/**
	 *
	 * @param type $filterActive
	 * @return array - options for select box
	 */
	public function getSelectOptions($filterActive=null)
	{
		$qb = $this->createQueryBuilder('usr')
				->addSelect('person')
				->join('usr.person', 'person');

		$ret = array ();

		foreach ($qb->getQuery()->execute() as $item){
			/* @var $item User */
			$ret[$item->getId()] = $item->getPerson()->getName()." ".$item->getPerson()->getSurname()." (".$item->getLogin().")";
		}
		return $ret;
	}

	/**
	 *
	 * @param int
	 * @retur Role
	 */
	private function getRole($roleID)
	{
		return $this->getEntityManager()->find('\Entity\User\Role', $roleID);
	}
}

<?php

namespace Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 * @ORM\Entity(repositoryClass="\Entity\User\UserRepository")
 *
 * @author Jan Oliva
 */
class User extends \Entity\BaseEntity
{
	/**
	 * #formLabel="přihlašovací jméno"
	 * @ORM\Column(type="string", unique=true,length=32)
	 */
	protected $login;

	/**
	 * #formLabel="heslo"
	 * @ORM\Column(type="string")
	 */
	protected $pass;

	/**
	 * #formLabel="aktivní"
	 * @ORM\Column(type="boolean")
	 */
	protected $active;

	/**
	 * @ORM\OneToOne(targetEntity="\Entity\Person\Person",cascade={"persist"},fetch="EAGER")
	 *
	 */
	protected $person;

	public function __construct()
	{
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

	/**
	 *
	 * @ORM\ManyToMany(targetEntity="\Entity\User\Role",inversedBy="users",fetch="LAZY")
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 */
	protected $roles;

	public function getId()
	{
		return $this->id;
	}

	public function getPass()
	{
		return $this->pass;
	}

	public function setPass($pass)
	{
		$this->pass = $pass;
		return $this;
	}

	/**
	 *
	 * @param \Entity\User\Role $role
	 */
	public function addRole(Role $role)
	{
		$role->getUsers()->add($this);
		$this->roles[$role->getId()] = $role;
		return $this;
	}

	/**
	 *
	 * @param \Entity\User\Role $role
	 * @return \Entity\User\User
	 */
	public function removeRole(Role $role)
	{
		$this->roles->removeElement($role);
		$role->getUsers()->removeElement($this);
		return $this;
	}

	public function setRoles($roles)
	{
		array_map(array($this,'addRole'), $roles);
		return $this;
	}

	/**
	 *
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getRoles()
	{
		return $this->roles;
	}

	public function getActive()
	{
		return $this->active;
	}

	/**
	 *
	 * @param bool $bool
	 */
	public function setActive($bool)
	{
		$this->active = $bool;
		return $this;
	}

	/**
	 *
	 * @return type
	 */
	public function getPerson()
	{
		return $this->person;
	}

	public function setPerson($person)
	{
		$this->person = $person;
		return $this;
	}

	public function getLogin()
	{
		return $this->login;
	}

	public function setLogin($login)
	{
		$this->login = $login;
		return $this;
	}



}

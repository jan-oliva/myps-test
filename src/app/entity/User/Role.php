<?php

namespace Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`userRole`")
 * @ORM\Entity(repositoryClass="\Entity\User\RoleRepository")
 * @author Jan Oliva
 */
class Role extends \Kdyby\Doctrine\Entities\BaseEntity
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 *
	 * @var integer
	 */
	protected $id;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * #formLabel="název role uživatele"
	 * @ORM\Column(type="string", length=100)
	 */
	protected $name;

	/**
	 * #formLabel="popis role uživatele"
	 * @ORM\Column(type="string", length=255)
	 */
	protected $label;

	/**
	 * #formLabel="výchozí stránka"
	 * @ORM\Column(type="string",nullable=true, length=100)
	 */
	protected $homePage;

	/**
	 *
	 *
	 * @ORM\ManyToMany(targetEntity="User",mappedBy="roles",fetch="EXTRA_LAZY")
	 * @var \Doctrine\Common\Collections\ArrayCollection Description
	 */
	 protected $users;

	 public function __construct()
	 {
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
	 }

	 /**
	  *
	  * @return \Doctrine\Common\Collections\ArrayCollection
	  */
	 public function getUsers()
	 {
		 return $this->users;
	 }

	 public function addUser(User $user)
	 {
		 $user->getRoles()->add($this);
		 $this->users[$user->getId()] = $user;
		 return $this;
	 }

	 public function setUsers(array $users)
	 {
		 array_map(array($this,'addUser'), $users);
		 return $this;
	 }

	 public function removeUser(User $user)
	 {
		 $this->users->removeElement($user);
		 $user->getRoles()->removeElement($this->getId());
	 }

	 public function getName()
	 {
		 return $this->name;
	 }

	 public function getLabel()
	 {
		 return $this->label;
	 }

	 public function setName($name)
	 {
		 $this->name = $name;
		 return $this;
	 }

	 public function setLabel($label)
	 {
		 $this->label = $label;
		 return $this;
	 }

	 public function getHomePage()
	 {
		 return $this->homePage;
	 }

	 public function setHomePage($homePage)
	 {
		 $this->homePage = $homePage;
		 return $this;
	 }


}

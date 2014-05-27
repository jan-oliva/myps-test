<?php

namespace Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`userRoleAcl`",uniqueConstraints={@ORM\UniqueConstraint(name="resource_privilege_role", columns={"userRole_id", "resource_id","privilege"})})
 * @ORM\Entity(repositoryClass="\Entity\User\RoleACLRepository")
 *
 * @author Jan Oliva
 */
class RoleACL extends \Entity\BaseEntity
{


	/**
	 * #formLabel="privilegium"
	 * @ORM\Column(type="string", length=255)
	 */
	protected $privilege;

	/**
	 * #formLabel="povoleni / zákaz privilegia"
	 *
	 * @ORM\Column(type="boolean")
	 */
	protected $allow;

	/**
	 * #formLabel="role"
	 * @ORM\ManyToOne(targetEntity="\Entity\User\Role")
	 * @ORM\JoinColumn(name="userRole_id", referencedColumnName="id",nullable=false)
	 */
	protected $role;

	/**
	 * #formLabel="oprávnění"
	 * @ORM\ManyToOne(targetEntity="\Entity\User\RightResource")
	 * @ORM\JoinColumn(name="resource_id", referencedColumnName="id",nullable=false)
	 */
	protected $rightResource;

	/**
	 *
	 * @return string
	 */
	public function getPrivilege()
	{
		return $this->privilege;
	}

	/**
	 *
	 * @return bool
	 */
	public function getAllow()
	{
		return $this->allow;
	}

	/**
	 *
	 * @return Role
	 */
	public function getRole()
	{
		return $this->role;
	}

	/**
	 *
	 * @return RightResource
	 */
	public function getRightResource()
	{
		return $this->rightResource;
	}

	/**
	 *
	 * @param string $privilege
	 * @return \Entity\User\RoleACL
	 */
	public function setPrivilege($privilege)
	{
		$this->privilege = $privilege;
		return $this;
	}

	/**
	 *
	 * @param bool $allow
	 * @return \Entity\User\RoleACL
	 */
	public function setAllow($allow)
	{
		$this->allow = $allow;
		return $this;
	}

	/**
	 *
	 * @param Role $role
	 * @return \Entity\User\RoleACL
	 */
	public function setRole($role)
	{
		$this->role = $role;
		return $this;
	}

	/**
	 *
	 * @param RightResource $rightResource
	 * @return \Entity\User\RoleACL
	 */
	public function setRightResource($rightResource)
	{
		$this->rightResource = $rightResource;
		return $this;
	}


}

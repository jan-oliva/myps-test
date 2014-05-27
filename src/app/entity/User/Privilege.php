<?php

namespace Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="userPrivilege")
 * @author Jan Oliva
 */
class Privilege extends \Entity\BaseEntity
{
	/**
	 *
	 *  @ORM\Column(type="string",unique=true,length=100)
	 */
	protected $name;

	/**
	 *
	 *  @ORM\Column(type="string",length=255)
	 */
	protected $label;
}

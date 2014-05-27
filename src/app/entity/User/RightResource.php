<?php

namespace Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="userRightResource")
 * @ORM\Entity(repositoryClass="\Entity\User\RightResourceRepository")
 * @author Jan Oliva
 */
class RightResource extends \Entity\BaseEntity
{
	/**
	 * #formLabel="název"
	 *  @ORM\Column(type="string", length=100,unique=true)
	 */
	protected $name;

	/**
	 * #formLabel="popis"
	 *  @ORM\Column(type="string", length=255)
	 */
	protected $label;

	/**
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 *
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 *
	 * @param string $name
	 * @return \Entity\User\RightResource
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 *
	 * @param string $label
	 * @return \Entity\User\RightResource
	 */
	public function setLabel($label)
	{
		$this->label = $label;
		return $this;
	}


}

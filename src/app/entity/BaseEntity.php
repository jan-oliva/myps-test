<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * 
 * @ORM\MappedSuperclass()
 * @author Jan Oliva
 */
abstract class BaseEntity extends \Kdyby\Doctrine\Entities\BaseEntity
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
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




}

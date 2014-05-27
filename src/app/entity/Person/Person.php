<?php

namespace Entity\Person;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`person`")
 * @author Jan Oliva
 */
class Person extends \Entity\BaseEntity
{
	/**
	 * #formLabel="jméno"
	 * @ORM\Column(type="string", length=100)
	 */
	protected $name;

	/**
	 * #formLabel="příjmení"
	 * @ORM\Column(type="string", length=100)
	 */
	protected $surname;

	/**
	 * #formLabel="email"
	 * @ORM\Column(type="string", length=100,nullable=true)
	 */
	protected $email;

	/**
	 * #formLabel="telefon"
	 * @ORM\Column(type="string", length=100,nullable=true)
	 */
	protected $phone;

	/**
	 * #formLabel="mobilní telefon"
	 * @ORM\Column(type="string", length=100,nullable=true)
	 */
	protected $mobilPhone;

	/**
	 * #formLabel="datum narození"
	 * @ORM\Column(type="datetime",name="birth_day",nullable=true)
	 */
	protected $birthDay=null;

	/**
	 * #formLabel="místo narození"
	 * @ORM\Column(type="string", length=100,nullable=true)
	 */
	protected $birthPlace;

	/**
	 * #formLabel="rodné číslo"
	 * @ORM\Column(type="string", length=100,nullable=true)
	 */
	protected $socialNumber;

	public function getName()
	{
		return $this->name;
	}

	public function getSurname()
	{
		return $this->surname;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getPhone()
	{
		return $this->phone;
	}

	public function getMobilPhone()
	{
		return $this->mobilPhone;
	}

	/**
	 *
	 * @return \DateTime
	 */
	public function getBirthDay()
	{
		return $this->birthDay;
	}

	/**
	 *
	 * @return string
	 */
	public function getBirthPlace()
	{
		return $this->birthPlace;
	}

	/**
	 * Rodne cislo
	 * @return string
	 */
	public function getSocialNumber()
	{
		return $this->socialNumber;
	}

	/**
	 *
	 * @param string $name
	 * @return \Entity\Person\Person
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 *
	 * @param string $surname
	 * @return \Entity\Person\Person
	 */
	public function setSurname($surname)
	{
		$this->surname = $surname;
		return $this;
	}

	/**
	 *
	 * @param string $email
	 * @return \Entity\Person\Person
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		return $this;
	}

	/**
	 *
	 * @param string $phone
	 * @return \Entity\Person\Person
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;
		return $this;
	}

	/**
	 *
	 * @param string $mobilPhone
	 * @return \Entity\Person\Person
	 */
	public function setMobilPhone($mobilPhone)
	{
		$this->mobilPhone = $mobilPhone;
		return $this;
	}

	/**
	 *
	 * @param \DateTime $birthDay
	 * @return \Entity\Person\Person
	 */
	public function setBirthDay($birthDay)
	{
		$this->birthDay = $birthDay;
		return $this;
	}

	/**
	 *
	 * @param string $birthPlace
	 * @return \Entity\Person\Person
	 */
	public function setBirthPlace($birthPlace)
	{
		$this->birthPlace = $birthPlace;
		return $this;
	}

	/**
	 *
	 * @param string $socialNumber
	 * @return \Entity\Person\Person
	 */
	public function setSocialNumber($socialNumber)
	{
		$this->socialNumber = $socialNumber;
		return $this;
	}



}

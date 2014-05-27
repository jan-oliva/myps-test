<?php
use Nette\Diagnostics\Debugger,
	Nette\Application\Routers\Route;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;

require_once dirname(__FILE__).'/../bootstrap.php';

/* @var $em EntityManager */

//$com = $em->getRepository('\Doctrine\Entity\Customer\Supplier')->find(1);
//var_dump($com);
//$com = $em->getRepository('\Doctrine\Entity\Customer\Customer1')->find(1);
//var_dump($com);
//
//exit;
$res = $em->createQueryBuilder()
		->select('supp.id,co.name,co.INr')
		->from('\Doctrine\Entity\Customer\Supplier','supp')
		->join('supp.company', 'co')
		->getQuery()->execute();
var_dump($res);
$res = $em->createQueryBuilder()
		->select('cus.customerID,co.name,co.INr')
		->from('\Doctrine\Entity\Customer\Customer','cus')
		->join('cus.company', 'co')
		->getQuery()->execute();
var_dump($res);

exit;
//$em->getConnection()->beginTransaction();
try{

	$companyData = new \Doctrine\Entity\Customer\CompanyData();
	$companyData
	->setBankAccount('2300507339')
	->setBankCode('2010')
	->setHouseNumber('49')
	//->setHouseNumberOrientation($houseNumberOrientation)
	->setINr('02285720')
	->setName('Jan Oliva')
	->setStreet('Zálší')
	->setTax("cz7301303680")
	//->setTownPart($townPart)
	->setTown("Zálší")
	->setZip("56501")	;

	$em->persist($companyData);
	$em->flush();
	echo $companyData->getCompanyDataID();
	$myCompany = new \Doctrine\Entity\Customer\Company();
	$myCompany
		->setCompanyID(1)
		->setCompanyDataID($companyData->getCompanyDataID());

	$em->persist($myCompany);
	$em->flush();
	//$em->getConnection()->commit();
}
 catch (\Exception $e){
	 $e->getMessage();
	 $e->getTraceAsString();
	 //$em->getConnection()->rollback();

 }


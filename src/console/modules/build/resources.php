<?php

require_once dirname(__FILE__) . '/../../bootstrap.php';

$em = $container->EntityManager;
/* @var $em \Doctrine\ORM\EntityManager */

//read file resources.yml
$file = __DIR__ . "/resources.yml";

$a = new Symfony\Component\Yaml\Parser();
$data = $a->parse(file_get_contents($file), true);
$dataRsc = $data['resources']['rights'];
print_r($dataRsc);
//exit;
$repos = $em->getRepository('\Entity\User\RightResource');
foreach ($dataRsc as $item) {
	$entity = $repos->findBy(array('name'=>$item['name']));
	var_dump($entity);
	if(!empty($entity)){
		echo "resource {$item['name']} already exists".PHP_EOL;
		continue;
	}

	$resource = new \Entity\User\RightResource();
	$resource->setId($item['id']);
	$resource
			->setName($item['name'])
			->setLabel($item['label']);

	try{
		$em->beginTransaction();
		$em->persist($resource);
		$em->flush();
		$em->commit();
	} catch (\Kdyby\Doctrine\DuplicateEntryException $ex) {
		$em->rollback();
		echo "resource {$item['name']} already exists".PHP_EOL;
	}


}

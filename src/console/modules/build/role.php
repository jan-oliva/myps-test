<?php

/**
 * init role, resources, ACL, users
 */

require_once dirname(__FILE__) . '/../../bootstrap.php';

$em = $container->EntityManager;
/* @var $em \Doctrine\ORM\EntityManager */

//File with initial resources for Authorization
$file = __DIR__ . "/resources.yml";
$yamlParser = new Symfony\Component\Yaml\Parser();
$data = $yamlParser->parse(file_get_contents($file), true);

$roleData = $data['resources']['roles'];

//roles add
foreach ($roleData as $roleItem){
	$entity = new \Entity\User\Role();
	$entity->setId($roleItem['id']);
	$entity
			->setName($roleItem['name'])
			->setLabel($roleItem['label']);

	$em->persist($entity);
	$em->flush();
}

//right resources from resources.yml
$dataRsc = $data['resources']['rights'];

$reposRsc = $em->getRepository('\Entity\User\RightResource');
foreach ($dataRsc as $item) {
	$res = $reposRsc->findBy(array('name'=>$item['name']));

	if(!empty($res)){
		echo "resource {$item['name']} already exists".PHP_EOL;
		continue;
	}

	$resource = new \Entity\User\RightResource();
	$resource
			->setName($item['name'])
			->setLabel($item['label']);

	try{
		$em->persist($resource);
		$em->flush();
	} catch (\Kdyby\Doctrine\DuplicateEntryException $ex) {
		echo "reource {$item['name']} already exists".PHP_EOL;
	}


}
//ACL
$aclData = $data['resources']['acl'];
foreach($aclData as $aclItem){
	$entityRootGroup = $em->getRepository('\Entity\User\Role')->find($aclItem['roleID']);
	$found = $em->getRepository('\Entity\User\RightResource')->findBy(array('name' => $aclItem['resourceName']));

	if(count($found) !== 1){
		echo "resource {$aclItem['resourceName']} doesn't exists'".PHP_EOL;
		continue;
	}

	$entityResource = current($found);
	$ra = new Entity\User\RoleACL();
	$ra
			->setPrivilege($aclItem['privilege'])
			->setAllow($aclItem['allow'])
			->setRole($entityRootGroup)
			->setRightResource($entityResource);

	$em->persist($ra);
	$em->flush();
}

$usrData = $data['resources']['users'];

$userRepos = $em->getRepository('\Entity\User\User');
/* @var $userRepos Entity\User\UserRepository */
$userRepos->setPasswordGenerator($container->PasswordGenerator);

foreach ($usrData as $userItem){

	$found = $em->getRepository('\Entity\User\Role')->findBy(array('name' => $userItem['role']));
	if(count($found) !== 1){
		echo "role {$userItem['role']} doesn't exists'. Skip user {$userItem['login']}".PHP_EOL;
		continue;
	}
	$role = current($found);
	/* @var $role Entity\User\Role */
	$persson = new \Entity\Person\Person();
	$persson
		->setName($userItem['name'])
		->setSurname($userItem['surname']);

	$user = new Entity\User\User();

	$user
			->setPass($userItem['password'])
			->setActive($userItem['active'])
			->setPerson($persson)
			->setLogin($userItem['login']);

	$userRepos->insert($user);

	$userRepos->addRole($user->getId(), $role->getId());
}


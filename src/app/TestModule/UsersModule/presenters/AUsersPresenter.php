<?php

namespace TestModule\UsersModule;

/**
 * Description of AUsersPresenter
 *
 * @author Jan Oliva
 */
abstract class AUsersPresenter extends \TestModule\AACLPresenter
{
	public function createComponentUsersTabs($name)
	{
		$tabs = new \JO\Nette\Application\UI\Tabs\Tabs($this->context->getService('ITranslator'),$this, $name);

		$tabs
				->addTab('user-list', 'Users:list', 'uživatelé')
				->addTab('user-add', 'Users:add', 'nový uživatel')
				->setMainElementClass('contenTabs');

		$arr[] = array('name'=>'role-list','resource'=>'Test:Users:Roles','link'=>'Roles:list','privilege'=>'list','label'=>'role');
		$arr[] = array('name'=>'role-add','resource'=>'Test:Users:Roles','link'=>'Roles:add','privilege'=>'add','label'=>'nová role');
		$arr[] = array('name'=>'acl-list','resource'=>'Test:Users:Acl','link'=>'Acl:list','privilege'=>'list','label'=>'Acl');
		$arr[] = array('name'=>'acl-Add','resource'=>'Test:Users:Acl','link'=>'Acl:Add','privilege'=>'add','label'=>'přidat Acl');
		$arr[] = array('name'=>'resources-list','resource'=>'Test:Users:RightResources','link'=>'RightResources:list','privilege'=>'list','label'=>'oprávnění');
		$arr[] = array('name'=>'resources-Add','resource'=>'Test:Users:RightResources','link'=>'RightResources:Add','privilege'=>'add','label'=>'přidat oprávnění');

		foreach ($arr as $item){
			if($this->getUser()->isAllowed($item['resource'], $item['privilege'])){
				$tabs->addTab($item['name'], $item['link'],$item['label']);
			}
		}

		return $tabs;
	}
}

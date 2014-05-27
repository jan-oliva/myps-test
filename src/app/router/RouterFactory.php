<?php

use Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();

		$router[] = new Route('users','Test:Users:Users:list');
		$router[] = new Route('users/add','Test:Users:Users:add');
		$router[] = new Route('user/roles','Test:Users:roles:list');
		$router[] = new Route('login','Test:Users:Sign:in');
		$router[] = new Route('logout','Test:Users:Sign:out');
		$router[] = new Route('/','Test:Homepage:default');
		$router[] = new Route('<module>.<presenter>/<action>[/<id>]','Test:Home:default');
	
		$router[] = new Route('<presenter>/<action>[/<id>]', 'Test:Homepage:default');

		return $router;
	}

}

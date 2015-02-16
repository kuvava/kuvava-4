<?php

namespace App;

use Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();
		$router[] = new Route('//[!<presenter>.]%domain%/[<url1 ([a-zA-Z][-a-zA-Z0-9]*|[0-9]+[-a-zA-Z]+[-a-zA-Z0-9]*)>/][<url2 ([a-zA-Z][-a-zA-Z0-9]*|[0-9]+[-a-zA-Z]+[-a-zA-Z0-9]*)>/][<number1 [0-9]+>/][-<action>/][<number2 [0-9]+>/]', 'Www:default');
		return $router;
	}

}

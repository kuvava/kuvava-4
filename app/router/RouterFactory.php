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
	public static function createRouter()
	{
		$router = new RouteList();
		$router[] = new Route('//[!<presenter>.]%domain%/[<url1 ([a-zA-Z][-a-zA-Z0-9]*|[0-9]+[-a-zA-Z]+[-a-zA-Z0-9]*)>/][<url2 ([a-zA-Z][-a-zA-Z0-9]*|[0-9]+[-a-zA-Z]+[-a-zA-Z0-9]*)>/][<number1 [0-9]+>/][-<action .*[a-zA-Z]+.*>/][-<number2 [0-9]+>/]', array(
			'presenter'	=> 'Www',
			'action'	=> 'default',
			'url1'		=> '',
			'url2'		=> '',
			'number1'	=> 0,
			'number2'	=> 0
			));
		return $router;
	}

}

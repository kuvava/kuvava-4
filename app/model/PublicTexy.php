<?php

namespace App\Model;


/**
 * Texy with safemode setting
 */
class PublicTexy extends \Texy
{
	
	public function __construct(){
		parent::__construct();
		\TexyConfigurator::safeMode($this);
	}

}

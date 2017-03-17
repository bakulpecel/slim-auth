<?php 

namespace App\Middleware;

use Slim\Container;

class Middleware
{
	protected $container;

	public function __construct(Container $container)

	{
		$this->container = $container;
	}
}

   ?>
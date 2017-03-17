<?php

use Respect\Validation\Validator as v;

session_start();



require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App([

	//Slim Settings
		'settings' => [

		//'determineRouteBeforeAppMiddleware' => true,
			'displayErrorDetails' => true,

			'addContentLengthHeader' => false,

	// Connection Database
			'db' => [
				'driver' 	=> 'mysql',
				'host'   	=> 'localhost',
				'database'	=> 'slim',
				'username'	=> 'root',
				'password'  => '',
				'charset'	=> 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'	=> '',
		]

	],



	]);

$container = $app->getContainer();

// Database Elequent
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
	return $capsule;
};

// Container auth
$container['auth'] = function ($container) {
	return new \App\Auth\Auth;
};

//Container Flash
$container['flash'] = function ($container) {
	return new \Slim\Flash\Messages;
};


// Template Twig
$container['view'] = function ($container) {

	$view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [

		'cache' => false,

	]);

	$view->addExtension(new \Slim\Views\TwigExtension(

		$container->router,
		$container->request->getUri()

	));

	$view->getEnvironment()->addGlobal('auth', [

		'check' => $container->auth->check(),
		'user'  => $container->auth->user(),

		]);

	$view->getEnvironment()->addGlobal('flash', $container->flash);

	return $view;
};

// Container Validator
$container['validator'] = function ($container) {
	return new App\Validation\Validator;
};

// Container HomeController
$container['HomeController'] = function ($container) {
	return new \App\Controllers\HomeController($container);
};

// Container AuthController
$container['AuthController'] = function ($container) {
	return new \App\Controllers\Auth\AuthController($container);
};

// Container PassworController
$container['PasswordController'] = function ($container) {
	return new \App\Controllers\Auth\PasswordController($container);
};

// Container csrf
$container['csrf'] = function ($container) {
	return new \Slim\Csrf\Guard;
};



$app->add(new App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new App\Middleware\OldInputMiddleware($container));
$app->add(new App\Middleware\CsrfViewMiddleware($container));


$app->add($container->csrf);

v::with('App\\Validation\\Rules');

require __DIR__ . '/../app/routes.php';

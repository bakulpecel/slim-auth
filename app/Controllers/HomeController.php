<?php 

namespace App\Controllers;


use \Slim\Views\Twig as View;


class HomeController extends Controller
{
	
	public function index($request, $response)
	{

		// $this->flash->addMessage('info', 'Error Message');

		return $this->view->render($response, 'home.twig');
		
	}

}
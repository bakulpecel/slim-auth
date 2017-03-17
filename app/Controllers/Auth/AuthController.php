<?php 

namespace App\Controllers\Auth;

// Use 
use App\Models\User;
	
use App\Controllers\Controller;

use Respect\Validation\Validator as v;


class AuthController extends Controller
{

	public function getSignOut($request, $response)
	{

		$this->auth->logout();

		return $response->withRedirect($this->router->pathFor('home'));

	}

	// Get Sign In
	public function getSignIn($request, $response)
	{


		return $this->view->render($response, 'auth/signin.twig');

	}

	// Post Sign In
	public function postSignIn($request, $response)
	{
		$auth = $this->auth->attempt(

				$request->getParam('email'),
				$request->getParam('password')
			);

		if (!$auth) {
			$this->flash->addMessage('error', 'NOTICE!  Could not sign you in those details.');
			return $response->withRedirect($this->router->pathFor('auth.signin'));
		}

			return $response->withRedirect($this->router->pathFor('home'));

	}


	// Get Sign Up
	public function getSignUp($request, $response)
	{

		// var_dump($request->getAttribute('csrf_value'));

		return $this->view->render($response, 'auth/signup.twig');
		
	}
	// Post Sign Up
	public function postSignUp($request, $response)
	{

		$validation = $this->validator->validate( $request, [

				'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),

				'name' =>  v::notEmpty()->alpha(),

				'password' =>  v::noWhitespace()->notEmpty(),

			]);



		if ($validation->failed()) {

		return $response->withRedirect($this->router->pathFor('auth.signup'));
			
		}

		$user = User::create([

		'email' => $request->getParam('email'),

		'name' => $request->getParam('name'),

		'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT ),

		]);


		$this->auth->attempt($user->email, $request->getParam('password'));

		return $response->withRedirect($this->router->pathFor('home'));

	}
}
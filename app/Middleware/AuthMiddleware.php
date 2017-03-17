<?php 

namespace App\Middleware;


class AuthMiddleware extends Middleware 
{

  public function __invoke($request, $response, $next)

   {

   	// Check if the user not signed in
   	if (!$this->container->auth->check()) {
   	 	$this->container->flash->addMessage('error', 'NOTICE! Please sign in before doing it ...???');
   	 	return $response->withRedirect($this->container->router->pathFor('auth.signin'));
   	 } ;
   
   

    $response = $next($request, $response);

    return $response;
  }
}

 ?>
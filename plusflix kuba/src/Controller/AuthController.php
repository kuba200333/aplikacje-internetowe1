<?php
namespace App\Controller;
use App\Model\Admin;
use App\Service\Router;
use App\Service\Templating;
class AuthController
{
    public function loginAction(Templating $templating, Router $router): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
        return $templating->render('auth/login.html.php', [
            'router' => $router,
            'error' => null
        ]);
    }
    public function logoutAction(Router $router): ?string
    {
        $path = $router->generatePath('movie-index'); 
        $router->redirect($path);
        return null;
    }
}
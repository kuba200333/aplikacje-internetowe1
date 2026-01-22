<?php
namespace App\Controller;
use App\Model\Category;
use App\Model\Comment;
use App\Model\Movie;
use App\Model\Platform;
use App\Service\Router;
use App\Service\Templating;
class AdminController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {

        $movies = Movie::findAll();
        $categories = Category::findAll();
        $platforms = Platform::findAll();
        $comments = Comment::findAll(); 
        $html = $templating->render('admin/index.html.php', [
            'router' => $router,
            'moviesCount' => 0,
            'categoriesCount' => 0,
            'platformsCount' => 0,
            'comments' => [], 
            'movies' => $movies 
        ]);
        return $html;
    }
}
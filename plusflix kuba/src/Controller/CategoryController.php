<?php
namespace App\Controller;
use App\Model\Category;
use App\Service\Router;
use App\Service\Templating;
class CategoryController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $categories = Category::findAll();
        $movies = \App\Model\Movie::findAll();
        $platforms = \App\Model\Platform::findAll();
        $comments = \App\Model\Comment::findAll();

        $html = $templating->render('category/index.html.php', [
            'categories' => $categories,
            'router' => $router,
            'moviesCount' => 0,
            'categoriesCount' => 0,
            'platformsCount' => 0,
            'commentsCount' => 0
        ]);
        return $html;
    }
    public function createAction(?array $requestPost, Templating $templating, Router $router): ?string
    {
        $category = new Category();
        $errors = [];

        if ($requestPost) {
            $data = $requestPost['category'] ?? [];
            $category->setName($data['name'] ?? '');
            
            $errors = Category::validate($data);
            
            if (empty($errors)) {
                $category->save();
                $router->redirect($router->generatePath('admin-category-index'));
                return null;
            }
        }
        
        $html = $templating->render('category/create.html.php', [
            'router' => $router,
            'category' => $category,
            'errors' => $errors,
        ]);
        return $html;
    }
    public function deleteAction(Router $router): ?string
    {
        $id = $_POST['id'] ?? null;
        $category = Category::find($id);
        if ($category) {
            $category->delete();
        }
        $router->redirect($router->generatePath('admin-category-index'));
        return null;
    }
}
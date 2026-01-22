<?php
namespace App\Controller;
use App\Exception\NotFoundException;
use App\Model\Platform;
use App\Service\Router;
use App\Service\Templating;
class PlatformController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $platforms = Platform::findAll();
        $movies = \App\Model\Movie::findAll();
        $categories = \App\Model\Category::findAll();
        $comments = \App\Model\Comment::findAll();

        $html = $templating->render('platform/index.html.php', [
            'platforms' => $platforms,
            'router' => $router,
            'moviesCount' => 0,
            'categoriesCount' => 0,
            'platformsCount' => 0,
            'commentsCount' => 0
        ]);
        return $html;
    }
    public function createAction(array $requestPost, Templating $templating, Router $router): ?string
    {
        $platform = new Platform();
        $errors = [];

        if (!empty($requestPost)) {
            $data = $requestPost['platform'] ?? [];
            
            $uploadedPath = $this->handleFileUpload($_FILES['logo_file'] ?? null);
            if ($uploadedPath) {
                $data['logo_path'] = $uploadedPath;
            }

            $platform->setName($data['name'] ?? '');
            $platform->setUrl($data['url'] ?? '');
            $platform->setLogoPath($data['logo_path'] ?? '');

            $errors = Platform::validate($data);

            if (empty($errors)) {
                $platform->save();
                $router->redirect($router->generatePath('admin-platform-index'));
                return null;
            }
        }
        
        $html = $templating->render('platform/create.html.php', [
            'platform' => $platform,
            'router' => $router,
            'errors' => $errors,
        ]);
        return $html;
    }

    public function editAction(Templating $templating, Router $router): ?string
    {
        $id = $_REQUEST['id'] ?? null;
        $platform = Platform::find($id);
        if (!$platform) {
            throw new NotFoundException("Brak platformy o id $id");
        }
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST['platform'] ?? [];

            $uploadedPath = $this->handleFileUpload($_FILES['logo_file'] ?? null);
            if ($uploadedPath) {
                $data['logo_path'] = $uploadedPath;
            } else {
                 $data['logo_path'] = $platform->getLogoPath();
            }

            $platform->setName($data['name']);
            $platform->setUrl($data['url']);
            $platform->setLogoPath($data['logo_path']);

            $errors = Platform::validate($data);

            if (empty($errors)) {
                $platform->save();
                $router->redirect($router->generatePath('admin-platform-index'));
                return null;
            }
        }
        $html = $templating->render('platform/edit.html.php', [
            'platform' => $platform,
            'router' => $router,
            'errors' => $errors,
        ]);
        return $html;
    }

    private function handleFileUpload(?array $file): ?string
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }

        $uploadDir = 'assets/uploads/';
        
        $info = pathinfo($file['name']);
        $ext = $info['extension'] ?? 'jpg';
        $filename = uniqid('platform_') . '.' . $ext;
        $target = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return $target;
        }

        return null;
    }
    public function deleteAction(Router $router): ?string
    {
        $id = $_POST['id'] ?? null;
        $platform = Platform::find($id);
        if ($platform) {
            $platform->delete();
        }
        $router->redirect($router->generatePath('admin-platform-index'));
        return null;
    }
}
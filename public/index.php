<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';

$config = new \App\Service\Config();
$templating = new \App\Service\Templating();
$router = new \App\Service\Router();

$action = $_REQUEST['action'] ?? 'album-index';

switch ($action) {
    case 'album-index':
        $controller = new \App\Controller\AlbumController();
        $view = $controller->indexAction($templating, $router);
        break;
    case 'album-create':
        $controller = new \App\Controller\AlbumController();
        $view = $controller->createAction($_REQUEST['album'] ?? null, $templating, $router);
        break;
    case 'album-edit':
        $controller = new \App\Controller\AlbumController();
        $view = $controller->editAction($_REQUEST['id'] ?? 0, $_REQUEST['album'] ?? null, $templating, $router);
        break;
    case 'album-show':
        $controller = new \App\Controller\AlbumController();
        $view = $controller->showAction($_REQUEST['id'] ?? 0, $templating, $router);
        break;
    case 'album-delete':
        $controller = new \App\Controller\AlbumController();
        $view = $controller->deleteAction($_REQUEST['id'] ?? 0, $router);
        break;
    case 'post-index':
        $controller = new \App\Controller\PostController();
        $view = $controller->indexAction($templating, $router);
        break;
    case 'post-create':
        $controller = new \App\Controller\PostController();
        $view = $controller->createAction($_REQUEST['post'] ?? null, $templating, $router);
        break;
    case 'post-edit':
        $controller = new \App\Controller\PostController();
        $view = $controller->editAction($_REQUEST['id'] ?? 0, $_REQUEST['post'] ?? null, $templating, $router);
        break;
    case 'post-show':
        $controller = new \App\Controller\PostController();
        $view = $controller->showAction($_REQUEST['id'] ?? 0, $templating, $router);
        break;
    case 'post-delete':
        $controller = new \App\Controller\PostController();
        $view = $controller->deleteAction($_REQUEST['id'] ?? 0, $router);
        break;
    case 'info':
        $controller = new \App\Controller\InfoController();
        $view = $controller->infoAction();
        break;
    default:
        $view = 'Not found';
        break;
}

if ($view) {
    echo $view;
}

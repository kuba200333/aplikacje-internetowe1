<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Album;
use App\Service\Router;
use App\Service\Templating;

class AlbumController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $albums = Album::findAll();
        $html = $templating->render('album/index.html.php', [
            'albums' => $albums,
            'router' => $router,
        ]);

        return $html;
    }

    public function createAction(?array $requestAlbum, Templating $templating, Router $router): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $album = Album::fromArray($_POST);
            $album->save();

            $router->redirect($router->generatePath('album-index'));
            return null;
        }

        $html = $templating->render('album/create.html.php', [
            'album' => new Album(),
            'router' => $router,
        ]);
        return $html;
    }

    public function editAction(int $albumId, ?array $requestAlbum, Templating $templating, Router $router): ?string
    {
        error_log("Editing album ID: " . $albumId);
        error_log("Request data: " . print_r($_POST, true));

        $album = Album::find($albumId);
        if (!$album) {
            throw new NotFoundException("Missing album with id $albumId");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $album->fill($_POST);
            $album->save();

            $path = $router->generatePath('album-index');
            $router->redirect($path);
            return null;
        }

        $html = $templating->render('album/edit.html.php', [
            'album' => $album,
            'router' => $router,
        ]);
        return $html;
    }

    public function showAction(int $albumId, Templating $templating, Router $router): ?string
    {
        $album = Album::find($albumId);
        if (!$album) {
            throw new NotFoundException("Missing album with id $albumId");
        }

        $html = $templating->render('album/show.html.php', [
            'album' => $album,
            'router' => $router,
        ]);
        return $html;
    }

    public function deleteAction(int $albumId, Router $router): ?string
    {
        $album = Album::find($albumId);
        if (!$album) {
            throw new NotFoundException("Missing album with id $albumId");
        }

        $album->delete();

        $path = $router->generatePath('album-index');
        $router->redirect($path);
        return null;
    }
}

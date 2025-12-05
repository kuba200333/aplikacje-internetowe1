<?php
/** @var \App\Model\Album $album */
/** @var \App\Service\Router $router */

$title = "Album Details";
$bodyClass = 'show';

ob_start(); ?>
    <h1><?= htmlspecialchars($album->getTitle()) ?></h1>
    <table border="1" style="border-collapse: collapse; width: 50%; margin: 10px 0;">
        <tr>
            <td><strong>Artist:</strong></td>
            <td><?= htmlspecialchars($album->getArtist()) ?></td>
        </tr>
        <tr>
            <td><strong>Release Year:</strong></td>
            <td><?= htmlspecialchars($album->getReleaseYear()) ?></td>
        </tr>
        <tr>
            <td><strong>Genre:</strong></td>
            <td><?= htmlspecialchars($album->getGenre()) ?></td>
        </tr>
    </table>
    <a href="<?= $router->generatePath('album-edit', ['id' => $album->getId()]) ?>"
       style="margin-right: 10px;
          background-color: #4CAF50;
          color: white;
          padding: 10px 20px;
          text-decoration: none;
          border-radius: 5px;
          font-size: 16px;">Edit</a>
    <a href="<?= $router->generatePath('album-index') ?>"
       style="background-color: #f44336;
          color: white;
          padding: 10px 20px;
          text-decoration: none;
          border-radius: 5px;
          font-size: 16px;">Back to list</a>

<?php $main = ob_get_clean();

include __DIR__ . '/../base.html.php';

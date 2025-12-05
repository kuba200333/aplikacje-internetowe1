<?php
/** @var \App\Model\Album[] $albums */
/** @var \App\Service\Router $router */

$title = 'Najlepsze Albumy';
$bodyClass = 'index';

ob_start(); ?>
    <h1>Najlepsze Albumy</h1>
    <a href="<?= $router->generatePath('album-create') ?>"
       style="margin-bottom: 10px; display: inline-block;
              background-color: #4CAF50;
              color: white;
              padding: 15px 20px;
              text-decoration: none;
              border-radius: 8px;
              font-size: 18px;">Add New Album</a>
    <table border="1" style="border-collapse: collapse; width: 100%; margin-top: 10px; text-align: left;">
        <tr style="background-color: #f2f2f2;">
            <th style="padding: 10px; width: 25%;">Title</th>
            <th style="padding: 10px; width: 20%;">Artist</th>
            <th style="padding: 10px; width: 15%;">Release Year</th>
            <th style="padding: 10px; width: 20%;">Genre</th>
            <th style="padding: 10px; width: 20%;">Actions</th>
        </tr>
        <?php foreach ($albums as $album): ?>
            <tr>
                <td style="padding: 10px;"><?= htmlspecialchars($album->getTitle()) ?></td>
                <td style="padding: 10px;"><?= htmlspecialchars($album->getArtist()) ?></td>
                <td style="padding: 10px;"><?= htmlspecialchars($album->getReleaseYear()) ?></td>
                <td style="padding: 10px;"><?= htmlspecialchars($album->getGenre()) ?></td>
                <td style="padding: 10px;">
                    <a href="<?= $router->generatePath('album-show', ['id' => $album->getId()]) ?>"
                       style="margin-right: 5px; color: #007BFF; text-decoration: none;">Details</a>
                    <a href="<?= $router->generatePath('album-edit', ['id' => $album->getId()]) ?>"
                       style="margin-right: 5px; color: #007BFF; text-decoration: none;">Edit</a>
                    <form action="<?= $router->generatePath('album-delete') ?>" method="post" style="display: inline;">
                        <input type="hidden" name="id" value="<?= $album->getId() ?>">
                        <button type="submit"
                                style="background-color: #f44336;
                                       color: white;
                                       border: none;
                                       padding: 5px 10px;
                                       border-radius: 3px;
                                       cursor: pointer;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php $main = ob_get_clean();

include __DIR__ . '/../base.html.php';

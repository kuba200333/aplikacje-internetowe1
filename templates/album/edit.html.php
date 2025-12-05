<?php
/** @var \App\Model\Album $album */
/** @var \App\Service\Router $router */

$title = "Edit Album";
$bodyClass = 'edit';

ob_start(); ?>
    <h1>Edit Album</h1>
    <form action="<?= $router->generatePath('album-edit', ['id' => $album->getId()]) ?>" method="post">
        <?php include __DIR__ . '/_form.html.php'; ?>
    </form>
<?php $main = ob_get_clean();

include __DIR__ . '/../base.html.php';

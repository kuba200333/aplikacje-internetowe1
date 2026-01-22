<?php
$title = "Edycja Filmu: {$movie->getTitle()}";
ob_start(); ?>
    <div class="edit">
        <h1>Edycja filmu</h1>
        <a href="<?= $router->generatePath('admin-movie-index') ?>" class="back-link">&larr; Powrót do listy</a>
        <form action="<?= $router->generatePath('admin-movie-edit') ?>" method="post" enctype="multipart/form-data" class="edit-form">
            <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>
            <input type="hidden" name="action" value="admin-movie-edit">
            <input type="hidden" name="id" value="<?= $movie->getId() ?>">
        </form>
    </div>
<?php $content = ob_get_clean();
include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
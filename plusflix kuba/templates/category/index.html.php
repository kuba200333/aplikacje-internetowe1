<?php
$title = 'Zarządzanie Kategoriami';
$bodyClass = 'admin';
ob_start(); ?>
<div class="admin-dashboard">
    <h1>Pulpit Administratora</h1>
    <?php include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . '_tiles.html.php'; ?>

    <div class="comments-section">
        <h1>Zarządzanie Kategoriami - Liczba kategorii: <?= $categoriesCount ?></h1>
        <div class="admin-controls">
            <a href="<?= $router->generatePath('admin-category-create') ?>" class="button">Dodaj nową kategorię</a>
        </div>
        
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nazwa Kategorii</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category->getId() ?></td>
                            <td><?= htmlspecialchars($category->getName()) ?></td>
                            <td>
                                <form action="<?= $router->generatePath('admin-category-delete') ?>" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $category->getId() ?>">
                                    <input type="submit" value="Usuń">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $content = ob_get_clean();
include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
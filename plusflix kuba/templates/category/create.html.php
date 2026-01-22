<?php
$title = 'Dodaj Kategorię';
ob_start(); ?>
    <div class="edit">
        <h1>Dodaj nową kategorię</h1>
        <a href="<?= $router->generatePath('admin-category-index') ?>" class="back-link">&larr; Powrót do listy</a>

        <form action="<?= $router->generatePath('admin-category-create') ?>" method="post" class="edit-form">
            <?php if (!empty($errors['name'])): ?>
                <div style="color: red; margin-bottom: 10px;"><?= $errors['name'] ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label for="name">Nazwa kategorii</label>
                <input type="text" id="name" name="category[name]" value="<?= isset($category) ? htmlspecialchars($category->getName() ?? '') : '' ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Zapisz">
            </div>
        </form>
    </div>
<?php $content = ob_get_clean();
include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
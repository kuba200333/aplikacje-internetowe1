<?php
$title = 'Edytuj Platformę';
ob_start(); ?>
    <div class="edit">
        <h1>Edycja platformy</h1>
        <a href="<?= $router->generatePath('admin-platform-index') ?>" class="back-link">&larr; Powrót do listy</a>
        <form action="<?= $router->generatePath('admin-platform-edit') ?>?id=<?= $platform->getId() ?>" method="post" enctype="multipart/form-data" class="edit-form">
            <input type="hidden" name="action" value="admin-platform-edit">
            <input type="hidden" name="id" value="<?= $platform->getId() ?>">
            
            <div class="form-group">
                <label for="name">Nazwa platformy</label>
                <input type="text" id="name" name="platform[name]" value="<?= htmlspecialchars($platform->getName()) ?>" required>
                <?php if (!empty($errors['name'])): ?>
                    <div style="color: red; margin-bottom: 10px;"><?= $errors['name'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="url">Adres URL</label>
                <input type="url" id="url" name="platform[url]" value="<?= htmlspecialchars($platform->getUrl()) ?>" required>
                 <?php if (!empty($errors['url'])): ?>
                    <div style="color: red; margin-bottom: 10px;"><?= $errors['url'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="logo_path">Logo</label>
                <?php if ($platform->getLogoPath()): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="<?= $platform->getLogoPath() ?>" alt="Aktualne logo" style="max-height: 50px;">
                    </div>
                <?php endif; ?>
                <input type="file" id="logo_path" name="logo_file" accept="image/*">
            </div>
            <div class="form-group">
                <input type="submit" value="Zapisz zmiany">
            </div>
        </form>
    </div>
<?php $content = ob_get_clean(); 
include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
<?php
$title = 'Zarządzanie Platformami';
$bodyClass = 'admin';
ob_start(); ?>
<div class="admin-dashboard">
    <h1>Pulpit Administratora</h1>
    <?php include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . '_tiles.html.php'; ?>

    <div class="comments-section">
        <h1>Zarządzanie Platformami - Liczba platform: <?= $platformsCount ?></h1>
        <div class="admin-controls">
            <a href="<?= $router->generatePath('admin-platform-create') ?>" class="button">Dodaj platformę</a>
        </div>
        
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nazwa</th>
                        <th>URL</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($platforms as $platform): ?>
                        <tr>
                            <td style="width: 80px; text-align: center;">
                                <?php if($platform->getLogoPath()): ?>
                                    <img src="<?= $platform->getLogoPath() ?>" alt="Logo" style="height: 30px;">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($platform->getName()) ?></td>
                            <td><?= htmlspecialchars($platform->getUrl()) ?></td>
                            <td>
                                <a href="<?= $router->generatePath('admin-platform-edit', ['id' => $platform->getId()]) ?>">Edytuj</a>
                                <form action="<?= $router->generatePath('admin-platform-delete') ?>" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $platform->getId() ?>">
                                    <input type="submit" value="Usuń"">
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
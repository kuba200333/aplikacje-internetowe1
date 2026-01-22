<?php
// Ensure this snippet has access to:
// $router, $moviesCount, $categoriesCount, $platformsCount, $comments (or count($comments))
?>
<div class="dashboard-tiles">
    <div class="tile">
        <h3>Filmy</h3>
        <p class="count"><?= $moviesCount ?? 0 ?></p>
        <a href="<?= $router->generatePath('admin-movie-index') ?>" class="tile-link">Zarządzaj</a>
    </div>
    <div class="tile">
        <h3>Kategorie</h3>
        <p class="count"><?= $categoriesCount ?? 0 ?></p>
        <a href="<?= $router->generatePath('admin-category-index') ?>" class="tile-link">Zarządzaj</a>
    </div>
    <div class="tile">
        <h3>Platformy</h3>
        <p class="count"><?= $platformsCount ?? 0 ?></p>
        <a href="<?= $router->generatePath('admin-platform-index') ?>" class="tile-link">Zarządzaj</a>
    </div>
    <div class="tile">
        <h3>Komentarze</h3>
        <p class="count"><?= is_array($comments ?? null) ? count($comments) : ($commentsCount ?? 0) ?></p>
        <a href="<?= $router->generatePath('admin-dashboard') ?>" class="tile-link">Zobacz</a>
    </div>
</div>

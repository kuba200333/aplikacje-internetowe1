<?php
$title = 'Panel Administratora - Filmy';
$bodyClass = 'admin';
ob_start(); ?>
<div class="admin-dashboard">
    <h1>Pulpit Administratora</h1>
    <?php include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . '_tiles.html.php'; ?>
    
    <div class="comments-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Zarządzanie Filmami (<?= $moviesCount ?>)</h1>
            <div class="rating-summary" style="background: rgba(231, 76, 60, 0.1); padding: 10px 20px; border-radius: 8px; border: 1px solid rgba(231, 76, 60, 0.3);">
                Średnia wszystkich ocen: <strong style="color: #e7ab3cff; font-size: 1.2em;"><?= $globalAvgRating ?> ★</strong>
            </div>
        </div>
        
        <div class="admin-controls" style="margin-bottom: 2rem;">
            <a href="<?= $router->generatePath('admin-movie-create') ?>" class="button">Dodaj nowy film</a>
        </div>

        <div class="movie-list-admin">
            <?php foreach ($movies as $item): 
                $movie = $item['movie'];
                $platforms = $item['platforms'];
                $avgRating = $item['avg_rating'];
            ?>
                <div class="movie-item-row">
                    <!-- Image -->
                    <div class="movie-image">
                        <img src="<?= $movie->getImagePath() ?>" alt="<?= htmlspecialchars($movie->getTitle()) ?>">
                    </div>

                    <!-- Details Column -->
                    <div class="movie-details">
                        <div style="width: 100%;">
                            <h3><?= htmlspecialchars($movie->getTitle()) ?></h3>
                            
                            <div class="meta-stack">
                                <span>Rok produkcji: <?= $movie->getYear() ?>, czas trwania: <?= $movie->getDuration() ?> min</span>
                                Opis:<span style="opacity:0.6;"><?= htmlspecialchars($movie->getDescription()) ?></span>
                            </div>
                        </div>

                        <div class="rating-row">
                            <i class="icon-star"></i> <?= $avgRating ?> / 10 ★
                        </div>
                    </div>

                    <!-- Platforms Column -->
                    <div class="movie-platforms">
                        <span>Platformy</span>
                        <?php if (!empty($platforms)): ?>
                            <div class="platforms-grid">
                                <?php foreach ($platforms as $platform): ?>
                                    <div class="platform-icon" title="<?= htmlspecialchars($platform->getName()) ?>">
                                        <?php if ($platform->getLogoPath()): ?>
                                            <img src="<?= $platform->getLogoPath() ?>" alt="<?= htmlspecialchars($platform->getName()) ?>">
                                        <?php else: ?>
                                            <span><?= htmlspecialchars($platform->getName()) ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span style="color: rgba(255,255,255,0.2); font-size: 0.8em;">-</span>
                        <?php endif; ?>
                    </div>

                    <!-- Actions Column -->
                    <div class="movie-actions">
                        <a href="<?= $router->generatePath('admin-movie-edit', ['id' => $movie->getId()]) ?>">
                            Edytuj
                        </a>
                        
                        <form action="<?= $router->generatePath('admin-movie-delete') ?>" method="post">
                            <input type="hidden" name="id" value="<?= $movie->getId() ?>">
                            <button type="submit">
                                Usuń
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean();
include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
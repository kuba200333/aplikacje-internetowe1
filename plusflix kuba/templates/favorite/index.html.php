<?php 
$title = 'Twoje Ulubione - Plusflix';
$isDetailsPage = true;
ob_start(); 
function buildSortLink($router, $params, $sortType) {
    unset($params['action']); 
    $newParams = array_merge($params, ['sort' => $sortType]);
    $baseUrl = $router->generatePath('favorite-index');
    $separator = (strpos($baseUrl, '?') !== false) ? '&' : '?';
    return $baseUrl . $separator . http_build_query($newParams);
}
?>
<div class="admin-dashboard" style="padding: 2rem 15%;">
    <div class="search-header-row" style="margin-bottom: 2rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
        <div class="stats-left" style="color: #ccc;">
            <span class="found-text">Ulubione: <strong style="color: #fff;"><?= $count ?></strong> filmów</span>
            <span class="sep" style="margin: 0 10px; opacity: 0.3;">|</span>
            <span class="avg-text">Średnia ocen: <strong style="color: #e7ab3c;">⭐ <?= $totalAvg ?></strong></span>
        </div>
        <div class="buttons-right">
            <style>
                .sort-btn {
                    display: inline-block;
                    padding: 0.5rem 1rem;
                    border-radius: 999px;
                    background: rgba(255, 255, 255, 0.08);
                    color: #ccc;
                    text-decoration: none;
                    font-size: 0.9rem;
                    margin-left: 0.5rem;
                    border: 1px solid transparent;
                    transition: 0.2s;
                }
                .sort-btn.active, .sort-btn:hover {
                    background: rgba(229, 9, 20, 0.2);
                    color: #e50914;
                    border-color: #e50914;
                }
            </style>
            <a href="<?= buildSortLink($router, $currentParams, 'best') ?>" class="sort-btn <?= ($currentParams['sort'] ?? '') === 'best' ? 'active' : '' ?>">Najlepsze</a>
            <a href="<?= buildSortLink($router, $currentParams, 'newest') ?>" class="sort-btn <?= ($currentParams['sort'] ?? '') === 'newest' ? 'active' : '' ?>">Najnowsze</a>
        </div>
    </div>
    
    <div class="movie-list-admin">
        <?php if (empty($results)): ?>
            <div class="empty-state" style="text-align: center; padding: 4rem 1rem; color: #777;">
                <h3>Brak ulubionych filmów</h3>
                <p>Kliknij serduszko przy filmie, aby go tutaj dodać.</p>
                <a href="<?= $router->generatePath('movie-index') ?>" class="button" style="display:inline-block; margin-top:20px; background: #e50914; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none;">Przeglądaj filmy</a>
            </div>
        <?php else: ?>
            <?php foreach ($results as $item): 
                $movie = $item['movie'];
                $platforms = $item['platforms'];
                $rating = $item['avg_rating'];
                
                $catName = 'Inne';
                if ($movie->getCatId()) {
                    $cat = \App\Model\Category::find($movie->getCatId());
                    if ($cat) $catName = $cat->getName();
                }
            ?>
                <div class="movie-item-row">
                    <!-- Image -->
                    <div class="movie-image">
                        <a href="<?= $router->generatePath('movie-show', ['id' => $movie->getId()]) ?>">
                            <?php if ($movie->getImagePath()): ?>
                                <img src="<?= htmlspecialchars($movie->getImagePath()) ?>" 
                                alt="<?= htmlspecialchars($movie->getTitle()) ?>" 
                                onerror="this.src='images/bez_plakatu.png';">
                            <?php else: ?>
                                <div style="width:100%; height:100%; display:flex; justify-content:center; align-items:center; color:#555;">Brak</div>
                            <?php endif; ?>
                        </a>
                    </div>

                    <!-- Details Column -->
                    <div class="movie-details">
                        <div style="width: 100%;">
                            <a href="<?= $router->generatePath('movie-show', ['id' => $movie->getId()]) ?>">
                                <h3><?= htmlspecialchars($movie->getTitle()) ?></h3>
                            </a>
                            
                            <div class="meta-stack">
                                <span>Kategoria: <?= htmlspecialchars($catName) ?></span>
                                <span>Rok: <?= $movie->getYear() ?></span>
                                <span style="opacity:0.6; display: block; margin-top: 5px;">
                                    <?= mb_strimwidth(htmlspecialchars($movie->getDescription()), 0, 150, "...") ?>
                                </span>
                            </div>
                        </div>

                        <div class="rating-row">
                            <i class="icon-star"></i> <?= $rating ?> / 10 ★
                        </div>
                    </div>

                    <!-- Platforms Column -->
                    <div class="movie-platforms">
                        <span>Platformy</span>
                        <?php if (!empty($platforms)): ?>
                            <div class="platforms-grid">
                                <?php foreach ($platforms as $platform): ?>
                                    <div class="platform-icon" title="<?= htmlspecialchars($platform->getName()) . ($platform->getDetails() ? ' (' . $platform->getDetails() . ')' : '') ?>">
                                        <?php if ($platform->getLogoPath()): ?>
                                            <img src="<?= htmlspecialchars($platform->getLogoPath()) ?>" alt="<?= htmlspecialchars($platform->getName()) ?>">
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
                        <a href="<?= $router->generatePath('favorite-toggle', ['id' => $movie->getId()]) ?>" 
                           style="border-color: #e50914; color: #e50914;"
                           title="Usuń z ulubionych">
                           Usuń <span style="font-size: 1.2em;">❤️</span>
                        </a>
                        <a href="<?= $router->generatePath('movie-show', ['id' => $movie->getId()]) ?>" style="margin-top: 5px;">
                            Pokaż
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<script>
    document.getElementById('contrast-toggle')?.addEventListener('click', function() {
        document.body.classList.toggle('high-contrast');
        if(document.body.classList.contains('high-contrast')) localStorage.setItem('contrast', 'high');
        else localStorage.removeItem('contrast');
    });
    if(localStorage.getItem('contrast') === 'high') document.body.classList.add('high-contrast');
</script>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../base.html.php'; ?>

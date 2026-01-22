<?php 
$title = 'Wyniki wyszukiwania - Plusflix';
ob_start(); 
function buildSortLink($router, $params, $sortType) {
    unset($params['action']); 
    $newParams = array_merge($params, ['sort' => $sortType]);
    $baseUrl = $router->generatePath('movie-index');
    $separator = (strpos($baseUrl, '?') !== false) ? '&' : '?';
    return $baseUrl . $separator . http_build_query($newParams);
}
?>
<header class="main-header sticky-header">
    <div class="logo">
        <a href="<?= $router->generatePath('movie-index') ?>">PLUSFLIX</a>
    </div>
    <div class="header-search">
        <form action="/index.php" method="get">
            <input type="hidden" name="action" value="movie-index">
            <input type="text" name="q" placeholder="Szukaj..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button type="submit">🔍</button>
        </form>
    </div>
    <div class="header-actions">
        <a href="<?= $router->generatePath('movie-index') ?>" class="back-btn" title="Wróć">↩</a>
        <a href="<?= $router->generatePath('favorite-index') ?>" class="btn-icon">❤️</a>
        <button id="contrast-toggle" class="btn-icon">🌗</button>
        <a href="<?= $router->generatePath('admin-login') ?>" class="btn-login">Zaloguj</a>
    </div>
</header>
<div class="main-search-container">
    <div class="search-header-row">
        <div class="stats-left">
            <span class="found-text">Znaleziono: <strong><?= $count ?></strong> filmów</span>
            <span class="sep">|</span>
            <span class="avg-text">Średnia ocen: ⭐ <strong><?= $totalAvg ?></strong></span>
        </div>
        <div class="buttons-right">
            <a href="<?= buildSortLink($router, $currentParams, 'best') ?>" class="sort-btn <?= ($currentParams['sort'] ?? '') === 'best' ? 'active' : '' ?>">Najlepsze</a>
            <a href="<?= buildSortLink($router, $currentParams, 'newest') ?>" class="sort-btn <?= ($currentParams['sort'] ?? '') === 'newest' ? 'active' : '' ?>">Najnowsze</a>
        </div>
    </div>
    <div class="movies-list-area">
        <?php if (empty($results)): ?>
            <div class="empty-state">
                <h3>Brak wyników</h3>
                <p>Spróbuj zmienić kryteria wyszukiwania.</p>
                <a href="<?= $router->generatePath('movie-index') ?>" class="btn-login" style="display:inline-block; margin-top:10px;">Wróć</a>
            </div>
        <?php else: ?>
            <?php foreach ($results as $item): 
                $movie = $item['movie'];
                $platforms = $item['platforms'];
                $rating = $item['avg_rating'];
                $isFav = in_array($movie->getId(), []);
                $catName = 'Inne';
                if ($movie->getCatId()) {
                    $cat = \App\Model\Category::find($movie->getCatId());
                    if ($cat) $catName = $cat->getName();
                }
            ?>
                <div class="full-width-card">
                    <a href="<?= $router->generatePath('movie-show', ['id' => $movie->getId()]) ?>" class="fw-poster">
                        <?php if ($movie->getImagePath()): ?>
                            <img src="<?= htmlspecialchars($movie->getImagePath()) ?>" 
                            alt="<?= htmlspecialchars($movie->getTitle()) ?>" 
                            onerror="this.src='images/bez_plakatu.png';">
                        <?php else: ?>
                            <div class="no-img">Brak</div>
                        <?php endif; ?>
                    </a>
                    <div class="fw-content">
                        <div class="fw-header">
                            <a href="<?= $router->generatePath('movie-show', ['id' => $movie->getId()]) ?>">
                                <h2 class="fw-title"><?= htmlspecialchars($movie->getTitle()) ?></h2>
                            </a>
                            <a href="<?= $router->generatePath('favorite-toggle', ['id' => $movie->getId()]) ?>" 
                               class="fw-fav <?= $isFav ? 'active' : '' ?>">
                               <?= $isFav ? '❤️' : '🤍' ?>
                            </a>
                        </div>
                        <div class="fw-description">
                            <?= mb_strimwidth(htmlspecialchars($movie->getDescription()), 0, 250, "...") ?>
                        </div>
                        <div class="fw-meta-stack">
                            <div class="meta-row">Kategoria: <span class="highlight"><?= htmlspecialchars($catName) ?></span></div>
                            <div class="meta-row">Rok produkcji: <span class="highlight"><?= $movie->getYear() ?></span></div>
                            <div class="meta-row">Ocena: <span class="star-highlight">⭐ <?= $rating ?>/10</span></div>
                        </div>
                        <div class="fw-platforms">
                            <?php foreach ($platforms as $platform): ?>
                                <?php if ($platform->getLogoPath()): ?>
                                    <img src="<?= htmlspecialchars($platform->getLogoPath()) ?>" 
                                         title="<?= htmlspecialchars($platform->getName()) . ($platform->getDetails() ? ' (' . $platform->getDetails() . ')' : '') ?>">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<script>
    document.getElementById('contrast-toggle').addEventListener('click', function() {
        document.body.classList.toggle('high-contrast');
        if(document.body.classList.contains('high-contrast')) localStorage.setItem('contrast', 'high');
        else localStorage.removeItem('contrast');
    });
    if(localStorage.getItem('contrast') === 'high') document.body.classList.add('high-contrast');
</script>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../base.html.php'; ?>
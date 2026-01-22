<header class="main-header <?= !empty($isDetailsPage) ? 'nav-details-mode' : '' ?>">
    <div class="nav-left">
        <?php if (!empty($isDetailsPage)): ?>
            <a href="<?= $router->generatePath('movie-index') ?>" class="back-btn" aria-label="Powrót">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
            </a>
        <?php endif; ?>
        <div class="logo">
            <a href="<?= $router->generatePath('movie-index') ?>">PLUSFLIX</a>
        </div>
    </div>

    <?php if (!empty($isDetailsPage)): ?>
        <div class="nav-search-container">
            <!-- Inline Search (Desktop) -->
            <form action="<?= $router->generatePath('movie-index') ?>" method="get" class="nav-search-form desktop-only">
                <input type="text" name="q" placeholder="Szukaj filmów..." aria-label="Szukaj">
            </form>

            <!-- Search Toggle (Mobile) -->
            <button class="btn-icon mobile-search-toggle" onclick="openSearchModal()" aria-label="Szukaj">
                🔍
            </button>
        </div>
    <?php endif; ?>

    <div class="header-actions">
        <a href="<?= $router->generatePath('favorite-index') ?>" class="btn-icon" aria-label="Ulubione">❤️</a>
        <button id="contrast-toggle" class="btn-icon" aria-label="Zmień kontrast">🌗</button>
        <a href="<?= $router->generatePath('admin-login') ?>" class="btn-login">Zaloguj</a>
    </div>
</header>

<!-- Mobile Search Modal -->
<?php if (!empty($isDetailsPage)): ?>
<div id="searchModal" class="search-modal-overlay">
    <div class="search-modal-content">
        <span class="close-search" onclick="closeSearchModal()">&times;</span>
        <h3>Szukaj filmu</h3>
        <form action="<?= $router->generatePath('movie-index') ?>" method="get" class="mobile-search-form">
            <input type="text" name="q" placeholder="Wpisz tytuł..." autofocus>
            <button type="submit" class="btn-search-submit">Szukaj</button>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
    function openSearchModal() {
        document.getElementById('searchModal').style.display = 'flex';
        document.querySelector('.mobile-search-form input').focus();
    }
    function closeSearchModal() {
        document.getElementById('searchModal').style.display = 'none';
    }

    document.getElementById('contrast-toggle').addEventListener('click', function() {
        document.body.classList.toggle('high-contrast');
        if (document.body.classList.contains('high-contrast')) {
            localStorage.setItem('contrast', 'high');
        } else {
            localStorage.removeItem('contrast');
        }
    });

    if (localStorage.getItem('contrast') === 'high') {
        document.body.classList.add('high-contrast');
    }
</script>

<?php 
$title = 'Plusflix - Strona Główna'; 
ob_start(); ?>
<section class="hero-search">
    <h1>Znajdź filmy lub seriale</h1>
    <form action="<?= $router->generatePath('movie-index') ?>" method="get" class="search-form">
        <input type="text" name="q" placeholder="Wpisz tytuł filmu..." aria-label="Szukaj">
        <button type="submit" class="search-btn">🔍</button>
    </form>
    <div class="filters-section">
        <h3>Kategorie</h3>
        <div class="tags-container">
            <?php foreach ($categories as $category): ?>
                <a href="<?= $router->generatePath('movie-index') ?>?category=<?= $category->getId() ?>" class="filter-tag category-tag">
                    <?= htmlspecialchars($category->getName()) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="filters-section">
        <h3>Platformy</h3>
        <div class="tags-container">
            <?php foreach ($platforms as $platform): ?>
                <a href="<?= $router->generatePath('movie-index') ?>?platform=<?= $platform->getId() ?>" class="filter-tag platform-tag">
                    <?php if ($platform->getLogoPath()): ?>
                        <img src="<?= htmlspecialchars($platform->getLogoPath()) ?>" class="platform-logo" alt="">
                    <?php endif; ?>
                    <?= htmlspecialchars($platform->getName()) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="container top-rated">
    <div class="section-header">
        <h2 class="section-title">Top 10 najlepiej ocenianych</h2>
    </div>
    <?php if (empty($movies)): ?>
        <p style="color: #777;">Brak filmów w bazie.</p>
    <?php else: ?>
        <div class="carousel" data-carousel="top-rated">
            <div class="carousel-controls">
                <button class="carousel-btn prev" data-target="top-rated" aria-label="Przewiń w lewo">&#8249;</button>
                <button class="carousel-btn next" data-target="top-rated" aria-label="Przewiń w prawo">&#8250;</button>
            </div>
            <div class="horizontal-scroll">
                <?php 
                $count = 0;
                foreach ($movies as $movie): 
                    if ($count >= 10) break;
                    $count++;
                ?>
                    <a href="<?= $router->generatePath('movie-show', ['id' => $movie->getId()]) ?>" class="movie-card">
                        <div class="poster-wrapper">
                            <div class="rank-number"><?= $count ?></div>
                            <?php if ($movie->getImagePath()): ?>
                                <img src="<?= htmlspecialchars($movie->getImagePath()) ?>" 
                                alt="<?= htmlspecialchars($movie->getTitle()) ?>" 
                                onerror="this.src='images/bez_plakatu.png';">
                            <?php else: ?>
                                <div style="display:flex; justify-content:center; align-items:center; height:100%; color:#555;">Brak</div>
                            <?php endif; ?>
                        </div>
                        <div class="movie-details">
                            <h4><?= htmlspecialchars($movie->getTitle()) ?></h4>
                            <span class="year"><?= $movie->getYear() ?></span>
                        </div>
                        <?php 
                            $avg = \App\Model\Rating::getAvgRating($movie->getId()); 
                        ?>
                        <div class="movie-rating-badge">
                            ⭐ <?= number_format($avg, 1) ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
<script>
    const carousels = document.querySelectorAll('[data-carousel]');

    function updateCarouselState(wrapper) {
        const scroller = wrapper.querySelector('.horizontal-scroll');
        if (!scroller) return;

        const maxScroll = scroller.scrollWidth - scroller.clientWidth;
        const current = scroller.scrollLeft;
        wrapper.classList.remove('at-start', 'at-end', 'middle');

        if (maxScroll <= 2) {
            wrapper.classList.add('at-start', 'at-end');
            return;
        }

        if (current <= 2) {
            wrapper.classList.add('at-start');
        } else if (current >= maxScroll - 2) {
            wrapper.classList.add('at-end');
        } else {
            wrapper.classList.add('middle');
        }
    }

    carousels.forEach(function(wrapper) {
        const scroller = wrapper.querySelector('.horizontal-scroll');
        if (!scroller) return;

        updateCarouselState(wrapper);
        scroller.addEventListener('scroll', function() {
            updateCarouselState(wrapper);
        });
    });

    document.querySelectorAll('.carousel-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const target = this.dataset.target;
            const carousel = document.querySelector('[data-carousel="' + target + '"] .horizontal-scroll');
            if (!carousel) return;

            const direction = this.classList.contains('next') ? 1 : -1;
            const distance = carousel.clientWidth * 0.8;
            carousel.scrollBy({ left: distance * direction, behavior: 'smooth' });
        });
    });
</script>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../base.html.php'; ?>
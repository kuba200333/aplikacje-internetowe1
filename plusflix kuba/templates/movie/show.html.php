<?php 
$title = $movie->getTitle();
$isDetailsPage = true;
ob_start(); ?>

<div class="movie-show-container">
    <!-- Top Section: Poster + Info -->
    <div class="movie-hero">
        <div class="hero-poster">
            <?php if ($movie->getImagePath()): ?>
                <img src="<?= htmlspecialchars($movie->getImagePath()) ?>" 
                     alt="<?= htmlspecialchars($movie->getTitle()) ?>" 
                     onerror="this.src='images/bez_plakatu.png';">
            <?php else: ?>
                <div class="no-poster">Brak</div>
            <?php endif; ?>
        </div>
        
    <div class="hero-info">
            <a href="<?= $router->generatePath('favorite-toggle', ['id' => $movie->getId()]) ?>" 
               class="favorite-btn" 
               title="<?= $isFavorite ? 'Usuń z ulubionych' : 'Dodaj do ulubionych' ?>">
               <?= $isFavorite ? '❤️' : '🤍' ?>
            </a>

            <div class="hero-header">
                <h1 class="movie-title"><?= htmlspecialchars($movie->getTitle()) ?></h1>
            </div>
            
            <div class="hero-description">
                <?= nl2br(htmlspecialchars($movie->getDescription())) ?>
            </div>
            
            <div class="hero-meta">
                <div class="meta-duration" title="Czas trwania">
                    🕒 <strong><?= $movie->getDuration() ?> min</strong>
                </div>
                <div class="meta-year" title="Rok produkcji">
                    📅 <strong><?= $movie->getYear() ?></strong>
                </div>
                <div class="meta-rating" title="Średnia ocena">
                    ⭐ <strong><?= number_format($avgRating, 1) ?> / 10</strong>
                </div>
            </div>

            <div class="hero-actions-bottom">
                 <button onclick="" class="btn-add-review">Dodaj recenzję</button>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Split Content -->
    <div class="content-split">
        <!-- Left Column: Platforms (1/4) -->
        <div class="platforms-section">
            <h3>Gdzie obejrzeć?</h3>
            <div class="platform-list">
                <?php foreach ($platforms as $platform): ?>
                    <a href="<?= htmlspecialchars($platform->getUrl()) ?>" target="_blank" class="platform-item">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <?php if ($platform->getLogoPath()): ?>
                                <img src="<?= htmlspecialchars($platform->getLogoPath()) ?>" alt="Logo">
                            <?php endif; ?>
                            <span><?= htmlspecialchars($platform->getName()) ?></span>
                        </div>
                        <?php if ($platform->getDetails()): ?>
                            <span class="platform-details"><?= htmlspecialchars($platform->getDetails()) ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
                <?php if (empty($platforms)): ?>
                    <p style="color: #777; font-style: italic;">Brak informacji o dostępności.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: Reviews (3/4) -->
        <div class="reviews-section">
            <h3>Opinie użytkowników</h3>
            <div class="reviews-list">
                <?php if (empty($comments)): ?>
                    <p style="color: #777; font-style: italic; text-align:center; padding: 20px;">
                        Jeszcze nikt nie ocenił tego filmu. Bądź pierwszy!
                    </p>
                <?php else: ?>

                        <div class="review-item">
                            <div class="review-header">

                            </div>
                            <div class="review-content">

                            </div>
                            <div class="review-rating">

                            </div>
                        </div>
        
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal for adding review -->
<div id="commentModal" class="modal-overlay">
    <div class="modal-content">
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('commentModal').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
    
    function closeModal() {
        document.getElementById('commentModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    window.onclick = function(event) {
        if (event.target == document.getElementById('commentModal')) {
            closeModal();
        }
    }
    
    // Key press to close
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeModal();
        }
    });
</script>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../base.html.php'; ?>
<?php
$title = 'Dashboard Administratora';
$bodyClass = 'admin';
ob_start(); ?>
<div class="admin-dashboard">
    <h1>Pulpit Administratora</h1>
    
    <?php include __DIR__ . DIRECTORY_SEPARATOR . '_tiles.html.php'; ?>

    <div class="comments-section">
        <h1>Liczba komentarzy: <?= count($comments) ?></h2>
        
        <div class="comments-list">
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                <div class="comment-item">
                    <div class="movie-info">
                        <img src="<?= $comment->getMovieImage() ?>" alt="Plakat">
                        <h4><?= htmlspecialchars($comment->getMovieTitle() ?? 'Nieznany film') ?></h4>
                    </div>
                    
                    <div class="comment-details">
                        <div class="comment-header">
                            <span class="user-name"><?= htmlspecialchars($comment->getNick()) ?></span>
                            <div class="header-meta">
                                <span class="comment-date"><?= $comment->getCreatedAt() ?></span>
                                <div class="rating-stars" title="Ocena: <?= $comment->getUserRating() ?>/10">
                                    <?php for($i=1; $i<=10; $i++): ?>
                                        <span class="star <?= $i <= $comment->getUserRating() ? 'filled' : '' ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="comment-body"><?= htmlspecialchars($comment->getContent()) ?></div>
                    </div>

                    <div class="comment-actions">
                        <form action="<?= $router->generatePath('admin-comment-delete') ?>" method="post" onsubmit="return confirm('Czy na pewno chcesz usunąć ten komentarz?');">
                            <input type="hidden" name="id" value="<?= $comment->getId() ?>">
                            <button type="submit" class="btn-delete">Usuń</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-comments">Brak komentarzy.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean();
include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
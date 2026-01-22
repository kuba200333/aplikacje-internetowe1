<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Plusflix' ?></title>
    <link rel="stylesheet" href="/assets/dist/style.min.css">
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
</head>
<body class="<?= $bodyClass ?? '' ?>">
    <div style="min-height: calc(100vh - 100px);">
        <nav><?php require(__DIR__ . DIRECTORY_SEPARATOR . 'nav.html.php') ?></nav>
        <?= $content ?? '' ?>
        <footer class="main-footer"><?php require(__DIR__ . DIRECTORY_SEPARATOR . 'footer.html.php') ?></footer>
    </div>
    <?php if (empty($content)): ?>
        <div style="color: red; padding: 20px; text-align: center;">
            Błąd: Zmienna $content jest pusta.
        </div>
    <?php endif; ?>
</body>
</html>
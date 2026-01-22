<?php
$title = 'Logowanie Administratora';
ob_start(); ?>
    <div style="max-width: 400px; margin: 0 auto; padding-top: 50px;">
        <h1>Panel Admina</h1>
        <?php if (isset($error)): ?>
            <p style="color: red; border: 1px solid red; padding: 10px;"><?= $error ?></p>
        <?php endif; ?>
        <form action="<?= $router->generatePath('admin-login') ?>" method="post">
            <div class="form-group">
                <label>Login:</label>
                <input type="text" name="login" required style="width: 100%;">
            </div>
            <div class="form-group">
                <label>Hasło:</label>
                <input type="password" name="password" required style="width: 100%;">
            </div>
            <div class="form-group">
                <input type="submit" value="Zaloguj się">
            </div>
        </form>
        <a href="<?= $router->generatePath('movie-index') ?>">Powrót do strony głównej</a>
    </div>
<?php $content = ob_get_clean();
include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
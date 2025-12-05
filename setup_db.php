<?php
try {
    $pdo = new PDO('sqlite:data.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents('sql/01-post.sql');

    $pdo->exec($sql);
    echo "Sukces! Baza data.db utworzona i tabela 'post' dodana.";
} catch (PDOException $e) {
    echo "Błąd: " . $e->getMessage();
}
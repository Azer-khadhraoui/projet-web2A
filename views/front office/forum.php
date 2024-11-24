<?php
require_once __DIR__ . '/../../config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - Choix de Section</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/forum.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/background-animation.css">
</head>
<body>
    <header class="forum-header">
        <div class="logo">
            <img src="<?= BASE_URL ?>green&purelogo.png" alt="Green & Pure Logo">
        </div>
        <div class="header-text">
            <h1>Bienvenue dans le Forum</h1>
            <p>Choisissez une section pour continuer :</p>
        </div>
    </header>
    
    <main class="forum-main">
        <div class="forum-options">
            <!-- Update links to point correctly to index.php -->
            <a href="<?= BASE_URL ?>views/front office/index.php?action=discussion" class="forum-link">Partie Discussion</a>
            <a href="<?= BASE_URL ?>views/front office/index.php?action=suggestion" class="forum-link">Partie Suggestion</a>
        </div>
    </main>
</body>
</html>

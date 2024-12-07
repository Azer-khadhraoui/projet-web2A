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
        
        <div class="header-text">
            <h1>Bienvenue dans le Forum</h1>
            <p>Choisissez une section pour continuer :</p>
        </div>
    </header>
    
    <main class="forum-main">
        <div class="forum-options">
            <!-- Existing links for the forum sections -->
            <a href="<?= BASE_URL ?>views/front office/index.php?action=discussion" class="forum-link"> Discussion</a>
            <a href="<?= BASE_URL ?>views/front office/index.php?action=suggestion" class="forum-link"> Suggestion</a>
            <a href="<?= BASE_URL ?>views/front office/index.php?action=AI" class="forum-link">conseil </a>
        </div>

        
</body>
</html>

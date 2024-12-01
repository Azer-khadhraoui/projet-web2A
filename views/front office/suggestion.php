<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/QuestionController.php';

// Initialize controller
$questionController = new QuestionController();

// Fetch only suggestions
$suggestions = $questionController->getAllSuggestions();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partie Suggestion</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/forum.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/suggestion.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/background-animation.css">
    <script src="<?= BASE_URL ?>js/validation.js"></script> <!-- Inclure le script de validation -->
</head>
<body onsubmit =validateSuggestion();>
    <header>
        <h1>Forum - Suggestions</h1>
    </header>
    <nav>
        <a href="<?= BASE_URL ?>views/front office/index.php?action=forum">Retour au Forum</a>
        | <a href="<?= BASE_URL ?>views/front office/index.php?action=discussion">Partie Discussion</a>
    </nav>

    <main class="suggestion-main">
        <h2>Ajouter une Nouvelle Suggestion</h2>
        <form action="<?= BASE_URL ?>views/front office/index.php?action=addSuggestion" method="POST" onsubmit="return validateSuggestion()"> <!-- Contrôle JS -->
            <textarea name="suggestion_text" rows="3" cols="50" placeholder="Tapez votre suggestion ici..."></textarea><br>
            <button type="submit">Soumettre la Suggestion</button>
        </form>

        <h2>Suggestions des Utilisateurs</h2>
        <?php foreach ($suggestions as $suggestion): ?>
            <div class="suggestion">
                <p><strong>Suggestion:</strong> <?= htmlspecialchars($suggestion['question_text']) ?></p>
                <p><em>Posté le: <?= $suggestion['created_at'] ?></em></p>
            </div>
            <hr>
        <?php endforeach; ?>
    </main>
</body>
</html>

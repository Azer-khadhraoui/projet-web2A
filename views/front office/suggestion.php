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
    <title>Partie Suggestion</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/forum.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/suggestion.css">
</head>
<body>
    <header>
        <h1>Forum - Suggestions</h1>
    </header>
    <nav>
        <a href="<?= BASE_URL ?>index.php?action=forum">Retour au Forum</a>
        | <a href="<?= BASE_URL ?>index.php?action=discussion">Partie Discussion</a>
    </nav>

    <main class="suggestion-main">
        <h2>Ajouter une Nouvelle Suggestion</h2>
        <form action="<?= BASE_URL ?>index.php?action=addSuggestion" method="POST">
            <textarea name="suggestion_text" rows="3" cols="50" required placeholder="Tapez votre suggestion ici..."></textarea><br>
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

<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controller/QuestionController.php';

// Initialize controller
$questionController = new QuestionController();
// discussion.php
$questions = $questionController->getQuestionsSorted($sortBy);


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
        <a href="<?= BASE_URL ?>view/frontoffice/index.php?action=forum">Return to Forum</a>
        | <a href="<?= BASE_URL ?>view/frontoffice/index.php?action=discussion">Discussion</a>
    </nav>

    <main class="suggestion-main">
        <h2>Add  New Suggestion</h2>
        <form action="<?= BASE_URL ?>view/frontoffice/index.php?action=addSuggestion" method="POST" onsubmit="return validateSuggestion()"> <!-- Contrôle JS -->
            <textarea name="suggestion_text" rows="3" cols="50" placeholder="Tap your suggestion ..."></textarea><br>
            <button type="submit">Submit  Suggestion</button>
        </form>

        <h2>Suggestion of Users</h2>
        <?php foreach ($suggestions as $suggestion): ?>
            <div class="suggestion">
                <p><strong>Suggestion:</strong> <?= htmlspecialchars($suggestion['question_text']) ?></p>
                <p><em>Posted : <?= $suggestion['created_at'] ?></em></p>
            </div>
            <hr>
        <?php endforeach; ?>
    </main>
</body>
</html>

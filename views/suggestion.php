<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controllers/QuestionController.php';

// Initialize controller
$questionController = new QuestionController();

// Fetch suggestions
$suggestions = $questionController->getAllSuggestions(); // Use getAllSuggestions if filtering by is_suggestion
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Partie Suggestion</title>
    <link rel="stylesheet" href="css/forum.css">
    <link rel="stylesheet" href="css/suggestion.css">
   
</head>
<body>
    <header>
        <h1>Forum - Suggestion</h1>
    </header>
    <nav>
        <a href="index.php?action=forum">Retour au Forum</a>
        | <a href="index.php?action=discussion">Partie Discussion</a>
    </nav>

    <main class="suggestion-main">
        <h2>Ajouter une Nouvelle Suggestion</h2>
        
        <!-- New Suggestion Form -->
        <form action="/projet module/views/submit_suggestion.php" method="POST">
            <textarea name="suggestion_text" rows="3" cols="50" required placeholder="Tapez votre suggestion ici..."></textarea><br>
            <button type="submit">Soumettre la Suggestion</button>
        </form>

        <h2>Suggestions des Utilisateurs</h2>

        <?php
        // Display each suggestion
        foreach ($suggestions as $suggestion) {
            if ($suggestion['is_suggestion'] == 1) { // Only show suggestions
                echo "<div class='suggestion'>";
                echo "<p><strong>Suggestion:</strong> " . htmlspecialchars($suggestion['question_text']) . "</p>";
                echo "</div><hr>";
            }
        }
        ?>
    </main>
</body>
</html>

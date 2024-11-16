<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Question.php';
require_once __DIR__ . '/../models/response.php'; // Ensure Response class is available for handling responses

// Enregistrer une nouvelle question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_text'])) {
    $pdo = config::getConnexion();
    $question = new Question(1, $_POST['question_text']); // Example: Client ID is 1
    $question->save($pdo);
    header("Location: index.php?action=discussion");
    exit;
}

// Récupérer toutes les questions
$pdo = config::getConnexion();
$questions = Question::getAll($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Partie Discussion</title>
    <link rel="stylesheet" href="css/forum.css">
    <link rel="stylesheet" href="css/discussion.css">
</head>
<body>
    <header>
        <h1>Forum - Discussion</h1>
    </header>
    <nav>
        <a href="index.php?action=forum">Retour au Forum</a>
        | <a href="index.php?action=suggestion">Partie Suggestion</a>
    </nav>

    <main class="discussion-main">
        <h2>Poser une Question</h2>
        <form action="index.php?action=addQuestion" method="POST">
            <textarea name="question_text" rows="3" cols="50" placeholder="Tapez votre question ici..." required></textarea><br>
            <button type="submit">Soumettre la Question</button>
        </form>

        <h2>Liste des Questions</h2>
        <ul>
            <?php foreach ($questions as $q): ?>
                <li>
                    <strong>Question #<?= $q['id_question'] ?>:</strong> <?= htmlspecialchars($q['question_text']) ?>
                    <em>(Posté le : <?= $q['created_at'] ?>)</em>

                    <!-- Display responses for each question -->
                    <?php
                    // Fetch responses for the current question
                    $responses = Response::getByQuestionId($pdo, $q['id_question']);
                    if ($responses):
                    ?>
                        <ul>
                            <?php foreach ($responses as $response): ?>
                                <li><strong>Réponse:</strong> <?= htmlspecialchars($response['response_text']) ?> 
                                <em>(Posté le : <?= $response['created_at'] ?>)</em></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <!-- Form to add a new response -->
                    <form action="index.php?action=addResponse&question_id=<?= $q['id_question'] ?>" method="POST">
                        <textarea name="response_text" rows="2" cols="50" placeholder="Tapez votre réponse ici..." required></textarea><br>
                        <button type="submit">Soumettre la Réponse</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>

<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../models/question.php';
require_once __DIR__ . '/../../models/response.php';
require_once __DIR__ . '/../../controllers/QuestionController.php';

$pdo = config::getConnexion();

// Enregistrer une nouvelle question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_text'])) {
    $question_text = trim($_POST['question_text']);
    if (!empty($question_text)) {
        $question = new Question(1, $question_text); // Example: Client ID is 1
        $question->save($pdo);
        header("Location: discussion.php");
        exit;
    } else {
        echo "La question ne peut pas être vide.";
    }
}

// Récupérer toutes les questions
try {
    $questions = Question::getAll($pdo);
} catch (Exception $e) {
    echo "Erreur lors de la récupération des questions : " . $e->getMessage();
    $questions = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Partie Discussion</title>
    <!-- Corrected CSS Paths -->
    <link rel="stylesheet" href="<?= BASE_URL ?>css/forum.css.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/discussion.css">
</head>
<body>
    <header>
        <h1>Forum - Discussion</h1>
    </header>
    <nav>
    <nav>
         <a href="<?= BASE_URL ?>index.php?action=forum">Retour au Forum</a>
        |<a href="<?= BASE_URL ?>index.php?action=suggestion">Partie Suggestion</a>
</nav>
    </nav>

    <main class="discussion-main">
        <h2>Poser une Question</h2>
        <form action="<?= BASE_URL ?>index.php?action=addQuestion" method="POST">
            <textarea name="question_text" rows="3" cols="50" placeholder="Tapez votre question ici..." required></textarea><br>
            <button type="submit">Soumettre la Question</button>
        </form>

        <h2>Liste des Questions</h2>
        <ul>
            <?php foreach ($questions as $q): ?>
                <li>
                    <strong>Question #<?= htmlspecialchars($q['id_question']) ?>:</strong> <?= htmlspecialchars($q['question_text']) ?>
                    <em>(Posté le : <?= $q['created_at'] ?>)</em>
                    <!-- Corrected Paths for Admin -->
                    <a href="<?= BASE_URL ?>views/back office/bs-simple-admin/updateQuestion.php?id=<?= $q['id_question'] ?>&context=user">Modifier</a>
                    <a href="<?= BASE_URL ?>views/back office/bs-simple-admin/deleteQuestion.php?id=<?= $q['id_question'] ?>&context=user" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?');">Supprimer</a>

                    <!-- Fetch Responses for Each Question -->
                    <?php
                    try {
                        $responses = Response::getByQuestionId($pdo, $q['id_question']);
                    } catch (Exception $e) {
                        echo "Erreur lors de la récupération des réponses : " . $e->getMessage();
                        $responses = [];
                    }

                    if ($responses):
                    ?>
                        <ul>
                            <?php foreach ($responses as $response): ?>
                                <li>
                                    <strong>Réponse:</strong> <?= htmlspecialchars($response['response_text']) ?>
                                    <em>(Posté le : <?= $response['created_at'] ?>)</em>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <!-- Form to Add a New Response -->
                    <form action="discussion.php?action=addResponse&question_id=<?= $q['id_question'] ?>" method="POST">
                        <textarea name="response_text" rows="2" cols="50" placeholder="Tapez votre réponse ici..." required></textarea><br>
                        <button type="submit">Soumettre la Réponse</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>

<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/QuestionController.php';
require_once __DIR__ . '/../../controllers/ResponseController.php';

// Initialize controllers
$questionController = new QuestionController();
$responseController = new ResponseController();

// Get all questions (no pagination or sorting)
$questions = $questionController->getAllQuestions();

// Add a response
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['question_id'], $_POST['response_text'])) {
    $question_id = (int) $_GET['question_id'];
    $response_text = trim($_POST['response_text']);
    if (strlen($response_text) >= 2) {
        $responseController->addResponse($question_id, 1, $response_text); // User ID = 1 by default
        header("Location: " . BASE_URL . "views/front office/index.php?action=discussion");
        exit;
    } else {
        echo "<script>alert('La réponse doit contenir au moins 2 caractères.');</script>";
    }
}

// Add a question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_text'])) {
    $question_text = trim($_POST['question_text']);
    if (strlen($question_text) >= 5) { // Server-side validation (min. 5 characters)
        // Call controller to add the question and get the ID
        $questionId = $questionController->addQuestion(1, $question_text); // User ID = 1 by default
        if ($questionId) {
            // Redirect to the same discussion page
            header("Location: " . BASE_URL . "views/front office/index.php?action=discussion");
            exit;
        } else {
            echo "<script>alert('Erreur lors de l\'ajout de la question.');</script>";
        }
    } else {
        echo "<script>alert('La question doit contenir au moins 5 caractères.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Partie Discussion</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/forum.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/discussion.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/background-animation.css">
    <script src="<?= BASE_URL ?>js/validation.js"></script> <!-- Include validation.js -->
</head>
<body>
    <header>
        <h1>Forum - Discussion</h1>
    </header>
    <nav>
        <a href="<?= BASE_URL ?>views/front office/index.php?action=forum">Retour au Forum</a> |
        <a href="<?= BASE_URL ?>views/front office/index.php?action=suggestion">Partie Suggestion</a>
    </nav>

    <!-- Form for submitting a new question -->
    <h2>Poser une Question</h2>
    <form action="<?= BASE_URL ?>views/front office/index.php?action=discussion" method="POST" onsubmit="return validateQuestion();">
        <textarea name="question_text" rows="3" cols="50" placeholder="Tapez votre question ici..." required></textarea><br>
        <button type="submit">Soumettre la Question</button>
    </form>

    <main class="discussion-main">
        <h2>Liste des Questions</h2>
        <ul>
            <?php foreach ($questions as $q): ?>
                <li>
                    <strong>Question #<?= $q['id_question'] ?>:</strong> <?= htmlspecialchars($q['question_text']) ?>
                    <em>(Posté le : <?= $q['created_at'] ?>)</em>
                    <a href="<?= BASE_URL ?>views/back office/bs-simple-admin/updateQuestion.php?id=<?= $q['id_question'] ?>&context=user" class="btn btn-primary">Modifier</a>
                    <a href="<?= BASE_URL ?>views/back office/bs-simple-admin/deleteQuestion.php?id=<?= $q['id_question'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?');">Supprimer</a>

                    <!-- Display responses -->
                    <?php
                    $responses = $responseController->getResponsesByQuestion($q['id_question']);
                    if (!empty($responses)):
                    ?>
                        <ul>
                            <?php foreach ($responses as $response): ?>
                                <li>
                                    <strong>Réponse:</strong> <?= htmlspecialchars($response['response_text']) ?>
                                    <em>(Posté le : <?= $response['created_at'] ?>)</em>
                                    <a href="<?= BASE_URL ?>views/back office/bs-simple-admin/updateResponse.php?id=<?= $response['id_response'] ?>&context=user" class="btn btn-primary">Modifier</a>
                                    <a href="<?= BASE_URL ?>views/back office/bs-simple-admin/deleteResponse.php?id=<?= $response['id_response'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ?');">Supprimer</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <!-- Form to add a response -->
                    <form action="<?= BASE_URL ?>views/front office/index.php?action=discussion&question_id=<?= $q['id_question'] ?>" method="POST" onsubmit="return validateResponse(this)">
                        <textarea name="response_text" rows="2" cols="50" placeholder="Tapez votre réponse ici..." required></textarea><br>
                        <button type="submit">Soumettre la Réponse</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>

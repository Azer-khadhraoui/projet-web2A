<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/QuestionController.php';
require_once __DIR__ . '/../../controllers/ResponseController.php';

// Initialize controllers
$questionController = new QuestionController();
$responseController = new ResponseController();

// Get sorting criterion from the query string, default to 'is_suggestion'
$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'is_suggestion';
$allowedSortColumns = ['is_suggestion'];

// Verify the sorting column
if (!in_array($sortBy, $allowedSortColumns)) {
    $sortBy = 'is_suggestion'; // Default sorting column
}

// Fetch regular questions (non-suggestions)
$questions = $questionController->getQuestionsSorted('is_suggestion'); // Assuming 'is_suggestion' is the flag

// Fetch suggestions (which are flagged as 'is_suggestion' = 1)
$suggestions = $questionController->getSuggestions(); // Assuming you have a method for fetching suggestions

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Partie Discussion</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/forum.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/discussion.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/background-animation.css">
    <script src="<?= BASE_URL ?>js/validation.js"></script>
</head>
<body>
    <header>
        <h1>Forum - Discussion</h1>
    </header>
    <nav>
        <a href="<?= BASE_URL ?>views/front office/index.php?action=forum&sortBy=<?= $sortBy ?>">Retour au Forum</a> |
        <a href="<?= BASE_URL ?>views/front office/index.php?action=suggestion&sortBy=<?= $sortBy ?>">Partie Suggestion</a>
    </nav>

    <!-- Add a new question form -->
    <h2>Ajouter une Question</h2>
    <form action="<?= BASE_URL ?>views/front office/index.php?action=addQuestion" method="POST">
        <textarea name="question_text" placeholder="Votre question..." required></textarea><br>
        <button type="submit" class="btn btn-primary">Ajouter la question</button>
    </form>

    <!-- Sorting dropdown -->
    <h2>Triez les Questions</h2>
    <form method="GET" action="<?= BASE_URL ?>views/front office/index.php?action=discussion">
        <label for="sortBy">Trier par :</label>
        <select name="sortBy" id="sortBy" onchange="this.form.submit();">
            <option value="is_suggestion" <?= ($sortBy === 'is_suggestion') ? 'selected' : '' ?>>Type (Suggestion)</option>
        </select>
        <button type="submit">Appliquer</button>
    </form>

    <main class="discussion-main">
        <h2>Liste des Questions</h2>
        
        <!-- Display regular questions first -->
        <h3>Questions normales</h3>
        <ul>
            <?php foreach ($questions as $q): ?>
                <?php if ($q['is_suggestion'] == 0): // Only display non-suggestions ?>
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
                                        <strong>Réponse : </strong> <?= htmlspecialchars($response['response_text']) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <!-- Add a response form -->
                        <form action="<?= BASE_URL ?>views/front office/index.php?action=addResponse&question_id=<?= $q['id_question'] ?>" method="POST">
                            <textarea name="response_text" placeholder="Votre réponse..." required></textarea>
                            <button type="submit" class="btn btn-success">Répondre</button>
                        </form>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <!-- Display suggestions below -->
        <h3>Suggestions</h3>
        <ul>
            <?php foreach ($suggestions as $q): ?>
                <?php if ($q['is_suggestion'] == 1): // Only display suggestions ?>
                    <li>
                        <strong>Suggestion #<?= $q['id_question'] ?>:</strong> <?= htmlspecialchars($q['question_text']) ?>
                        <em>(Posté le : <?= $q['created_at'] ?>)</em>
                        <a href="<?= BASE_URL ?>views/back office/bs-simple-admin/updateQuestion.php?id=<?= $q['id_question'] ?>&context=user" class="btn btn-primary">Modifier</a>
                        <a href="<?= BASE_URL ?>views/back office/bs-simple-admin/deleteQuestion.php?id=<?= $q['id_question'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette suggestion ?');">Supprimer</a>

                        <!-- Display responses -->
                        <?php
                        $responses = $responseController->getResponsesByQuestion($q['id_question']);
                        if (!empty($responses)):
                        ?>
                            <ul>
                                <?php foreach ($responses as $response): ?>
                                    <li>
                                        <strong>Réponse : </strong> <?= htmlspecialchars($response['response_text']) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <!-- Add a response form -->
                        <form action="<?= BASE_URL ?>views/front office/index.php?action=addResponse&question_id=<?= $q['id_question'] ?>" method="POST">
                            <textarea name="response_text" placeholder="Votre réponse..." required></textarea>
                            <button type="submit" class="btn btn-success">Répondre</button>
                        </form>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>

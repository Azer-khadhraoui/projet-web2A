<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controller/QuestionController.php';
require_once __DIR__ . '/../../controller/ResponseController.php';

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
$questions = $questionController->getQuestionsSorted('is_suggestion');

// Fetch suggestions (which are flagged as 'is_suggestion' = 1)
$suggestions = $questionController->getSuggestions();

// Check if the like action is triggered
if (isset($_GET['action']) && $_GET['action'] == 'likeQuestion' && isset($_GET['question_id'])) {
    $question_id = $_GET['question_id'];
    // Increment the likes count for the specific question
    $questionController->incrementLikes($question_id);
    // Redirect back to the discussion page to reflect the updated like count
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
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
    <script src="<?= BASE_URL ?>js/validation.js"></script>
</head>
<body>
    <header>
        <h1>Forum - Discussion</h1>
    </header>
    <nav>
        <a href="<?= BASE_URL ?>view/frontoffice/index.php?action=forum&sortBy=<?= $sortBy ?>">Return to Forum</a> |
        <a href="<?= BASE_URL ?>view/frontoffice/index.php?action=suggestion&sortBy=<?= $sortBy ?>"> Suggestion</a>
    </nav>

    <!-- Add a new question form -->
    <h2>ADD Question</h2>
    <form action="<?= BASE_URL ?>view/frontoffice/index.php?action=addQuestion" method="POST">
        <textarea name="question_text" placeholder="Your question..." required></textarea><br>
        <button type="submit" class="btn btn-primary">add question</button>
    </form>

    <!-- Sorting dropdown -->
    <h2>Sort Questions</h2>
    <form method="GET" action="<?= BASE_URL ?>view/frontoffice/index.php?action=discussion">
        <label for="sortBy">sort by :</label>
        <select name="sortBy" id="sortBy" onchange="this.form.submit();">
            <option value="is_suggestion" <?= ($sortBy === 'is_suggestion') ? 'selected' : '' ?>>Type (Suggestion)</option>
        </select>
        <button type="submit">Apply</button>
    </form>

    <main class="discussion-main">
        <h2>List of Questions</h2>

        <!-- Display regular questions first -->
        <h3> Normal Questions</h3>
        <ul>
            <?php foreach ($questions as $q): ?>
                <?php if ($q['is_suggestion'] == 0): ?>
                    <li>
                        <strong>Question #<?= $q['id_question'] ?>:</strong> <?= htmlspecialchars($q['question_text']) ?>
                        <em>(Posté le : <?= $q['created_at'] ?>)</em>
                        <a href="<?= BASE_URL ?>view/backoffice/bs-simple-admin/updateQuestion.php?id=<?= $q['id_question'] ?>&context=user" class="btn btn-primary">Modify</a>
                        <a href="<?= BASE_URL ?>view/backoffice/bs-simple-admin/deleteQuestion.php?id=<?= $q['id_question'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your question ?');">delete</a>

                        <!-- Display responses and add response form -->
                        <?php if ($q['status'] != 'closed' && $q['status'] != 'answered'): ?>
                            <?php
                            $responses = $responseController->getResponsesByQuestion($q['id_question']);
                            if (!empty($responses)):
                            ?>
                                <ul>
                                    <?php foreach ($responses as $response): ?>
                                        <li>
                                            <strong>Response : </strong> <?= htmlspecialchars($response['response_text']) ?>
                                            
                                            <!-- Update and Delete buttons for response -->
                                            <a href="<?= BASE_URL ?>view/backoffice/bs-simple-admin/updateResponse.php?id=<?= $response['id_response'] ?>&question_id=<?= $q['id_question'] ?>" class="btn btn-primary">Modify</a>
                                            <a href="<?= BASE_URL ?>view/backoffice/bs-simple-admin/deleteResponse.php?id=<?= $response['id_response'] ?>&question_id=<?= $q['id_question'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your  response ?');">Delete</a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <form action="<?= BASE_URL ?>view/frontoffice/index.php?action=addResponse&question_id=<?= $q['id_question'] ?>" method="POST">
                                <textarea name="response_text" placeholder="Your response..." required></textarea>
                                <button type="submit" class="btn btn-success">answer</button>
                            </form>
                        <?php else: ?>
                            <p>This question is  <?= $q['status'] ?> and can't receive  response.</p>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <!-- Display suggestions -->
        <h3>Suggestions</h3>
        <ul>
            <?php foreach ($suggestions as $q): ?>
                <?php if ($q['is_suggestion'] == 1): ?>
                    <li>
                        <strong>Suggestion #<?= $q['id_question'] ?>:</strong> <?= htmlspecialchars($q['question_text']) ?>
                        <em>(Posted  : <?= $q['created_at'] ?>)</em>
                        
                        <a href="<?= BASE_URL ?>view/backoffice/bs-simple-admin/updateQuestion.php?id=<?= $q['id_question'] ?>&context=user" class="btn btn-primary">Modify</a>
                        <a href="<?= BASE_URL ?>view/backoffice/bs-simple-admin/deleteQuestion.php?id=<?= $q['id_question'] ?>" class="btn btn-danger" onclick="return confirm('Are you sur you want to add suggestion ?');">Delete</a>

                        <!-- Display responses and add response form -->
                        <?php if ($q['status'] != 'closed' && $q['status'] != 'answered'): ?>
                            <?php
                            $responses = $responseController->getResponsesByQuestion($q['id_question']);
                            if (!empty($responses)):
                            ?>
                                <ul>
                                    <?php foreach ($responses as $response): ?>
                                        <li>
                                            <strong>Response : </strong> <?= htmlspecialchars($response['response_text']) ?>

                                            <!-- Update and Delete buttons for response -->
                                            <a href="<?= BASE_URL ?>view/backoffice/bs-simple-admin/updateResponse.php?id=<?= $response['id_response'] ?>&question_id=<?= $q['id_question'] ?>" class="btn btn-primary">Modifier</a>
                                            <a href="<?= BASE_URL ?>view/backoffice/bs-simple-admin/deleteResponse.php?id=<?= $response['id_response'] ?>&question_id=<?= $q['id_question'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ?');">Supprimer</a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <form action="<?= BASE_URL ?>view/frontoffice/index.php?action=addResponse&question_id=<?= $q['id_question'] ?>" method="POST">
                                <textarea name="response_text" placeholder="Your response..." required></textarea>
                                <button type="submit" class="btn btn-success">answer</button>
                            </form>
                        <?php else: ?>
                            <p>This suggestion is<?= $q['status'] ?> and can't  receive response.</p>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>

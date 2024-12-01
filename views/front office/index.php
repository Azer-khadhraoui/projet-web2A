<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/QuestionController.php';
require_once __DIR__ . '/../../controllers/ResponseController.php';

// Define the current directory base URL
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

// Get sorting criterion from the query string, default to 'created_at'
$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'is_suggestion';
$allowedSortColumns = ['is_suggestion'];

// Verify that the sorting criterion is valid, otherwise default to 'created_at'
if (!in_array($sortBy, $allowedSortColumns)) {
    $sortBy = 'is_suggestion'; // Default sorting column
}

// Initialize controllers
$questionController = new QuestionController();
$responseController = new ResponseController();

// Handle different actions
$action = $_GET['action'] ?? 'forum'; // Default action is 'forum'

switch ($action) {
    case 'forum':
        include __DIR__ . '/forum.php'; // Include the forum page
        break;

    case 'discussion':
        include __DIR__ . '/discussion.php'; // Include the discussion page
        break;

    case 'suggestion':
        include __DIR__ . '/suggestion.php'; // Include the suggestion page
        break;

    case 'addQuestion':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_text'])) {
            // Sanitize input
            $questionText = htmlspecialchars(trim($_POST['question_text']));
            
            // Validate question text
            if (strlen($questionText) < 3) {
                echo "<script>alert('La question doit contenir au moins 3 caractères.');</script>";
                include __DIR__ . '/forum.php'; // Redisplay the forum page if validation fails
            } else {
                // Add the question
                $questionController->addQuestion(1, $questionText); // Assuming user ID is 1
                header("Location: $baseUrl/index.php?action=discussion&sortBy=$sortBy"); // Redirect to discussion page with sortBy preserved
                exit;
            }
        } else {
            include __DIR__ . '/forum.php'; // In case no question text is provided
        }
        break;

    case 'addSuggestion':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suggestion_text'])) {
            // Sanitize input
            $suggestionText = htmlspecialchars(trim($_POST['suggestion_text']));
            
            // Validate suggestion text
            if (strlen($suggestionText) < 3) {
                echo "<script>alert('La suggestion doit contenir au moins 3 caractères.');</script>";
                include __DIR__ . '/suggestion.php'; // Redisplay the suggestion page if validation fails
            } else {
                // Add suggestion
                $questionController->addQuestion(1, $suggestionText, true); // Assuming user ID is 1
                header("Location: $baseUrl/index.php?action=suggestion&sortBy=$sortBy"); // Redirect to suggestion page with sortBy preserved
                exit;
            }
        } else {
            include __DIR__ . '/suggestion.php'; // In case no suggestion text is provided
        }
        break;

    case 'addResponse':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['question_id'], $_POST['response_text'])) {
            // Sanitize input
            $responseText = htmlspecialchars(trim($_POST['response_text']));
            $questionId = (int) $_GET['question_id'];
            
            // Validate response text
            if (strlen($responseText) < 2) {
                echo "<script>alert('La réponse doit contenir au moins 2 caractères.');</script>";
                include __DIR__ . '/discussion.php'; // Show discussion again if validation fails
            } else {
                // Add response to the specific question
                $responseController->addResponse($questionId, 1, $responseText); // Assuming user ID is 1
                header("Location: $baseUrl/index.php?action=discussion&sortBy=$sortBy"); // Redirect back to discussion page with sortBy preserved
                exit;
            }
        } else {
            include __DIR__ . '/discussion.php'; // In case no response text is provided
        }
        break;

    default:
        include __DIR__ . '/forum.php'; // Default to forum page if no action matches
        break;
}
?>

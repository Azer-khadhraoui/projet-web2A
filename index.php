<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/controllers/QuestionController.php';
require_once __DIR__ . '/controllers/ResponseController.php';

// Set the default action
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? 'forum';

// Initialize controllers when needed
$questionController = new QuestionController();
$responseController = new ResponseController();

// Handle different actions
switch ($action) {
    case 'forum':
        // Display the main forum page
        include __DIR__ . '/views/front office/forum.php';
        break;

    case 'discussion':
        // Display the discussion page
        include __DIR__ . '/views/front office/discussion.php';
        break;

    case 'suggestion':
        // Display the suggestion page
        include __DIR__ . '/views/front office/suggestion.php';
        break;

    case 'addQuestion':
        // Handle adding a new question
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_text'])) {
            try {
                $questionController->addQuestion(1, $_POST['question_text']); // Example: Client ID is 1
                header("Location: " . BASE_URL . "index.php?action=discussion");
                exit;
            } catch (Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        } else {
            echo "Erreur : Données invalides.";
        }
        break;

    case 'addResponse':
        // Handle adding a new response
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['question_id']) && isset($_POST['response_text'])) {
            try {
                $question_id = (int) $_GET['question_id'];
                $responseController->addResponse($question_id, 1, $_POST['response_text']); // Example: User ID is 1
                header("Location: " . BASE_URL . "index.php?action=discussion");
                exit;
            } catch (Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        } else {
            echo "Erreur : Données invalides.";
        }
        break;
        
        case 'addSuggestion':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suggestion_text'])) {
                try {
                    $suggestion_text = $_POST['suggestion_text'];
                    $id_client = 1; // Example: Replace with the actual client ID logic
                    $questionController->addQuestion($id_client, $suggestion_text, true); // Mark as suggestion
                    header("Location: " . BASE_URL . "index.php?action=suggestion");
                    exit;
                } catch (Exception $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            } else {
                echo "Erreur : Données invalides.";
            }
            break;
        

    default:
        // Default case: Redirect to the forum page
        header("Location: " . BASE_URL . "index.php?action=forum");
        break;
}

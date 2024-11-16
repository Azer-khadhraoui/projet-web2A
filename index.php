<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/controllers/QuestionController.php';
require_once __DIR__ . '/controllers/ResponseController.php';

// Set the default action
$action = $_GET['action'] ?? 'forum';

// Initialize controllers
$questionController = new QuestionController();
$responseController = new ResponseController();

// Handle different actions
switch ($action) {
    case 'forum':
        // Display the main forum page
        include 'views/forum.php';
        break;

    case 'discussion':
        // Display the discussion page
        include 'views/discussion.php';
        break;

    case 'suggestion':
        // Display the suggestion page
        include 'views/suggestion.php';
        break;

    case 'addQuestion':
        // Handle adding a new question
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_text'])) {
            // Use the client ID of 1 for this example
            $questionController->addQuestion(1, $_POST['question_text']); 
            
            // Redirect to the discussion page after adding the question
            header("Location: index.php?action=discussion");
            exit;
        } else {
            // If the request is not POST or data is missing, show an error
            echo "Erreur : Impossible d'ajouter la question. Vérifiez les données.";
        }
        break;

    case 'addResponse':
        // Handle adding a new response
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['question_id']) && isset($_POST['response_text'])) {
            $question_id = (int) $_GET['question_id'];
            
            // Use user ID of 1 for this example
            $responseController->addResponse($question_id, 1, $_POST['response_text']); 
            
            // Redirect to the discussion page after adding the response
            header("Location: index.php?action=discussion");
            exit;
        } else {
            // If the request is not POST or data is missing, show an error
            echo "Erreur : Impossible d'ajouter la réponse. Vérifiez les données.";
        }
        break;

    default:
        // Default case: Display the main forum page
        include 'views/forum.php';
        break;
}

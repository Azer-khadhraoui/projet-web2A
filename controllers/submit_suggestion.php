<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../../controllers/QuestionController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suggestion_text'])) {
    $suggestion_text = $_POST['suggestion_text'];
    $questionController = new QuestionController();

    // Use a sample client ID of 1; replace this as needed.
    $id_client = 1; 
    $questionController->addQuestion($id_client, $suggestion_text, true); // Pass true to mark it as a suggestion

    // Redirect back to the suggestion page to display the new suggestion
    header("Location: ../views/suggestion.php");
    exit;
}
?>

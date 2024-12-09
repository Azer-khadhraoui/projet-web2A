<?php
session_start();
require_once 'QuestionController.php';
require_once '../Model/QuestionModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the user is logged in and has admin privileges
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        // Get the input data
        $titre_quest = $_POST['titre_quest'];
        $contenue = $_POST['contenue'];

        // Validate inputs
        if (!empty($titre_quest) && !empty($contenue)) {
            // Create a QuestionModel instance
            $question = new QuestionModel($titre_quest, $contenue, null);
            
            // Create a QuestionController instance and add the question
            $questionController = new QuestionController();
            $result = $questionController->addQuestion($question);  // Capture the result of adding the question

            // Check if the result is true or false and display an appropriate message
            if (!$result) {
                echo "Question added successfully.";
            } else {
                echo "Failed to add question.";
            }
        } else {
            echo "Both title and content are required.";
        }
    } else {
        echo "You do not have permission to add a question.";
    }
}
?>

<?php
session_start();
require_once 'QuestionControlleradem.php'; // Adjust this path if necessary

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "You do not have permission to perform this action.";
    exit();
}

if (isset($_POST['id_quest'])) {
    $id_quest = $_POST['id_quest'];

    // Initialize the QuestionController and call the delete method
    $questionController = new QuestionController();
    $questionController->deleteQuestion($id_quest);

    echo "Question deleted successfully.";
} else {
    echo "Invalid question ID.";
}
?>


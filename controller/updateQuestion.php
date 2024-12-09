<?php
session_start();
require_once 'QuestionController.php';

if (isset($_POST['id_quest'], $_POST['titre_quest'], $_POST['contenue'])) {
    $id_quest = $_POST['id_quest'];
    $new_title = $_POST['titre_quest'];
    $new_content = $_POST['contenue'];

    // Initialize QuestionController and call the update method
    $questionController = new QuestionController();
    $result = $questionController->updateQuestionContent($id_quest, $new_title, $new_content);

    if ($result) {
        echo "Question updated successfully.";
    } else {
        echo "Failed to update the question.";
    }
} else {
    echo "Invalid data.";
}
?>



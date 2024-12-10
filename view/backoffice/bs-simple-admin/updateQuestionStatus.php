<?php
require_once __DIR__ . '/../../../controller/QuestionController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id']) && isset($_POST['status'])) {
    $controller = new QuestionController();
    try {
        $controller->updateQuestionStatus($_POST['question_id'], $_POST['status']);
        header('Location: ../forum_admin.php'); // Redirect back to the admin forum page
        exit;
    } catch (Exception $e) {
        echo "Error updating question status: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>

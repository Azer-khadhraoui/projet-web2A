<?php
require_once __DIR__ . '/../../../controllers/QuestionController.php';

$controller = new QuestionController();

if (isset($_GET['id'])) {
    try {
        $controller->deleteQuestion($_GET['id']);
        
        // Redirect based on context (admin or user)
        if (isset($_GET['context']) && $_GET['context'] === 'admin') {
            header("Location: ../forum_admin.php");
        } else {
            header("Location: ../../front office/discussion.php");
        }
        exit;
    } catch (Exception $e) {
        echo "Error deleting question: " . $e->getMessage();
    }
} else {
    echo "No question ID provided!";
    exit;
}
?>

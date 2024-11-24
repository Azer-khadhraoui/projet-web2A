<?php
require_once __DIR__ . '/../../../controllers/ResponseController.php';

$controller = new ResponseController();

if (isset($_GET['id'])) {
    try {
        $controller->deleteResponse($_GET['id']);
        
        // Redirect based on context (admin or user)
        if (isset($_GET['context']) && $_GET['context'] === 'admin') {
            header("Location: ../forum_admin.php");
        } else {
            header("Location: ../../front office/discussion.php");
        }
        exit;
    } catch (Exception $e) {
        echo "Error deleting response: " . $e->getMessage();
    }
} else {
    echo "No response ID provided!";
    exit;
}
?>

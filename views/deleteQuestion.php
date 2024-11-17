<?php
require_once __DIR__ . '/../controllers/QuestionController.php';
$controller = new QuestionController();

if (isset($_GET['id'])) {
    $controller->deleteQuestion($_GET['id']);
    header("Location: index.php?action=discussion");
    exit;
}

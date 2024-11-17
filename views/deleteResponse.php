<?php
require_once __DIR__ . '/../controllers/ResponseController.php';
$controller = new ResponseController();

if (isset($_GET['id'])) {
    $controller->deleteResponse($_GET['id']);
    header("Location: index.php?action=discussion");
    exit;
}

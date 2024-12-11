<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'reponseController.php';

echo "Delete Response Script Called";  // Debugging

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reponse'])) {
    $id_response = intval($_POST['id_reponse']);
    $id_user = $_SESSION['user_id'] ?? null; // Ensure user is logged in and user ID is in session

    echo "ID Response: $id_response, ID User: $id_user";  // Debugging

    if ($id_user) {
        $reponseController = new ReponseController();
        if ($reponseController->isUserResponseOwner($id_response, $id_user)) {
            $reponseController->deleteResponse($id_response, $id_user);
            echo "Response deleted successfully.";
        } else {
            echo "Error: You do not have permission to delete this response.";
        }
    } else {
        echo "Error: User ID not found in session.";
    }
} else {
    echo "Invalid request.";
}



exit();



?>

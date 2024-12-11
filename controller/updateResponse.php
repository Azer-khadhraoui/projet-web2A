<?php
// controller/updateResponse.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();  // Start the session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User ID not found in session.";
    exit();
}

require_once 'reponseController.php';  // Include your response controller

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reponse']) && isset($_POST['contenue'])) {
    $id_response = intval($_POST['id_reponse']);
    $contenue = $_POST['contenue'];
    $id_user = $_SESSION['user_id'];  // Use correct session variable

    $reponseController = new ReponseController();

    if ($reponseController->isUserResponseOwner($id_response, $id_user)) {
        // If the user is the owner of the response, try to update it
        $updateSuccess = $reponseController->updateResponse($id_response, $contenue, $id_user);
        
        if ($updateSuccess) {
            echo "Response updated successfully.";  // Success message
        } else {
            echo "Error: Unable to update response in the database.";  // Error message
        }
    } else {
        echo "Error: You do not have permission to update this response.";  // Permission error message
    }
} else {
    echo "Invalid request or missing parameters.";  // Request error
}

exit();



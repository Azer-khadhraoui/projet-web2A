<?php
// controller/addresponse.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../controller/ReponseController.php';
require_once '../model/ReponseModel.php';

// Debugging: Check if session is started and user_id is set
if (!isset($_SESSION['user_id'])) {
    echo "Error: User ID is not set in session.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_quest']) && isset($_POST['contenue'])) {
        $id_quest = intval($_POST['id_quest']);
        $contenue = $_POST['contenue'];
        $id_user = $_SESSION['user_id']; // Ensure user ID is set

        // Debugging: Check if data is received correctly
        echo "ID Quest: $id_quest, Content: $contenue, User ID: $id_user";

        $reponseController = new ReponseController();
        $response = new ReponseModel($contenue, $id_quest, $id_user);
        $reponseController->addResponse($response);

        echo "Response added successfully!";
    } else {
        echo "Error: Missing data.";
    }
} else {
    echo "Error: Invalid request method.";
}

exit();



?>




<?php
// controller/addresponse.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'reponseController.php';
require_once '../Model/reponseModel.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User not logged in.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_quest']) && isset($_POST['contenue'])) {
        $id_quest = intval($_POST['id_quest']);
        $contenue = trim($_POST['contenue']);
        $id_user = $_SESSION['user_id'];

        if (empty($contenue)) {
            echo "Error: Response cannot be empty.";
            exit();
        }

        $reponseController = new ReponseController();

        // Check if the user has already posted 3 responses for this question
        $responseCount = $reponseController->countUserResponsesToQuestion($id_quest, $id_user);
        if ($responseCount >= 3) {
            echo "Error: You can only post up to 3 responses for this question.";
            exit();
        }

        // Create a new response and add it
        $response = new ReponseModel($contenue, $id_quest, $id_user);
        $responseAdded = $reponseController->addResponse($response);

        if ($responseAdded) {
            // Fetch the question owner's email
            $questionOwnerEmail = $reponseController->getQuestionOwnerEmail($id_quest);

            if ($questionOwnerEmail) {
                // Send an email notification to the question owner
                $subject = "New Response to Your Question";
                $message = "Hello,\n\nYour question has received a new response:\n\n" . $contenue . "\n\nRegards,\nYour Application Team";

                // Use PHPMailer for sending the email
                $reponseController->sendEmailNotification($questionOwnerEmail, $subject, $message);
            }

            echo "Response added successfully, and notification sent!";
        } else {
            echo "Error: Failed to add the response.";
        }
    } else {
        echo "Error: Missing question ID or response content.";
    }
} else {
    echo "Error: Invalid request method.";
}
?>

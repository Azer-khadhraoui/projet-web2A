<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/controllers/QuestionController.php';
require_once __DIR__ . '/controllers/ResponseController.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'addQuestion':
        $questionController = new QuestionController();
        $questionController->addQuestion(1, $_POST['question_text']);
        header("Location: views/discussion.php");
        exit;

    case 'addResponse':
        $responseController = new ResponseController();
        $question_id = $_GET['question_id'];
        $responseController->addResponse($question_id, 1, $_POST['response_text']);
        header("Location: views/response_dt.php?question_id=" . $question_id);
        exit;

    case 'forum':
        include 'views/forum.php';
        break;

    default:
        include 'views/discussion.php';
        break;
}
?>

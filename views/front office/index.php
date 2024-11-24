<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/QuestionController.php';
require_once __DIR__ . '/../../controllers/ResponseController.php';

// Définir l'action par défaut
$action = $_GET['action'] ?? 'forum';

// Initialiser les contrôleurs
$questionController = new QuestionController();
$responseController = new ResponseController();

// Gestion des actions
switch ($action) {
    case 'forum':
        include __DIR__ . '/forum.php'; // Inclure directement le fichier dans le même dossier
        break;

    case 'discussion':
        include __DIR__ . '/discussion.php'; // Inclure directement le fichier dans le même dossier
        break;

    case 'suggestion':
        include __DIR__ . '/suggestion.php'; // Inclure directement le fichier dans le même dossier
        break;

    case 'addQuestion':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_text'])) {
            $questionController->addQuestion(1, $_POST['question_text']);
            header("Location: index.php?action=discussion");
            exit;
        }
        break;

    case 'addSuggestion':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suggestion_text'])) {
            $questionController->addQuestion(1, $_POST['suggestion_text'], true);
            header("Location: index.php?action=suggestion");
            exit;
        }
        break;

    case 'addResponse':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['question_id'], $_POST['response_text'])) {
            $responseController->addResponse((int) $_GET['question_id'], 1, $_POST['response_text']);
            header("Location: index.php?action=discussion");
            exit;
        }
        break;

    default:
        include __DIR__ . '/forum.php'; // Inclure directement le fichier dans le même dossier
        break;
}

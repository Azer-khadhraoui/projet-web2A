<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../controller/QuestionController.php';

$controller = new QuestionController();

// Handle the update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['question_text'])) {
    try {
        $controller->updateQuestion($_POST['question_text'], $_POST['id']);

        // Redirect based on context (admin or user)
        if (isset($_GET['context']) && $_GET['context'] === 'admin') {
            header('Location: ' . BASE_URL . 'view/backoffice/bs-simple-admin/forum_admin.php');
        } else {
            header('Location: ' . BASE_URL . 'view/frontoffice/discussion.php');
        }
        exit;
    } catch (Exception $e) {
        echo "Error updating question: " . $e->getMessage();
    }
}

// Fetch the question to update
if (isset($_GET['id'])) {
    try {
        $question = $controller->getQuestionById($_GET['id']);
        if (!$question) {
            echo "No question found for ID: " . htmlspecialchars($_GET['id']);
            exit;
        }
    } catch (Exception $e) {
        echo "Error fetching question: " . $e->getMessage();
        exit;
    }
} else {
    echo "No question ID provided!";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Question</title>
</head>
<body>
    <h1>Update Question</h1>
    <form method="POST" action="?id=<?= htmlspecialchars($_GET['id']) ?>&context=<?= htmlspecialchars($_GET['context'] ?? 'user') ?>">
        <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']) ?>">
        <textarea name="question_text" required><?= htmlspecialchars($question['question_text'] ?? '') ?></textarea><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>

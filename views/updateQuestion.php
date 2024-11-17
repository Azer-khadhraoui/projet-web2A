<?php
require_once __DIR__ . '/../controllers/QuestionController.php';
require_once __DIR__ . '/../models/question.php';

$controller = new QuestionController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['question_text'])) {
    // Update the question with the provided ID and text
    $controller->updateQuestion($_POST['question_text'], $_POST['id']);
    header('Location: ../index.php?action=discussion');
    exit;
}

// Fetch the question to update
$question = $controller->getQuestionById($_GET['id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Question</title>
</head>
<body>
    <h1>Update Question</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']) ?>">
        <textarea name="question_text" required><?= htmlspecialchars($question['question_text']) ?></textarea><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>

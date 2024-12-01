<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../models/response.php';

$pdo = config::getConnexion();

// Check if a valid question ID is provided
$question_id = isset($_GET['question_id']) ? (int)$_GET['question_id'] : 0;
if ($question_id === 0) {
    header("Location: discussion.php"); // Redirect to discussion if no valid question ID
    exit;
}

// Handle response submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['response_text'])) {
    $response = new Response($question_id, 1, $_POST['response_text']); // Assuming responder ID is 1
    $response->save($pdo);
    header("Location: response_dt.php?question_id=$question_id"); // Redirect to prevent form resubmission
    exit;
}

// Fetch all responses for the current question
$responses = Response::getAllForQuestion($pdo, $question_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Responses</title>
    <link rel="stylesheet" href="../../css/forum.css">
    <link rel="stylesheet" href="../../css/discussion.css">
</head>

<body>
    <header>
        <h1>Responses for Question #<?= htmlspecialchars($question_id) ?></h1>
    </header>
    <main>
        <form method="POST" action="response_dt.php?question_id=<?= htmlspecialchars($question_id) ?>">
            <textarea name="response_text" rows="3" cols="50" placeholder="Type your response here..." required></textarea><br>
            <button type="submit">Submit Response</button>
        </form>
        <h2>All Responses</h2>
        <ul>
            <?php foreach ($responses as $r): ?>
                <li>
                    <?= htmlspecialchars($r['response_text']) ?>
                    <em>(Responded on: <?= $r['created_at'] ?>)</em>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="discussion.php">Back to Questions</a>
    </main>
</body>
</html>
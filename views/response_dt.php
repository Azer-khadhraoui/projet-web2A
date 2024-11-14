<?php
include 'config.php';
include 'Response.php';

$pdo = config::getConnexion();
$question_id = isset($_GET['question_id']) ? (int)$_GET['question_id'] : 0;

if ($question_id === 0) {
    header("Location: discussion.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['response_text'])) {
    $response = new Response($question_id, 1, $_POST['response_text']); // Assuming responder ID is 1
    $response->save($pdo);
    header("Location: response_dt.php?question_id=$question_id");
    exit;
}

$responses = Response::getAllForQuestion($pdo, $question_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Responses</title>
</head>
<body>
    <h1>Responses for Question #<?= $question_id ?></h1>
    <form method="POST" action="response_dt.php?question_id=<?= $question_id ?>">
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
</body>
</html>

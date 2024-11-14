<?php
include 'config.php';
include 'Question.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_text'])) {
    $pdo = config::getConnexion();
    $question = new Question(1, $_POST['question_text']); // Example: ID of client is 1

    $question->save($pdo);
    header("Location: discussion.php");
    exit;
}

$pdo = config::getConnexion();
$questions = Question::getAll($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Partie Discussion</title>
    <link rel="stylesheet" href="forum.css">
</head>
<body>
    <header>
        <h1>Forum - Discussion</h1>
    </header>

    <main class="discussion-main">
        <h2>Poser une Question</h2>
        <form method="POST" action="discussion.php">
            <textarea name="question_text" rows="3" cols="50" placeholder="Tapez votre question ici..." required></textarea><br>
            <button type="submit">Soumettre la Question</button>
        </form>

        <h2>Liste des Questions</h2>
        <ul>
            <?php foreach ($questions as $q): ?>
                <li>
                    <strong>Question #<?= $q['id_question'] ?>:</strong> <?= htmlspecialchars($q['question_text']) ?>
                    <em>(Posté le : <?= $q['created_at'] ?>)</em>
                    <a href="response_dt.php?question_id=<?= $q['id_question'] ?>">Répondre</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>

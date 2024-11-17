<?php
require_once __DIR__ . '/../controllers/ResponseController.php';
require_once __DIR__ . '/../models/response.php';

$controller = new ResponseController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['response_text'])) {
    // Pass correct arguments to updateResponse
    $controller->updateResponse($_POST['response_text'], $_POST['id']);
    header('Location: ../index.php?action=discussion');
    exit;
}

// Fetch the response to update
$response = $controller->getResponseById($_GET['id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Response</title>
</head>
<body>
    <h1>Update Response</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']) ?>">
        <textarea name="response_text" required><?= htmlspecialchars($response['response_text']) ?></textarea><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>

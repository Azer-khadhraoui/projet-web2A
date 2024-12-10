<?php
require_once __DIR__ . '/../../../controller/ResponseController.php';

$controller = new ResponseController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['response_text'])) {
    try {
        $controller->updateResponse($_POST['response_text'], $_POST['id']);
        
        // Redirect based on context (admin or user)
        if (isset($_GET['context']) && $_GET['context'] === 'admin') {
            header('Location: ../forum_admin.php'); // Back Office
        } else {
            header('Location: ../../frontoffice/discussion.php'); // Front Office
        }
        exit;
    } catch (Exception $e) {
        echo "Error updating response: " . $e->getMessage();
    }
}

// Fetch the response to update
if (isset($_GET['id'])) {
    try {
        $response = $controller->getResponseById($_GET['id']);
        if (!$response) {
            echo "No response found for ID: " . htmlspecialchars($_GET['id']);
            exit;
        }
    } catch (Exception $e) {
        echo "Error fetching response: " . $e->getMessage();
        exit;
    }
} else {
    echo "No response ID provided!";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Response</title>
</head>
<body>
    <h1>Update Response</h1>
    <form method="POST" action="?id=<?= htmlspecialchars($_GET['id']) ?>&context=<?= htmlspecialchars($_GET['context'] ?? 'user') ?>">
        <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']) ?>">
        <textarea name="response_text" required><?= htmlspecialchars($response['response_text'] ?? '') ?></textarea><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>

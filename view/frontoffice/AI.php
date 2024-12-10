<?php
$aiResponse = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category'])) {
    $category = $_POST['category'];
    $url = 'http://localhost:3000/generate';
    $data = json_encode(['category' => $category]);

    // Initialize cURL session
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    // Execute cURL request
    $aiResponse = curl_exec($ch);

    if (curl_errno($ch)) {
        // If cURL fails, show the error
        $aiResponse = 'Error: ' . curl_error($ch);
    } else {
        // Check if the response is a valid JSON
        $responseData = json_decode($aiResponse, true);
        if (isset($responseData['text'])) {
            $aiResponse = $responseData['text'];
        } else if (isset($responseData['error'])) {
            // If there's an error from the API, display it
            $aiResponse = 'Error from AI: ' . htmlspecialchars($responseData['error']);
        } else {
            // If the response format is invalid
            $aiResponse = 'Error: Invalid response format';
        }
    }

    // Close the cURL session
    curl_close($ch);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/AI.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/background-animation.css">
    <title>Partie AI - Suggestions pour votre culture</title>
</head>
<body>
    <h1>Partie AI - Suggestions pour votre culture</h1>
    <form method="POST" action="">
        <select name="category">
            <option value="potatoes">Pommes de Terre</option>
            <option value="tomatoes">Tomates</option>
            <option value="carrots">Carottes</option>
            <option value="carrots">Onions</option>
            <option value="carrots">Lettuce</option>
        </select>
        <button type="submit">GENERATE</button>
    </form>

    <?php if (!empty($aiResponse)): ?>
        <h2>Conseils pour <?= htmlspecialchars($category) ?>:</h2>
        <p><?= htmlspecialchars($aiResponse) ?></p>
    <?php endif; ?>
</body>
</html>

<?php
$aiResponse = "";  // Variable to store the AI response

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category'])) {
    $category = $_POST['category']; // Get the selected category from the form

    // Send the category to the Node.js server using cURL (POST request)
    $url = 'http://localhost:3000/generate'; // Your Node.js server URL

    // Create the payload (category)
    $data = json_encode(['category' => $category]);

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response as string
    curl_setopt($ch, CURLOPT_POST, true);  // Use POST method
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  // Send the data (category)
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'  // Set content type to JSON
    ]);

    error_log("Sending data: " . $data);
    // Execute the request and get the response
    $aiResponse = curl_exec($ch);
    error_log("Raw Response: " . $aiResponse);  // This will log the response from Node.js


    // Check for errors
    if (curl_errno($ch)) {
        $aiResponse = 'Error: ' . curl_error($ch);
    } else {
        // Decode and check if the response is valid JSON
        $responseData = json_decode($aiResponse, true);
        if (isset($responseData['text'])) {
            $aiResponse = $responseData['text'];  // Get AI's response text
        } else {
            $aiResponse = 'Error: Invalid response from AI';
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
    <title>Partie AI - Suggestions pour votre culture</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/forum.css">
</head>
<body>
    <header class="forum-header">
        
        <div class="header-text">
            <h1>Partie AI - Suggestions pour votre culture</h1>
            <p>Choisissez une catégorie pour recevoir des conseils sur la plantation :</p>
        </div>
    </header>
    
    <main class="forum-main">
        <div class="forum-options">
            <form method="POST" action="">
                <select name="category" class="ai-category-select">
                    <option value="potatoes">Pommes de Terre</option>
                    <option value="tomatoes">Tomates</option>
                    <option value="carrots">Carottes</option>
                </select>
                  <button type="submit" class="ai-submit-btn" id="submitButton">Recevoir des conseils</button>
            </form>
            <div id="loading" style="display:none;">Chargement...</div>

            <script>
                const form = document.querySelector('form');
                const loading = document.getElementById('loading');
                const submitButton = document.getElementById('submitButton');

                 form.addEventListener('submit', function () {
                     submitButton.disabled = true;  // Disable button
                     loading.style.display = 'block';  // Show loading
                    });
<           /script>

            <?php if (!empty($aiResponse)): ?>
                <div class="ai-response">
                    <h2>Conseils pour <?= htmlspecialchars($category) ?>:</h2>
                    <p><?= htmlspecialchars($aiResponse) ?></p>
                    <!-- Display the AI response here -->
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

<?php
session_start();
require_once '../controller/QuestionController.php';
require_once '../controller/ReponseController.php';

$questionController = new QuestionController();
$reponseController = new ReponseController();
$questions = $questionController->listPublicationsSortedByTitle('ASC');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Question Interface</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #28a745;
            padding: 20px;
            border-radius: 8px;
            margin-right: 20px;
            color: #fff;
        }
        .sidebar h2 {
            margin-top: 0;
            text-align: center;
        }
        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            margin: 10px 0;
            padding: 10px;
            background-color: #218838;
            border-radius: 4px;
            text-align: center;
        }
        .sidebar a:hover {
            background-color: #1e7e34;
        }
        .main-content {
            flex-grow: 1;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .question-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .question-card h2 {
            margin-top: 0;
            color: #28a745;
        }
        .question-card p {
            color: #333;
        }
        .response {
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 4px;
            padding: 10px;
            margin-top: 10px;
        }
        .response p {
            margin: 0;
        }
        .no-questions {
            text-align: center;
            color: #777;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Actions</h2>
            <a href="searchquestion.php">Search</a>
            <a href="addquestion.php">Add Question</a>
            <a href="listquestions.php">Delete Question</a>
            <a href="update.php">Update Question</a>
        </div>
        <div class="main-content">
            <h1>Questions Added by Clients</h1>
            <?php if (count($questions) > 0): ?>
                <?php foreach ($questions as $question): ?>
                <div class="question-card">
                    <h2><?php echo htmlspecialchars($question['titre_quest']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($question['contenue'])); ?></p>
                    <div id="responses-<?php echo $question['id_quest']; ?>">
                        <?php 
                        $responses = $reponseController->getResponsesByQuestionId($question['id_quest']);
                        foreach ($responses as $response): ?>
                            <div class="response" id="response-<?php echo $response['id']; ?>">
                                <p class="response-content"><strong>Response:</strong> <?php echo htmlspecialchars($response['contenue']); ?></p>
                                <?php if ($response['id_user'] === $_SESSION['user_id']): ?>
                                    <button onclick="deleteResponse(<?php echo $response['id']; ?>)">Delete</button>
                                    <button onclick="showUpdateForm(<?php echo $response['id']; ?>, `<?php echo htmlspecialchars($response['contenue']); ?>`)">Update</button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <textarea id="response-text-<?php echo $question['id_quest']; ?>" rows="2" placeholder="Write your response..." style="width: 100%; margin-top: 10px;"></textarea>
                    <button onclick="addResponse(<?php echo $question['id_quest']; ?>)" style="margin-top: 10px;">Submit Response</button>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-questions">No questions found.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function addResponse(questionId) {
            var responseText = document.getElementById('response-text-' + questionId).value;
            console.log("Response text: " + responseText);  // Debugging

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../controller/addresponse.php", true);  // Ensure the path is correct
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    console.log("AJAX call made, state: " + xhr.readyState);  // Debugging
                    if (xhr.status === 200) {
                        console.log("Response from server: " + xhr.responseText);  // Debugging
                        var responseDiv = document.createElement('div');
                        responseDiv.className = 'response';
                        responseDiv.innerHTML = '<p><strong>Response:</strong> ' + responseText + '</p>';
                        document.getElementById('responses-' + questionId).appendChild(responseDiv);
                        document.getElementById('response-text-' + questionId).value = '';
                    } else {
                        console.log("Error: " + xhr.status);  // Debugging
                    }
                }
            };
            console.log("Sending data: id_quest=" + questionId + "&contenue=" + encodeURIComponent(responseText));  // Debugging
            xhr.send("id_quest=" + questionId + "&contenue=" + encodeURIComponent(responseText));
        }

        function deleteResponse(id) {
            if (confirm("Are you sure you want to delete this response?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../controller/deleteResponse.php", true);  // Ensure the path is correct
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(xhr.responseText);
                        location.reload();
                    }
                };
                xhr.send("id=" + id);
            }
        }

        function showUpdateForm(id_reponse, content) {
            console.log("Update button clicked, id_reponse: " + id_reponse + ", content: " + content);  // Debugging
            var newContent = prompt("Update your response:", content);
            if (newContent !== null) {
                console.log("New content: " + newContent);  // Debugging
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "controller/updateResponse.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        console.log("AJAX call made, state: " + xhr.readyState);  // Debugging
                        if (xhr.status === 200) {
                            console.log("Response from server: " + xhr.responseText);  // Debugging
                            alert(xhr.responseText);
                            if (xhr.responseText.includes("Response updated successfully")) {
                                var responseElement = document.getElementById('response-' + id_reponse);
                                if (responseElement) {
                                    responseElement.querySelector('.response-content').innerText = newContent;
                                }
                            }
                        }
                    } else {
                        console.log("Error: " + xhr.status);  // Debugging
                    }
                };
                console.log(`Sending data: id_reponse=${id_reponse}&contenue=${encodeURIComponent(newContent)}`);  // Debugging
                xhr.send(`id_reponse=${id_reponse}&contenue=${encodeURIComponent(newContent)}`);
            }
        }
    </script>
</body>
</html>
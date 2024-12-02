<?php
session_start();
require_once '../controller/QuestionController.php';
require_once '../controller/ReponseController.php';

$questionController = new QuestionController();
$reponseController = new ReponseController();
$questions = $questionController->listPublicationsSortedByTitle('ASC');


$totalQuestions = $questionController->countQuestions();
$totalResponses = $reponseController->countResponses();
$avgResponses = $totalResponses > 0 ? round($totalResponses / $totalQuestions, 2) : 0;

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
}

/* Light Theme */
body.light {
    background-color: #f4f4f4;
    color: #333; /* Default text color for light theme */
}

/* Dark Theme */
body.dark {
    background-color: #121212;
    color: #f4f4f4; /* Lighter text color for dark theme */
}

/* Container */
.container {
    max-width: 100%;
    margin: 0 auto;
    padding: 20px;
    border-radius: 8px;
    display: flex;
}

/* Sidebar Styles */
body.light .sidebar {
    width: 250px;
    background-color: #28a745; /* Green background for light mode */
    color: #fff;
    padding: 20px;
    border-radius: 8px;
    margin-right: 20px;
}

body.light .sidebar a {
    display: block;
    color: #fff;
    text-decoration: none;
    margin: 10px 0;
    padding: 10px;
    background-color: #218838;
    border-radius: 4px;
    text-align: center;
}

body.light .sidebar a:hover {
    background-color: #1e7e34;
}

/* Dark Mode Sidebar Styles */
body.dark .sidebar {
    width: 250px;
    background-color: #1c1c1c; /* Dark gray background for dark mode */
    color: #f4f4f4;
    padding: 20px;
    border-radius: 8px;
    margin-right: 20px;
}

body.dark .sidebar a {
    display: block;
    color: #f4f4f4;
    text-decoration: none;
    margin: 10px 0;
    padding: 10px;
    background-color: #333; /* Dark background for links in dark mode */
    border-radius: 4px;
    text-align: center;
}

body.dark .sidebar a:hover {
    background-color: #444; /* Darker background for hover state */
}

/* Main Content */
.main-content {
    flex-grow: 1;
}

/* Question Card Styles */
.question-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Light Theme for Content */
body.light .main-content {
    background-color: #fff;
}

body.light .question-card {
    background-color: #fff;
    border: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

body.light .response {
    background-color: #f9f9f9;
    border: 1px solid #eee;
}

body.light .question-card h2,
body.light .question-card p {
    color: #333;
}

/* Dark Theme for Content */
body.dark .main-content {
    background-color: #1c1c1c;
}

body.dark .question-card {
    background-color: #2c2c2c;
    border: 1px solid #444;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

body.dark .response {
    background-color: #333;
    border: 1px solid #555;
}

body.dark .question-card h2,
body.dark .question-card p {
    color: #f4f4f4; /* Lighter text color in dark mode */
}

/* Response Styles */
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

/* No Questions Found Message */
.no-questions {
    text-align: center;
    color: #777;
    font-size: 18px;
    margin-top: 20px;
}

/* Ensure content text adapts to theme */
.main-content, 
.question-card, 
.question-card p, 
.question-card h2, 
.response, 
.response p {
    color: inherit; /* Inherit text color from the body */
}

/* Additional Style for Button Text */
button {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #218838;
}
</style>

</head>
<body>
    <div class="container">
        <div class="sidebar">
                <h2>Actions</h2>
                <button onclick="toggleTheme()" style="margin: 10px 0; padding: 10px; background-color: #fff; color: #000; border: none; border-radius: 4px; cursor: pointer;">Toggle Theme</button>
                <p><strong>Statistics:</strong></p>
                <p>Total Questions: <?php echo $totalQuestions; ?></p>
                <p>Total Responses: <?php echo $totalResponses; ?></p>
                <p>Average Responses per Question: <?php echo $avgResponses; ?></p>
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
                            <div class="response" id="response-<?php echo $response['id_reponse']; ?>">
                                <p class="response-content"><strong>Response:</strong> <?php echo htmlspecialchars($response['contenue']); ?></p>
                                <?php if ($response['id_user'] === $_SESSION['user_id']): ?>
                                    <button onclick="deleteResponse(<?php echo $response['id_reponse']; ?>)">Delete</button>
                                    <button onclick="showUpdateForm(<?php echo $response['id_reponse']; ?>, `<?php echo htmlspecialchars($response['contenue']); ?>`)">Update</button>
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
                xhr.send("id_reponse=" + id);
            }
        }

        function showUpdateForm(id_reponse, content) {
            console.log("Update button clicked, id_reponse: " + id_reponse + ", content: " + content);  // Debugging
            var newContent = prompt("Update your response:", content);
    
            if (newContent !== null) {
                console.log("New content: " + newContent);  // Debugging
        
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../controller/updateResponse.php", true);  // Ensure correct path
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        console.log("AJAX call made, state: " + xhr.readyState);  // Debugging
                        if (xhr.status === 200) {
                            console.log("Response from server: " + xhr.responseText);  // Debugging
                            alert(xhr.responseText);  // Display server response
                    
                            // Only update the UI if the response was successful
                            if (xhr.responseText === "Response updated successfully.") {
                        // Find the response element by ID and update it
                                var responseElement = document.querySelector(`#response-${id_reponse}`);
                                if (responseElement) {
                                    responseElement.querySelector('p').innerHTML = '<strong>Response:</strong> ' + newContent;
                                }
                            }
                        } else {
                            console.log("Error: " + xhr.status);  // Debugging
                        }
                    }
                };
        
        // Sending data to the server
                console.log("Sending data: id_reponse=" + id_reponse + "&contenue=" + encodeURIComponent(newContent));  // Debugging
                xhr.send("id_reponse=" + id_reponse + "&contenue=" + encodeURIComponent(newContent));
            }
        }

        // Apply saved theme on load
        document.addEventListener('DOMContentLoaded', function () {
            const savedTheme = localStorage.getItem('theme') || 'light'; // Default to light theme
            document.body.classList.add(savedTheme);
        });

        // Theme toggle function
        function toggleTheme() {
            const currentTheme = document.body.classList.contains('light') ? 'light' : 'dark';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            document.body.classList.remove(currentTheme);
            document.body.classList.add(newTheme);
            localStorage.setItem('theme', newTheme); // Save theme preference
        }




    </script>
</body>
</html>
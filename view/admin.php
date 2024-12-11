<?php
session_start();
require_once '../controller/QuestionControlleradem.php';
require_once '../controller/ReponseController.php';

$questionController = new QuestionController();
$reponseController = new ReponseController();

$deletedCount = $reponseController->deleteResponsesWithBadWords(); // Bad words filtering
if ($deletedCount > 0) {
    echo "<script>console.log('Deleted $deletedCount responses containing bad words.');</script>";
}

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
    <title>Admin Question Interface</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: space-between;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            width: 100%;
        }

        .sidebar {
            width: 20%;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            box-sizing: border-box;
            height: 100vh;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            color: #ecf0f1;
        }

        .sidebar a {
            display: block;
            margin: 10px 0;
            color: #ecf0f1;
            text-decoration: none;
        }

        .sidebar a:hover {
            color: #f39c12;
        }

        .main-content {
            width: 75%;
            padding: 20px;
            box-sizing: border-box;
        }

        .question-card {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .question-card h2 {
            font-size: 24px;
            color: #34495e;
        }

        .question-card p {
            font-size: 16px;
            color: #7f8c8d;
        }

        .response {
            background-color: #f9f9f9;
            padding: 15px;
            margin-top: 10px;
            border-left: 5px solid #3498db;
            border-radius: 5px;
            font-size: 16px;
        }

        .response-content {
            color: #34495e;
        }

        .response button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .response button:hover {
            background-color: #2980b9;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .no-questions {
            color: #7f8c8d;
            font-size: 18px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">

            <p><strong>Statistics:</strong></p>
            <p>Total Questions: <?php echo $totalQuestions; ?></p>
            <p>Total Responses: <?php echo $totalResponses; ?></p>
            <p>Average Responses per Question: <?php echo $avgResponses; ?></p>
        </div>

        <div class="main-content">
            <h1>Questions Added by Clients</h1>

            <!-- Button to add a new question -->
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <button onclick="showAddQuestionForm()">Add New Question</button>
            <?php endif; ?>

            <?php if (count($questions) > 0): ?>
                <?php foreach ($questions as $question): ?>
                    <div class="question-card" id="question-<?php echo $question['id_quest']; ?>">
                        <h2><?php echo htmlspecialchars($question['titre_quest']); ?></h2>
                        <p><?php echo nl2br(htmlspecialchars($question['contenue'])); ?></p>

                        <!-- Admin Actions for Question (Delete/Update) -->
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                            <button onclick="deleteQuestion(<?php echo $question['id_quest']; ?>)">Delete Question</button>

                            <button
                                onclick="showUpdateQuestionForm(<?php echo $question['id_quest']; ?>, '<?php echo htmlspecialchars($question['titre_quest']); ?>', '<?php echo htmlspecialchars($question['contenue']); ?>')">Update
                                Question</button>

                        <?php endif; ?>

                        <div id="responses-<?php echo $question['id_quest']; ?>">
                            <?php
                            $responses = $reponseController->getResponsesByQuestionId($question['id_quest']);
                            foreach ($responses as $response): ?>
                                <div class="response" id="response-<?php echo $response['id_reponse']; ?>">
                                    <p class="response-content"><strong>Response:</strong>
                                        <?php echo htmlspecialchars($response['contenue']); ?></p>

                                    <!-- Admin can delete or update any response -->
                                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                        <button onclick="deleteResponse(<?php echo $response['id_reponse']; ?>)">Delete</button>
                                        <button
                                            onclick="showUpdateForm(<?php echo $response['id_reponse']; ?>, `<?php echo htmlspecialchars($response['contenue']); ?>`)">Update</button>
                                    <?php endif; ?>

                                </div>
                            <?php endforeach; ?>
                        </div>

                        <textarea id="response-text-<?php echo $question['id_quest']; ?>" rows="2"
                            placeholder="Write your response..." style="width: 100%; margin-top: 10px;"></textarea>
                        <button onclick="addResponse(<?php echo $question['id_quest']; ?>)" style="margin-top: 10px;">Submit
                            Response</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-questions">No questions found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>

        function deleteQuestion(questionId) {
            if (confirm("Are you sure you want to delete this question?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../controller/deleteQuestion.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(xhr.responseText);
                        location.reload();
                    }
                };
                xhr.send("id_quest=" + questionId);
            }
        }

        function showUpdateQuestionForm(questionId, title, content) {
            var newTitle = prompt("Update the question title:", title);
            var newContent = prompt("Update the question content:", content);

            if (newTitle !== null && newContent !== null) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../controller/updateQuestion.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            alert(xhr.responseText);
                            if (xhr.responseText === "Question updated successfully.") {
                                var questionElement = document.querySelector(`#question-${questionId}`);
                                if (questionElement) {
                                    questionElement.querySelector('h2').textContent = newTitle;
                                    questionElement.querySelector('p').textContent = newContent;
                                }
                            }
                        } else {
                            console.log("Error: " + xhr.status);
                        }
                    }
                };

                xhr.send("id_quest=" + questionId + "&titre_quest=" + encodeURIComponent(newTitle) + "&contenue=" + encodeURIComponent(newContent));
            }
        }


        function addResponse(questionId) {
            var responseText = document.getElementById('response-text-' + questionId).value;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../controller/addresponse.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var responseDiv = document.createElement('div');
                        responseDiv.className = 'response';
                        responseDiv.innerHTML = '<p><strong>Response:</strong> ' + responseText + '</p>';
                        document.getElementById('responses-' + questionId).appendChild(responseDiv);
                        document.getElementById('response-text-' + questionId).value = '';
                    } else {
                        console.log("Error: " + xhr.status);
                    }
                }
            };
            xhr.send("id_quest=" + questionId + "&contenue=" + encodeURIComponent(responseText));
        }

        function deleteResponse(id) {
            if (confirm("Are you sure you want to delete this response?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../controller/deleteResponse.php", true);
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

        function showAddQuestionForm() {
            // Prompt user for title and content
            var newTitle = prompt("Enter the question title:");
            var newContent = prompt("Enter the question content:");

            if (newTitle && newContent) {
                // Send data to the server to save the new question
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../controller/addQuestion.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(xhr.responseText);
                        location.reload(); // Reload the page to show the new question
                    }
                };

                xhr.send("titre_quest=" + encodeURIComponent(newTitle) + "&contenue=" + encodeURIComponent(newContent));
            }
        }

    </script>
</body>

</html>
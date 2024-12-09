<?php
session_start(); // Start the session to access session variables

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config.php';
require_once '../model/QuestionModel.php';
require_once '../controller/QuestionController.php';

$error = "";
$success_message = "";

// Check if the user is logged in and retrieve the user ID from the session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (isset($_POST["titre_quest"]) && isset($_POST["contenue"]) && $user_id !== null) {
    if (!empty($_POST["titre_quest"]) && !empty($_POST["contenue"])) {
        try {
            // Create an instance of the QuestionModel
            $question = new QuestionModel($_POST['titre_quest'], $_POST['contenue'], (int)$user_id);
            $questionController = new QuestionController();
            $questionController->addQuestion($question);
            $success_message = "Question added successfully!";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Questions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .question-form {
            margin-top: 20px;
        }

        .question-form input,
        .question-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .question-form button {
            background-color: #28a745;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .question-form button:hover {
            background-color: #218838;
        }

        .sidebar {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .sidebar a {
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            margin: 0 5px;
        }
        .sidebar a:hover {
            background-color: #218838;
        }
        
        /* Add styles for the voice input buttons */
        #voice-buttons {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        #start-voice-input, #stop-voice-input {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 5px;
        }

        #stop-voice-input {
            display: none;
        }

        #start-voice-input:hover, #stop-voice-input:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add a New Question</h1>
        <?php if (!empty($error)) { echo '<div style="color: red;">' . $error . '</div>'; } ?>
        <?php if (!empty($success_message)) { echo '<div style="color: green;">' . $success_message . '</div>'; } ?>
        <form class="question-form" action="" method="post">
            <input type="text" name="titre_quest" placeholder="Question Title" required>
            <textarea id="contenue" name="contenue" placeholder="Your Question Content" rows="5" required></textarea>
            
            <!-- Hidden field for user ID, automatically filled from session -->
            <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($user_id); ?>">
            
            <button type="submit">Submit</button>
        </form>
        <div class="sidebar">
            <a href="ind.php" class="return-button">Return</a>
        </div>

        <!-- Voice input buttons -->
        <div id="voice-buttons">
            <button id="start-voice-input">Start Voice Input</button>
            <button id="stop-voice-input">Stop Voice Input</button>
        </div>
    </div>

    <script>
        // Get references to the title input field and buttons
        const titleInput = document.querySelector('input[name="titre_quest"]'); // The title input field
        const startButton = document.getElementById('start-voice-input');
        const stopButton = document.getElementById('stop-voice-input');

        // Speech Recognition API setup
        if ('webkitSpeechRecognition' in window) {
            let recognition = new webkitSpeechRecognition();
            recognition.continuous = true;  // Keep listening until stopped
            recognition.lang = 'en-US';  // Set language to English
            recognition.interimResults = true;  // Allow partial results
            recognition.maxAlternatives = 3;  // Allow multiple alternatives

            // Start voice input on button click
            startButton.addEventListener('click', function() {
                recognition.start();  // Start speech recognition
                titleInput.value = "This is recorded chat";  // Set the title of the question
                startButton.style.display = 'none';
                stopButton.style.display = 'inline-block';  // Show stop button
            });

            // Handle speech input result
            recognition.onresult = function(event) {
                var transcript = event.results[0][0].transcript;
                document.getElementById('contenue').value = transcript;  // Put the result in the textarea
            };

            // Stop voice input on button click
            stopButton.addEventListener('click', function() {
                recognition.stop();  // Stop recognition
            });

            // Handle speech recognition errors
            recognition.onerror = function(event) {
                if (event.error === 'no-speech') {
                    alert("No speech detected. Please try again.");
                } else {
                    alert("Speech recognition error: " + event.error);
                }
            };

            // Handle the end of the recognition process
            recognition.onend = function() {
                document.getElementById('start-voice-input').style.display = 'inline-block';
                document.getElementById('stop-voice-input').style.display = 'none';
            };
        } else {
            alert("Speech Recognition API is not supported in your browser.");
        }
    </script>
</body>
</html>










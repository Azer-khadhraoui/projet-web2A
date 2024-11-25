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
    </style>
</head>
<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once '../config.php';
    require_once '../model/QuestionModel.php';
    require_once '../controller/QuestionController.php';
    
    $error = "";
    $success_message = "";

    if (isset($_POST["titre_quest"]) && isset($_POST["contenue"]) && isset($_POST["id_user"])) {
        if (!empty($_POST["titre_quest"]) && !empty($_POST["contenue"]) && !empty($_POST["id_user"])) {
            try {
                // Create an instance of the QuestionModel
                $question = new QuestionModel($_POST['titre_quest'], $_POST['contenue'], (int)$_POST['id_user']);
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
    <div class="container">
        <h1>Add a New Question</h1>
        <?php if (!empty($error)) { echo '<div style="color: red;">' . $error . '</div>'; } ?>
        <?php if (!empty($success_message)) { echo '<div style="color: green;">' . $success_message . '</div>'; } ?>
        <form class="question-form" action="" method="post">
            <input type="text" name="titre_quest" placeholder="Question Title" required>
            <textarea name="contenue" placeholder="Your Question Content" rows="5" required></textarea>
            <input type="number" name="id_user" placeholder="User ID" required>
            <button type="submit">Submit</button>
        </form>
        <div class="sidebar">
            <a href="ind.php" class="return-button">Return</a>
        </div>
    </div>
</body>
</html>

<?php
session_start(); // Start the session to access session variables

require_once '../controller/QuestionControlleradem.php';

$error = "";
$success_message = "";
$questionController = new QuestionController();
$questions = [];

// Get the user ID from the session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch the user's questions
if ($user_id !== null) {
    $questions = $questionController->getQuestionsByUserId($user_id);
}

// Delete a question
if (isset($_POST['delete_id'])) {
    if ($user_id !== null) {
        $questionController->deleteQuestion($_POST['delete_id']);
        $success_message = "Question deleted successfully!";
        header("Location: listquestions.php"); // Refresh the page
        exit();
    } else {
        $error = "You must be logged in to delete a question.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Your Question</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #28a745;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .delete-button {
            background-color: #dc3545;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #c82333;
        }
        .no-questions {
            text-align: center;
            color: #777;
            font-size: 18px;
            margin-top: 20px;
        }
        .return-button {
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            margin-left: 10px;
        }
        .return-button:hover {
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
    <div class="container">
        <h1>Delete Your Question</h1>

        <!-- Show success or error message -->
        <?php if (!empty($success_message)) { echo '<div style="color: green;">' . $success_message . '</div>'; } ?>
        <?php if (!empty($error)) { echo '<div style="color: red;">' . $error . '</div>'; } ?>

        <?php if (count($questions) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $question): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($question['id_quest']); ?></td>
                        <td><?php echo htmlspecialchars($question['titre_quest']); ?></td>
                        <td><?php echo htmlspecialchars($question['contenue']); ?></td>
                        <td><?php echo htmlspecialchars($question['date']); ?></td>
                        <td>
                            <form action="listquestions.php" method="post" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?php echo $question['id_quest']; ?>">
                                <button type="submit" class="delete-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-questions">No questions found for this user.</p>
        <?php endif; ?>
        
        <div class="sidebar">
            <a href="ind.php" class="return-button">Return</a>
        </div>
    </div>
</body>
</html>


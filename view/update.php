<?php
session_start(); // Start the session to access session variables

require_once '../controller/QuestionController.php';

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

// Update a question's content
if (isset($_POST['update_id']) && isset($_POST['new_content'])) {
    if ($user_id !== null) {
        $questionController->updateQuestion($_POST['update_id'], $_POST['new_content']);
        $success_message = "Question updated successfully!";
        header("Location: update.php"); // Refresh the page to reflect the update
        exit();
    } else {
        $error = "You must be logged in to update a question.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Questions</title>
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
        .update-button {
            background-color: #17a2b8;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .update-button:hover {
            background-color: #138496;
        }
        .no-questions {
            text-align: center;
            color: #777;
            font-size: 18px;
            margin-top: 20px;
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
        <h1>Update Your Question</h1>

        <!-- Show success or error message -->
        <?php if (!empty($success_message)) { echo '<div style="color: green;">' . $success_message . '</div>'; } ?>
        <?php if (!empty($error)) { echo '<div style="color: red;">' . $error . '</div>'; } ?>

        <?php if (count($questions) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $question): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($question['titre_quest']); ?></td>
                        <td>
                            <form action="update.php" method="post" style="display:inline;">
                                <input type="hidden" name="update_id" value="<?php echo $question['id_quest']; ?>">
                                <input type="text" name="new_content" value="<?php echo htmlspecialchars($question['contenue']); ?>" required>
                                <button type="submit" class="update-button">Update</button>
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



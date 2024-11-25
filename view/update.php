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
        .filter-form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .filter-form label {
            margin-right: 10px;
        }
        .filter-form input,
        .filter-form button {
            padding: 10px;
            margin: 0 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .filter-form button {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .filter-form button:hover {
            background-color: #218838;
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
    <?php
    require_once '../controller/QuestionController.php';
    $questionController = new QuestionController();
    $questions = [];

    if (isset($_POST['filter_id'])) {
        $id_user = (int)$_POST['filter_id'];
        $questions = $questionController->getQuestionsByUserId($id_user);
    }

    if (isset($_POST['update_id'])) {
        $questionController->updateQuestionContent($_POST['update_id'], $_POST['new_content']);
        header("Location: update.php"); // Refresh the page
        exit();
    }
    ?>
    <div class="container">
        <h1>Update Your Question</h1>
        <form class="filter-form" action="update.php" method="post">
            <label for="filter_id">Client ID:</label>
            <input type="number" name="filter_id" id="filter_id" required>
            <button type="submit">Filter</button>
        </form>
        <?php if (count($questions) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Content</th>
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
            <p class="no-questions">No questions found for this client ID.</p>
        <?php endif; ?>
        <div class="sidebar">
            <a href="ind.php" class="return-button">Return</a>
        </div>
    </div>
</body>
</html>



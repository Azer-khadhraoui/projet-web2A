<?php
session_start();
require_once '../controller/QuestionController.php';

$questionController = new QuestionController();
$questions = [];

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $questions = $questionController->searchquestion($search);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Questions</title>
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
            position: relative;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .search-form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .search-form input,
        .search-form button {
            padding: 10px;
            margin: 0 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-form button {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .search-form button:hover {
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
        .no-results {
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
            position: absolute;
            bottom: 20px;
            right: 20px;
        }
        .return-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Search Questions</h1>
        <form class="search-form" action="searchquestion.php" method="post">
            <input type="text" name="search" placeholder="Enter keywords" required>
            <button type="submit">Search</button>
        </form>

        <?php if (isset($_POST['search'])): ?>
            <?php if (count($questions) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Content</th>
                            <th>User ID</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $question): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($question['titre_quest']); ?></td>
                            <td><?php echo htmlspecialchars($question['contenue']); ?></td>
                            <td><?php echo htmlspecialchars($question['id_user']); ?></td>
                            <td><?php echo htmlspecialchars($question['date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-results">No questions found matching your keywords.</p>
            <?php endif; ?>
        <?php endif; ?>
        <div class="sidebar">
            <a href="ind.php" class="return-button">Return</a>
        </div>
    </div>
</body>
</html>


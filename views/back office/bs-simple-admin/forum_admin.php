<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../controllers/QuestionController.php';
require_once __DIR__ . '/../../../controllers/ResponseController.php';

// Initialize controllers
$questionController = new QuestionController();
$responseController = new ResponseController();

// Fetch questions and responses
$questions = $questionController->getAllQuestions();
$responses = $responseController->getAllResponses();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forum Management</title>
    <!-- BOOTSTRAP STYLES -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES -->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
</head>
<body>
    <div id="wrapper">
        <!-- NAVBAR -->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <a class="navbar-brand" href="#">
                    <img src="assets/img/logo.png" alt="Logo" />
                </a>
                <span class="logout-spn">
                    <a href="#" style="color:#fff;">LOGOUT</a>
                </span>
            </div>
        </div>
        
        <!-- SIDEBAR -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li><a href="index.html"><i class="fa fa-desktop"></i> Dashboard</a></li>
                    <li><a href="forum_admin.php" class="active-menu"><i class="fa fa-comments"></i> Forum</a></li>
                </ul>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Forum Management</h2>
                        <hr />
                        
                        <!-- Questions Management -->
                        <div class="panel panel-default">
                            <div class="panel-heading">Questions Management</div>
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Question</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($questions as $q): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($q['id_question']) ?></td>
                                                <td><?= htmlspecialchars($q['question_text']) ?></td>
                                                <td>
                                                    <?= $q['is_suggestion'] == 1 ? '<span class="label label-success">Suggestion</span>' : '<span class="label label-primary">Question</span>' ?>
                                                </td>
                                                <td>
                                                    <a href="updateQuestion.php?id=<?= $q['id_question'] ?>&context=admin" class="btn btn-primary">Modifier</a>
                                                    <a href="deleteQuestion.php?id=<?= $q['id_question'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?');">Supprimer</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Responses Management -->
                        <div class="panel panel-default">
                            <div class="panel-heading">Responses Management</div>
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Response</th>
                                            <th>Question ID</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($responses as $r): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($r['id_response']) ?></td>
                                                <td><?= htmlspecialchars($r['response_text']) ?></td>
                                                <td><?= htmlspecialchars($r['id_question']) ?></td>
                                                <td>
                                                    <a href="updateResponse.php?id=<?= $r['id_response'] ?>&context=admin" class="btn btn-primary">Modifier</a>
                                                    <a href="deleteResponse.php?id=<?= $r['id_response'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ?');">Supprimer</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="row">
                <div class="col-lg-12">
                    &copy; 2024 YourCompany | Design by: You
                </div>
            </div>
        </div>
    </div>
</body>
</html>

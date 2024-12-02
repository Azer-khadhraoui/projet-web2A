<?php
require_once '../controller/QuestionController.php';
require_once '../controller/reponseController.php';


$questionController = new QuestionController();
$reponseController = new ReponseController();
$totalQuestions = $questionController->countQuestions();
$totalResponses = $reponseController->countResponses();
$avgResponses = $totalResponses > 0 ? round($totalResponses / $totalQuestions, 2) : 0;
?>
<div class="stats">
    <p>Total Questions: <?php echo $totalQuestions; ?></p>
    <p>Total Responses: <?php echo $totalResponses; ?></p>
    <p>Average Responses per Question: <?php echo $avgResponses; ?></p>
</div>

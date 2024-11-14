<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Question.php';

class QuestionController {
    public function addQuestion($id_client, $question_text) {
        $pdo = config::getConnexion();
        $question = new Question($id_client, $question_text);
        $question->save($pdo);
    }

    public function getAllQuestions() {
        $pdo = config::getConnexion();
        return Question::getAll($pdo);
    }
}
?>

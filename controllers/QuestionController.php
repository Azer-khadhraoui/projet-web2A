<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/question.php';

class QuestionController {
    public function addQuestion($id_client, $question_text, $is_suggestion = false) {
        $pdo = config::getConnexion();
        $stmt = $pdo->prepare("INSERT INTO question (id_client, question_text, is_suggestion, created_at) VALUES (:id_client, :question_text, :is_suggestion, NOW())");
        $stmt->execute([
            'id_client' => $id_client,
            'question_text' => $question_text,
            'is_suggestion' => $is_suggestion ? 1 : 0
        ]);
    }
    public function getAllSuggestions() {
        $pdo = config::getConnexion();
        $stmt = $pdo->query("SELECT * FROM question WHERE is_suggestion = 1 ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    public function getAllQuestions() {
        $pdo = config::getConnexion();
        return Question::getAll($pdo);
    }
}
?>

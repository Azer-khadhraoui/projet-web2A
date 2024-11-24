<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/question.php';

class QuestionController {
    // Add a new question or suggestion
    public function addQuestion($id_client, $question_text, $is_suggestion = false) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("INSERT INTO question (id_client, question_text, is_suggestion, created_at) VALUES (:id_client, :question_text, :is_suggestion, NOW())");
            $stmt->execute([
                'id_client' => $id_client,
                'question_text' => $question_text,
                'is_suggestion' => $is_suggestion ? 1 : 0
            ]);
        } catch (Exception $e) {
            echo "Error adding question: " . $e->getMessage();
        }
    }

    // Get all suggestions
    public function getAllSuggestions() {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->query("SELECT * FROM question WHERE is_suggestion = 1 ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error fetching suggestions: " . $e->getMessage();
            return [];
        }
    }

    // Get all questions
    public function getAllQuestions() {
        try {
            $pdo = config::getConnexion();
            return Question::getAll($pdo);
        } catch (Exception $e) {
            echo "Error fetching questions: " . $e->getMessage();
            return [];
        }
    }

    // Update a question by ID
    public function updateQuestion($questionText, $id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("UPDATE question SET question_text = :question_text WHERE id_question = :id");
            $stmt->execute([
                'question_text' => $questionText,
                'id' => $id
            ]);
        } catch (Exception $e) {
            echo "Error updating question: " . $e->getMessage();
        }
    }

    // Delete a question by ID
    public function deleteQuestion($id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("DELETE FROM question WHERE id_question = :id");
            $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            echo "Error deleting question: " . $e->getMessage();
        }
    }

    // Get a question by ID
    public function getQuestionById($id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM question WHERE id_question = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error fetching question by ID: " . $e->getMessage();
            return null;
        }
    }
}
?>

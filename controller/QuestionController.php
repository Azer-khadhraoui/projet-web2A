<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/question.php';

class QuestionController {
    // Ajouter une nouvelle question ou suggestion
    public function addQuestion($id_client, $question_text, $is_suggestion = false) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("INSERT INTO question (id_client, question_text, is_suggestion, created_at) VALUES (:id_client, :question_text, :is_suggestion, NOW())");
            $stmt->execute([ 
                'id_client' => $id_client, 
                'question_text' => $question_text, 
                'is_suggestion' => $is_suggestion ? 1 : 0
            ]);
            
            // Retourner l'ID de la question insérée
            return $pdo->lastInsertId();
        } catch (Exception $e) {
            echo "Error adding question: " . $e->getMessage();
            return null;
        }
    }

    // Récupérer toutes les questions sans pagination ni tri
    public function getAllQuestions() {
        $pdo = config::getConnexion();
        $stmt = $pdo->query("SELECT * FROM question ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une question par son ID
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

    // Mise à jour d'une question par ID
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

    // Supprimer une question par ID
    public function deleteQuestion($id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("DELETE FROM question WHERE id_question = :id");
            $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            echo "Error deleting question: " . $e->getMessage();
        }
    }

    // Get all suggestions for a specific question
    public function getSuggestionsByQuestion($questionId) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM question WHERE is_suggestion = 1 AND related_question_id = :question_id ORDER BY created_at DESC");
            $stmt->execute(['question_id' => $questionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    // Get all suggestions (for the suggestion section)
    public function getAllSuggestions() {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->query("SELECT * FROM question WHERE is_suggestion = 1 ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    // Get questions sorted by a specific field (use only valid sorting criteria)
    public function getQuestionsSorted($sortBy) {
        $pdo = config::getConnexion();
        $allowedSortColumns = ['is_suggestion', 'created_at', 'question_text'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'is_suggestion'; // Default to sorting by 'is_suggestion' if an invalid sort is passed
        }
        $stmt = $pdo->prepare("SELECT * FROM question ORDER BY $sortBy DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all suggestions (fixed)
    public function getSuggestions() {
        $pdo = config::getConnexion();
        $sql = "SELECT * FROM question WHERE is_suggestion = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateQuestionStatus($questionId, $status) {
        $validStatuses = ['open', 'answered', 'closed'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception('Invalid status');
        }
        $pdo = config::getConnexion();
        $stmt = $pdo->prepare("UPDATE question SET status = ? WHERE id_question = ?");
        $stmt->execute([$status, $questionId]);
    }

    // Like a question
   // Dans QuestionController.php (méthode likeQuestion)
public function likeQuestion($id_question, $id_client) {
    try {
        $pdo = config::getConnexion();
 
        // Vérifier si l'utilisateur a déjà liké cette question
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE id_question = :id_question AND id_client = :id_client");
        $stmt->execute(['id_question' => $id_question, 'id_client' => $id_client]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception('Vous avez déjà liké cette question.');
        }
 
        // Ajouter un nouveau like
        $stmt = $pdo->prepare("INSERT INTO likes (id_question, id_client) VALUES (:id_question, :id_client)");
        $stmt->execute(['id_question' => $id_question, 'id_client' => $id_client]);
 
        // Mettre à jour le nombre de likes pour la question
        $stmt = $pdo->prepare("UPDATE question SET likes_count = likes_count + 1 WHERE id_question = :id_question");
        $stmt->execute(['id_question' => $id_question]);
 
        // Redirection après avoir liké
        header('Location: ' . BASE_URL . 'views/front office/index.php?action=discussion');
        exit;
    } catch (Exception $e) {
        echo "Erreur: " . $e->getMessage();
    }
}

    public function incrementLikes($question_id) {
        // Get the database connection from the model
        $pdo = config::getConnexion();

        // Call the method from the Question model to increment the like count
        try {
            $stmt = $pdo->prepare("UPDATE question SET likes_count = likes_count + 1 WHERE id_question = ?");
            $stmt->execute([$question_id]);
        } catch (Exception $e) {
            throw new Exception("Error updating like count: " . $e->getMessage());
        }
    }
    public function searchQuestionById($id) {
        $pdo = config::getConnexion();
        $stmt = $pdo->prepare("SELECT * FROM question WHERE id_question = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
}

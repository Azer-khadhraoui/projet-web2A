<?php
class Question {
    private $id_question;
    private $id_client;
    private $question_text;
    private $created_at;
    private $is_suggestion;
    private $status; // New property for status

     
    public function __construct(int $id_client, string $question_text, ?DateTime $created_at = null, bool $is_suggestion = false, string $status = 'open') {
        $this->id_client = $id_client;
        $this->question_text = $question_text;
        $this->created_at = $created_at ?? new DateTime(); // Default to current date/time if not provided
        $this->is_suggestion = $is_suggestion; // Set whether this is a suggestion
        $this->status = $status; // Set the status
    }

     
    public function save($pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO question (id_client, question_text, created_at, is_suggestion, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $this->id_client,
                $this->question_text,
                $this->created_at->format('Y-m-d H:i:s'), // Format DateTime for MySQL
                $this->is_suggestion ? 1 : 0, // Save 1 if it's a suggestion, otherwise 0
                $this->status // Save status
            ]);
            $this->id_question = $pdo->lastInsertId(); // Set the id after inserting
        } catch (Exception $e) {
            echo "Error saving question: " . $e->getMessage();
        }
    }

    
    public static function getAll($pdo) {
        try {
            $stmt = $pdo->query("SELECT * FROM question ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error fetching questions: " . $e->getMessage();
            return [];
        }
    }
    

    // Get questions marked as suggestions
    public static function getSuggestions($pdo) {
        try {
            $stmt = $pdo->query("SELECT * FROM question WHERE is_suggestion = 1 ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error fetching suggestions: " . $e->getMessage();
            return [];
        }
    }

    // New method to update the status of a question
    public static function updateStatus($pdo, $id_question, $status) {
        $validStatuses = ['open', 'answered', 'closed'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception('Invalid status');
        }
        $stmt = $pdo->prepare("UPDATE question SET status = ? WHERE id_question = ?");
        $stmt->execute([$status, $id_question]);
    }

    // Getter and Setter for status
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}
?>
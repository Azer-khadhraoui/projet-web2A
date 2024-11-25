<?php
class Question {
    private $id_question;
    private $id_client;
    private $question_text;
    private $created_at;
    private $is_suggestion;

    // Constructor with optional parameters
    public function __construct(int $id_client, string $question_text, ?DateTime $created_at = null, bool $is_suggestion = false) {
        $this->id_client = $id_client;
        $this->question_text = $question_text;
        $this->created_at = $created_at ?? new DateTime(); // Default to current date/time if not provided
        $this->is_suggestion = $is_suggestion; // Set whether this is a suggestion
    }

    // Save the question to the database
    public function save($pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO question (id_client, question_text, created_at, is_suggestion) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $this->id_client,
                $this->question_text,
                $this->created_at->format('Y-m-d H:i:s'), // Format DateTime for MySQL
                $this->is_suggestion ? 1 : 0 // Save 1 if it's a suggestion, otherwise 0
            ]);
            $this->id_question = $pdo->lastInsertId(); // Set the id after inserting
        } catch (Exception $e) {
            echo "Error saving question: " . $e->getMessage();
        }
    }

    // Get all questions
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

    // Get a question by ID
    public static function getById($pdo, int $id) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM question WHERE id_question = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error fetching question by ID: " . $e->getMessage();
            return null;
        }
    }

    // Update a question in the database
    public static function update($pdo, int $id, string $question_text) {
        try {
            $stmt = $pdo->prepare("UPDATE question SET question_text = ?, updated_at = NOW() WHERE id_question = ?");
            $stmt->execute([$question_text, $id]);
        } catch (Exception $e) {
            echo "Error updating question: " . $e->getMessage();
        }
    }

    // Delete a question from the database
    public static function delete($pdo, int $id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM question WHERE id_question = ?");
            $stmt->execute([$id]);
        } catch (Exception $e) {
            echo "Error deleting question: " . $e->getMessage();
        }
    }

    // Getters
    public function getIdQuestion(): ?int {
        return $this->id_question;
    }

    public function getIdClient(): int {
        return $this->id_client;
    }

    public function getQuestionText(): string {
        return $this->question_text;
    }

    public function getCreatedAt(): DateTime {
        return $this->created_at;
    }

    public function isSuggestion(): bool {
        return $this->is_suggestion;
    }

    // Setters
    public function setIdQuestion(int $id_question): void {
        $this->id_question = $id_question;
    }

    public function setIdClient(int $id_client): void {
        $this->id_client = $id_client;
    }

    public function setQuestionText(string $question_text): void {
        $this->question_text = $question_text;
    }

    public function setCreatedAt(DateTime $created_at): void {
        $this->created_at = $created_at;
    }

    public function setIsSuggestion(bool $is_suggestion): void {
        $this->is_suggestion = $is_suggestion;
    }
}
?>

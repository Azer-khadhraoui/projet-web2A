<?php
class question {
    private $id_question;
    private $id_client;
    private $question_text;
    private $created_at;
    private $is_suggestion;

    // Constructor with is_suggestion as an optional parameter (default to false)
    public function __construct(int $id_client, string $question_text, DateTime $created_at = null, bool $is_suggestion = true) {
        $this->id_client = $id_client;
        $this->question_text = $question_text;
        $this->created_at = $created_at ?? new DateTime(); // Default to current date/time if not provided
        $this->is_suggestion = $is_suggestion; // Set whether this is a suggestion
    }

    // Method to save the question to the database, with is_suggestion support
    public function save($pdo) {
        $stmt = $pdo->prepare("INSERT INTO question (id_client, question_text, created_at, is_suggestion) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $this->id_client,
            $this->question_text,
            $this->created_at->format('Y-m-d H:i:s'), // Format DateTime for MySQL
            $this->is_suggestion ? 1 : 0 // Save 1 if it's a suggestion, otherwise 0
        ]);
        $this->id_question = $pdo->lastInsertId(); // Set the id after inserting
    }

    // Static method to get all questions marked as suggestions
    public static function getSuggestions($pdo) {
        $stmt = $pdo->query("SELECT * FROM question WHERE is_suggestion = 1 ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Static method to get all questions from the database
public static function getAll($pdo) {
    $stmt = $pdo->query("SELECT * FROM question ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Getters
    public function getIdQuestion(): int {
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

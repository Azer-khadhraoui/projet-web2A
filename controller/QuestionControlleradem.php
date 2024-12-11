<?php
require_once '../config.php';

class QuestionController
{
    private $db;

    public function __construct()
    {
        $this->db = Config::getConnexion(); // Assuming Config::getConnexion() returns a PDO instance
    }

    public function listPublicationsSortedByTitle($sortOrder)
    {
        try {
            $sql = "SELECT * FROM questionreclamation ORDER BY titre_quest $sortOrder";
            $list = $this->db->query($sql);
            return $list->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getQuestionById($id_quest)
    {
        try {
            $query = $this->db->prepare('SELECT * FROM questionreclamation WHERE id_quest = :id_quest');
            $query->bindValue(':id_quest', $id_quest, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }
    public function countQuestions()
    {
        try {
            $query = $this->db->prepare("SELECT COUNT(*) AS total FROM questionreclamation");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            die("Error counting questions: " . $e->getMessage());
        }
    }


    public function addQuestion($question)
    {
        $sql = "INSERT INTO questionreclamation (titre_quest, contenue, date, id_user) VALUES (:titre_quest, :contenue, CURDATE(), :id_user)";
        try {
            $query = $this->db->prepare($sql);


            $query->execute([
                'titre_quest' => $question->getTitreQuest(),
                'contenue' => $question->getContenue(),
                'id_user' => $question->getUserId(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }


    public function deleteQuestion1($id)
    {
        try {
            $sql = "DELETE FROM questionreclamation WHERE id_quest = :id";
            $req = $this->db->prepare($sql);
            $req->bindValue(':id', $id, PDO::PARAM_INT);
            $req->execute();
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    public function deleteQuestion($id)
    {
        try {
            // First, delete all responses associated with the question
            $sqlDeleteResponses = "DELETE FROM responsereclamation WHERE id_quest = :id_quest";
            $reqResponses = $this->db->prepare($sqlDeleteResponses);
            $reqResponses->bindValue(':id_quest', $id, PDO::PARAM_INT);
            $reqResponses->execute();

            // Then, delete the question itself
            $sqlDeleteQuestion = "DELETE FROM questionreclamation WHERE id_quest = :id_quest";
            $reqQuestion = $this->db->prepare($sqlDeleteQuestion);
            $reqQuestion->bindValue(':id_quest', $id, PDO::PARAM_INT);
            $reqQuestion->execute();

        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }


    public function getQuestionsByUserId($id_user)
    {
        $sql = "SELECT * FROM questionreclamation WHERE id_user = :id_user";
        try {
            $query = $this->db->prepare($sql);
            $query->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function searchquestion($recherche)
    {
        $sql = "SELECT * FROM questionreclamation
                    WHERE titre_quest LIKE :recherche "
        ;
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindValue(':recherche', '%' . $recherche . '%');
            $query->execute();
            $question = $query->fetchAll();
            return $question;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    public function updateQuestion($id_quest, $new_content)
    {
        // Check if the question ID exists
        $stmt = $this->db->prepare("SELECT id_quest FROM questionreclamation WHERE id_quest = :id_quest");
        $stmt->execute(['id_quest' => $id_quest]);
        $question = $stmt->fetch();

        if ($question) {
            // Update the content of the question
            $updateStmt = $this->db->prepare("UPDATE questionreclamation SET contenue = :new_content WHERE id_quest = :id_quest");
            $updateStmt->execute([
                'new_content' => $new_content,
                'id_quest' => $id_quest
            ]);

            return "Question updated successfully.";
        } else {
            return "Question not found.";
        }
    }
    public function updateQuestionContent($id_quest, $new_title, $new_content)
    {
        // Check if the question ID exists
        $stmt = $this->db->prepare("SELECT id_quest FROM questionreclamation WHERE id_quest = :id_quest");
        $stmt->execute(['id_quest' => $id_quest]);
        $question = $stmt->fetch();
    
        if ($question) {
            // Update both the title and content of the question
            $updateStmt = $this->db->prepare("UPDATE questionreclamation SET titre_quest = :new_title, contenue = :new_content WHERE id_quest = :id_quest");
            $updateStmt->execute([
                'new_title' => $new_title,
                'new_content' => $new_content,
                'id_quest' => $id_quest
            ]);
    
            return "Question updated successfully.";
        } else {
            return "Question not found.";
        }
    }
    



}
?>
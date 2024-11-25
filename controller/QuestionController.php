<?php
require_once '../config.php';

class QuestionController {
    private $db;

    public function __construct() {
        $this->db = Config::getConnexion(); // Assuming Config::getConnexion() returns a PDO instance
    }

    public function listPublicationsSortedByTitle($sortOrder) {
        try {
            $sql = "SELECT * FROM question ORDER BY titre_quest $sortOrder";
            $list = $this->db->query($sql);
            return $list->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getQuestionById($id_quest) {
        try {
            $query = $this->db->prepare('SELECT * FROM question WHERE id_quest = :id_quest');
            $query->bindValue(':id_quest', $id_quest, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }

    public function addQuestion($question)
{
    $sql = "INSERT INTO question (titre_quest, contenue, date, id_user) VALUES (:titre_quest, :contenue, CURDATE(), :id_user)";
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


    public function deleteQuestion1($id) {
        try {
            $sql = "DELETE FROM question WHERE id_quest = :id";
            $req = $this->db->prepare($sql);
            $req->bindValue(':id', $id, PDO::PARAM_INT);
            $req->execute();
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    public function deleteQuestion($id) {
        try {
            // First, delete all responses associated with the question
            $sqlDeleteResponses = "DELETE FROM response WHERE id_quest = :id_quest";
            $reqResponses = $this->db->prepare($sqlDeleteResponses);
            $reqResponses->bindValue(':id_quest', $id, PDO::PARAM_INT);
            $reqResponses->execute();
    
            // Then, delete the question itself
            $sqlDeleteQuestion = "DELETE FROM question WHERE id_quest = :id_quest";
            $reqQuestion = $this->db->prepare($sqlDeleteQuestion);
            $reqQuestion->bindValue(':id_quest', $id, PDO::PARAM_INT);
            $reqQuestion->execute();
    
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

   
        public function getQuestionsByUserId($id_user)
        {
            $sql = "SELECT * FROM question WHERE id_user = :id_user";
            try {
                $query = $this->db->prepare($sql);
                $query->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                $query->execute();
                return $query->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        }
        public function updateQuestionContent($id_quest, $newContent)
        {
            $sql = "UPDATE question SET contenue = :contenue WHERE id_quest = :id_quest";
            try {
                $query = $this->db->prepare($sql);
                $query->bindParam(':contenue', $newContent, PDO::PARAM_STR);
                $query->bindParam(':id_quest', $id_quest, PDO::PARAM_INT);
                $query->execute();
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        }
        public function searchquestion($recherche) {
            $sql = "SELECT * FROM question
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

   
}
?>


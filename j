<?php

require '../../config.php';

class question
{
    // Database connection
    private $db;

    public function __construct()
    {
        // Initialize the database connection
        $this->db = Config::getConnexion();
    }

    public function listPublicationsSortedByTitle($sortOrder)
    {
        try {
            // Select all publications with sorting by title
            $sql = "SELECT * FROM question ORDER BY titre_quest $sortOrder";
            $list = $this->db->query($sql);

            // Fetch the result set
            return $list->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle exceptions
            die('Error:' . $e->getMessage());
        }
    }

    public function getquestionById($id_quest)
    {
        $db = config::getConnexion();

        try {
            $query = $db->prepare('SELECT * FROM question WHERE id_question = :id_question');
            $query->bindValue(':id_quest', $id_quest);
            $query->execute();

            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Add an error message for debugging
            echo 'Error: ' . $e->getMessage();
            return null; // Return null to indicate failure
        }
    }

    public function listquestion()
    {
        try {
            // Select all publications
            $sql = "SELECT * FROM question";
            $list = $this->db->query($sql);

            // Fetch the result set
            return $list->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle exceptions
            die('Error:' . $e->getMessage());
        }
    }

    public function deletequestion($id)
    {
        try {
            // Delete a publication by ID
            $sql = "DELETE FROM question WHERE id_quest = :id";
            $req = $this->db->prepare($sql);
            $req->bindValue(':id', $id);
            $req->execute();
        } catch (PDOException $e) {
            // Handle exceptions
            die('Error:' . $e->getMessage());
        }
    }

    function addquestion($question)
    {
        $sql = "INSERT INTO question (titre_quest, contenue, date, id_user) VALUES (:titre_quest, :contenue, CURDATE(), :id_user)";

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                //'id_quest'=> $question->getIdQuestion(),
                'titre_quest' => $question->getTitreQuest(),
                'contenue' => $question->getContenue(),
                'id_user' => $question->getUserId(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function showquestion($id)
    {
        try {
            // Select a publication by ID
            $sql = "SELECT * FROM question WHERE id_quest= :id";
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
            // Fetch the result set
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle exceptions
            die('Error:' . $e->getMessage());
        }
    }

    function updatequestion($question, $id)
{
    try {
        $db = config::getConnexion();
        $query = $db->prepare(
            'UPDATE question SET 
                id_quest = :id_quest,
                titre_quest = :titre_quest, 
                contenue = :contenue, 
                id_user = :id_user,
                date = :date
            WHERE id_quest = :id'
        );

        $query->execute([
            'id_quest' => $id,
            'titre_quest' => $question->getTitreQuest(),
            'contenue' => $question->getContenue(),
            'id_user' => $question->getUserId(),
            'date' => $question->getDate(),
            'id' => $id  // Add this line to bind the ID correctly
        ]);

        // Return true if at least one row was affected, indicating success
        if ($query->rowCount() > 0) {
            // Redirect to listquestion.php
            header('Location: listquestion.php');
            exit(); // Stop further execution
        } else {
            // Return false if no rows were affected
            return false;
        }
    } catch (PDOException $e) {
        // Log or handle the error as needed
        echo 'Error: ' . $e->getMessage();
        return false; // Return false to indicate failure
    }
}
function searchquestion($recherche) {
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
public function deleteReponse($id)
{
    try {
        // Fetch the content of the response
        $sql = "SELECT contenue_rep FROM reponse WHERE id_reponse = :id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':id', $id);
        $req->execute();
        $content = $req->fetchColumn();

        // List of banned words
        $bannedWords = array("word1", "word2", "word3"); // Add your banned words here

        // Check if content contains any banned words
        foreach ($bannedWords as $word) {
            if (stripos($content, $word) !== false) {
                // If banned word found, return a message without deleting
                return "Content contains banned words.";
            }
        }

        // If no banned words found, return success message
        return "Response does not contain any banned words.";
    } catch (PDOException $e) {
        // Handle exceptions
        die('Error:' . $e->getMessage());
    }
}



}
?>
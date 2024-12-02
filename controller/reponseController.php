<?php

require_once '../config.php';
require_once '../model/reponseModel.php';
class ReponseController {
    private $db;

    public function __construct() {
        $this->db = Config::getConnexion();
    }

    public function addResponse(ReponseModel $response) {
        $sql = "INSERT INTO response (contenue, id_quest, id_user, date) VALUES (:contenue, :id_quest, :id_user, :date)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':contenue', $response->getContenue());
            $stmt->bindParam(':id_quest', $response->getIdQuest());
            $stmt->bindParam(':id_user', $response->getIdUser());
            $stmt->bindParam(':date', $response->getDate());
            return $stmt->execute();  // Return true or false
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getResponsesByQuestionId($id_quest) {
        $sql = "SELECT * FROM response WHERE id_quest = :id_quest ORDER BY date ASC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_quest', $id_quest);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Check if the response belongs to the user
    public function isUserResponseOwner($id_response, $id_user) {
        $sql = "SELECT COUNT(*) FROM response WHERE id_reponse = :id AND id_user = :id_user";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id_response);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteResponse($id_response, $id_user) {
        if ($this->isUserResponseOwner($id_response, $id_user)) {
            $sql = "DELETE FROM response WHERE id_reponse= :id";
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $id_response);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                return false;
            }
        } else {
            return false;
        }
    }
    public function countResponses() {
        try {
            $query = $this->db->prepare("SELECT COUNT(*) AS total FROM response");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            die("Error counting responses: " . $e->getMessage());
        }
    }
    

    public function updateResponse($id_response, $contenue, $id_user) {
        if ($this->isUserResponseOwner($id_response, $id_user)) {
            $sql = "UPDATE response SET contenue = :contenue WHERE id_reponse = :id";
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':contenue', $contenue);
                $stmt->bindParam(':id', $id_response);
                $stmt->execute();
                
                // Return true if the update was successful
                return $stmt->rowCount() > 0;
            } catch (PDOException $e) {
                return false;
            }
        } else {
            return false;  // Return false if user is not authorized
        }
    }
    private function deleteResponseById($id_reponse) {
        $deleteQuery = "DELETE FROM response WHERE id_reponse = ?";
        $deleteStmt = $this->db->prepare($deleteQuery);
        $deleteStmt->execute([$id_reponse]);
    }

    public function deleteResponsesWithBadWords() {
        // List of bad words to search for
        $badWords = ['bad1', 'bad2', 'bad3'];

        // Prepare the query to fetch responses containing bad words
        $placeholders = implode(',', array_fill(0, count($badWords), '?'));
        $query = "SELECT id_reponse, contenue FROM response WHERE " . implode(' OR ', array_map(fn($word) => "contenue LIKE ?", $badWords));
        $stmt = $this->db->prepare($query);

        // Bind the bad words with wildcards for partial matching
        foreach ($badWords as $index => $word) {
            $stmt->bindValue($index + 1, '%' . $word . '%');
        }

        $stmt->execute();
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop through results and delete each response
        foreach ($response as $response) {
            $this->deleteResponseById($response['id_reponse']);
        }

        return count($response); // Return the count of deleted responses
    }

}

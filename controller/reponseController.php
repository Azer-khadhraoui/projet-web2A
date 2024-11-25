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
}

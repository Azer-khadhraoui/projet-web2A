<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/response.php';

class ResponseController {
    // Add a new response
    public function addResponse($id_question, $id_responder, $response_text) {
        try {
            $db = config::getConnexion();
            $sql = "INSERT INTO response (id_question, id_responder, response_text, created_at) VALUES (:id_question, :id_responder, :response_text, NOW())";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'id_question' => $id_question,
                'id_responder' => $id_responder,
                'response_text' => $response_text,
            ]);
        } catch (Exception $e) {
            echo "Error adding response: " . $e->getMessage();
        }
    }

    // Fetch all responses
    public function getAllResponses() {
        try {
            $db = config::getConnexion();
            $stmt = $db->query("SELECT * FROM response ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error fetching responses: " . $e->getMessage();
            return [];
        }
    }

    // Fetch a single response by ID
    public function getResponseById($id) {
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare("SELECT * FROM response WHERE id_response = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error fetching response: " . $e->getMessage();
            return null;
        }
    }

    // Update a response
    public function updateResponse($response_text, $id) {
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare("UPDATE response SET response_text = :response_text WHERE id_response = :id");
            $stmt->execute([
                'response_text' => $response_text,
                'id' => $id,
            ]);
        } catch (Exception $e) {
            echo "Error updating response: " . $e->getMessage();
        }
    }

    // Delete a response
    public function deleteResponse($id) {
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare("DELETE FROM response WHERE id_response = :id");
            $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            echo "Error deleting response: " . $e->getMessage();
        }
    }
}

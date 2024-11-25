<?php
require_once __DIR__ . '/../config.php'; // Ensure the config file is included
require_once __DIR__ . '/../models/response.php';

class ResponseController {

    // Fetch responses for a specific question
    public function getResponsesByQuestion($question_id) {
        try {
            $pdo = config::getConnexion();
            return Response::getAllForQuestion($pdo, $question_id);
        } catch (Exception $e) {
            die("Error fetching responses: " . $e->getMessage());
        }
    }

    // Add a new response
    public function addResponse($question_id, $id_responder, $response_text) {
        try {
            $pdo = config::getConnexion();
            $response = new Response($question_id, $id_responder, $response_text);
            $response->save($pdo);
        } catch (Exception $e) {
            die("Error adding response: " . $e->getMessage());
        }
    }

    // Update a response
    public function updateResponse($response_text, $id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("UPDATE response SET response_text = ? WHERE id_response = ?");
            $stmt->execute([$response_text, $id]);
        } catch (Exception $e) {
            die("Error updating response: " . $e->getMessage());
        }
    }

    // Delete a response
    public function deleteResponse($id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("DELETE FROM response WHERE id_response = ?");
            $stmt->execute([$id]);
        } catch (Exception $e) {
            die("Error deleting response: " . $e->getMessage());
        }
    }

    // Fetch a single response by ID
    public function getResponseById($id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM response WHERE id_response = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            die("Error fetching response: " . $e->getMessage());
        }
    }

    // Fetch all responses
    public function getAllResponses() {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->query("SELECT * FROM response");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            die("Error fetching all responses: " . $e->getMessage());
        }
    }
}

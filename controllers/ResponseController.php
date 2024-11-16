<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/response.php';

class ResponseController {
    public function addResponse($id_question, $id_responder, $response_text) {
        // Connexion à la base de données
        $db = config::getConnexion();
        
        // Requête pour insérer la réponse dans la base de données
        $sql = "INSERT INTO response (id_question, id_responder, response_text) VALUES (:id_question, :id_responder, :response_text)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'id_question' => $id_question,
            'id_responder' => $id_responder,
            'response_text' => $response_text
        ]);
    }
    public function getResponsesByQuestionId($id_question) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT response_text, created_at FROM response WHERE id_question = :id_question ORDER BY created_at ASC");
        $stmt->execute(['id_question' => $id_question]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

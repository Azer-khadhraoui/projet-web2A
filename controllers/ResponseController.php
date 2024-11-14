<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Response.php';

class ResponseController {
    public function addResponse($id_question, $id_responder, $response_text) {
        $pdo = config::getConnexion();
        $response = new Response($id_question, $id_responder, $response_text);
        $response->save($pdo);
    }

    public function getResponsesForQuestion($id_question) {
        $pdo = config::getConnexion();
        return Response::getAllForQuestion($pdo, $id_question);
    }
}
?>

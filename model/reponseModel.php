<?php

class ReponseModel {
    private $id_reponse;
    private $contenue;
    private $id_quest;
    private $date;
    private $id_user;

    public function __construct($contenue, $id_quest, $id_user, $id_reponse = null, $date = null) {
        $this->id_reponse = $id_reponse;
        $this->contenue = $contenue;
        $this->id_quest = $id_quest;
        $this->id_user = $id_user;
        $this->date = $date ?? date("Y-m-d H:i:s");
    }

    // Getters
    public function getIdReponse() {
        return $this->id_reponse;
    }

    public function getContenue() {
        return $this->contenue;
    }

    public function getIdQuest() {
        return $this->id_quest;
    }

    public function getIdUser() {
        return $this->id_user;
    }

    public function getDate() {
        return $this->date;
    }

    // Setters
    public function setIdReponse($id_reponse) {
        $this->id_reponse = $id_reponse;
    }

    public function setContenue($contenue) {
        $this->contenue = $contenue;
    }

    public function setIdQuest($id_quest) {
        $this->id_quest = $id_quest;
    }

    public function setIdUser($id_user) {
        $this->id_user = $id_user;
    }

    public function setDate($date) {
        $this->date = $date;
    }
}
?>

<?php

class User {
    private $cin;
    private $fname;
    private $lname;
    private $pwd;
    private $num;
    private $role;
    private $mail; // Nouveau champ
    private $statut; // Nouveau champ

    // Constructeur
    public function __construct($cin, $fname, $lname, $pwd, $num, $role, $mail, $statut) {
        $this->cin = $cin;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->pwd = $pwd;
        $this->num = $num;
        $this->role = $role;
        $this->mail = $mail;
        $this->statut = $statut;
    }

    // Getters et setters pour les nouveaux champs
    public function getMail() {
        return $this->mail;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }

    // Autres getters et setters...
    public function getCin() {
        return $this->cin;
    }

    public function setCin($cin) {
        $this->cin = $cin;
    }

    public function getFname() {
        return $this->fname;
    }

    public function setFname($fname) {
        $this->fname = $fname;
    }

    public function getLname() {
        return $this->lname;
    }

    public function setLname($lname) {
        $this->lname = $lname;
    }

    public function getPassword() {
        return $this->pwd;
    }

    public function setPassword($pwd) {
        $this->pwd = $pwd;
    }

    public function getNum() {
        return $this->num;
    }

    public function setNum($num) {
        $this->num = $num;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }
}

?>
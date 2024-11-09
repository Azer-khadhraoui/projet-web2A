<?php

class User {
    private $cin;
    private $name;
    private $prenom;
    private $email;
    private $password;
    private $num;
    private $role;

    // Constructor
    public function __construct($cin, $name, $prenom, $email, $password, $num, $role) {
        $this->cin = $cin;
        $this->name = $name;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->num = $num;
        $this->role = $role;
    }

    // Getters
    public function getCin() {
        return $this->cin;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getNum() {
        return $this->num;
    }

    public function getRole() {
        return $this->role;
    }

    // Setters
    public function setCin($cin) {
        $this->cin = $cin;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setNum($num) {
        $this->num = $num;
    }

    public function setRole($role) {
        $this->role = $role;
    }
}

?>
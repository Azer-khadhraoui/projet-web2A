<?php

class User {
    private $cin;
    private $fname;
    private $lname;
    private $password;
    private $num;
    private $role;

    // Constructor
    public function __construct($cin, $fname, $lname, $password, $num, $role) {
        $this->cin = $cin;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->password = $password;
        $this->num = $num;
        $this->role = $role;
    }

    // Getters
    public function getCin() {
        return $this->cin;
    }

    public function getFname() {
        return $this->fname;
    }

    public function getLname() {
        return $this->lname;
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

    public function setFname($fname) {
        $this->fname = $fname;
    }

    public function setLname($lname) {
        $this->lname = $lname;
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
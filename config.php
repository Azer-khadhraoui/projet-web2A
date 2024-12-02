<?php
class Config {
    public static function getConnexion() {
        try {
            // Replace with your own database credentials
            $dsn = 'mysql:host=localhost;dbname=dbquestion';
            $username = 'root';
            $password = '';
            return new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
?>


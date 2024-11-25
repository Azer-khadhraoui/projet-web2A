<?php
include '../config.php';

try {
    $db = Config::getConnexion();
    echo "Database connection successful.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


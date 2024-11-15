<?php
include '../../Controller/usercontroller.php';

if (isset($_GET['cin'])) {
    $cin = $_GET['cin'];

    
    if (!is_numeric($cin)) {
        die("CIN invalide. Réessayer.");
    }

    $controller = new UserController();
    $controller->deleteUser($cin); 

    header("Location: affichage.php");
    exit();
} else {
    die("Aucun CIN fourni pour la suppression.");
}
?>
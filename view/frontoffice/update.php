<?php
include '../../Controller/usercontroller.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cin = $_POST['cin'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $num = $_POST['num'];
    $pwd = $_POST['pwd'];
    $role = $_POST['role'];
    $mail = $_POST['mail'];
    $statut = $_POST['statut'];

    // Créez un utilisateur avec les nouveaux champs
    $user = new User($cin, $fname, $lname, $pwd, $num, $role, $mail, $statut);

    $controller = new UserController();
    $controller->updateUser($user);

    header("Location: affichage.php");
    exit();
} else {
    die("Méthode de requête non valide.");
}
?>
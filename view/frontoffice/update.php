<?php
include '../../Controller/usercontroller.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cin = $_POST['cin'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $num = $_POST['num'];
    $pwd = $_POST['pwd'];
    $role = $_POST['role'];

    $user = new User($cin, $fname, $lname, $pwd, $num, $role);

    $controller = new UserController();
    $controller->updateUser($user);

    header("Location: affichage.php");
    exit();
} else {
    die("Méthode de requête non valide.");
}
?>
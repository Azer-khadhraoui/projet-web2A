<?php
include '../../Controller/usercontroller.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $cin = $_POST['cin'];
    $number = $_POST['number'];
    $password = $_POST['password'];
    $retype_password = $_POST['retype-password'];
    $mail = $_POST['mail'];
    

    // Vérifiez que les mots de passe correspondent
    if ($password !== $retype_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    // Créez un nouvel utilisateur avec les nouveaux champs
    $usr = new User($cin, $fname, $lname, $password, $number, 0, $mail,0);

    $controller = new usercontroller();
    $controller->adduser($usr);

    echo "Utilisateur ajouté avec succès.";
} else {
    echo "Aucune donnée reçue.";
}
?>
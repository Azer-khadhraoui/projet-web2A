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
    $statut = 0; 

    
    if ($password !== $retype_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

   
    $recaptcha_secret = '6LfReIoqAAAAAA8SADFIG8K-05njBLYPBU4Q7syB';
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';

    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    if (!$recaptcha->success) {
        echo "La vérification reCAPTCHA a échoué. Veuillez réessayer.";
        exit();
    }

    
    $usr = new User($cin, $fname, $lname, $password, $number, 0, $mail, $statut);

    $controller = new UserController();
    $controller->addUser($usr);
    header("Location: products.php ");
} else {
    echo "Aucune donnée reçue.";
}
?>
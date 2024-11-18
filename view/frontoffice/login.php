<?php
include '../../Controller/usercontroller.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cin = $_POST['cin'];
    $password = $_POST['password'];

    $controller = new UserController();
    $user = $controller->getUserByCin($cin);

    if ($user && $user['pwd'] === $password) {
        $fname = $user['nom'];
        $lname = $user['prenom'];
        $message = "Login successful! Welcome, $fname $lname.";
        header("Location: signin.html?message=" . urlencode($message));
        exit();
    } else {
        $cin_error = "";
        $password_error = "";
        if (!$user) {
            $cin_error = "Invalid CIN.";
        }
        if ($user && $user['pwd'] !== $password) {
            $password_error = "Invalid password.";
        }
        header("Location: signin.html?cin_error=" . urlencode($cin_error) . "&password_error=" . urlencode($password_error));
        exit();
    }
} else {
    header("Location: signin.html?message=" . urlencode("Invalid request method."));
    exit();
}
?>
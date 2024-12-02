<?php
include '../../Controller/usercontroller.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cin = $_POST['cin'];
    $password = $_POST['password'];

    $controller = new UserController();
    $user = $controller->getUserByCin($cin);

    if ($user) {
        if ($user['statut'] == 1) {
            
            echo "<div style='color: red; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; text-align: center;'>
                    Your account is blocked. Please contact the admin.
                  </div>";
        } elseif ($user['pwd'] === $password) {
            $fname = $user['nom'];
            $lname = $user['prenom'];
            $role = $user['role'];
            if ($role == 1) {
                header("Location: ../backoffice/bs-simple-admin/index.html");
                exit();
            } else {
                $message = "Login successful! Welcome, $fname $lname.";
                header("Location: signin.html?message=" . urlencode($message));
                exit();
            }
        } else {
            echo "<div style='color: red; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; text-align: center;'>
                    Mot de passe incorrect.
                  </div>";
        }
    } else {
        echo "<div style='color: red; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; text-align: center;'>
                CIN incorrect.
              </div>";
    }
} else {
    echo "<div style='color: red; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; text-align: center;'>
            Méthode de requête non valide.
          </div>";
}
?>
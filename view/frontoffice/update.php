<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

include '../../Controller/usercontroller.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cin = $_POST['cin'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $num = $_POST['num'];
    $pwd = $_POST['pwd'];
    $role = $_POST['role'];
    $email = $_POST['mail']; 
    $statut = $_POST['statut'];

   
    $user = new User($cin, $fname, $lname, $pwd, $num, $role, $email, $statut);

    $controller = new UserController();
    $oldUser = $controller->getUserByCin($cin);
    $controller->updateUser($user);

    
    if ($oldUser['statut'] == 0 && $statut == 1) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'azerronaldo2004@gmail.com';
            $mail->Password = 'pkjhmjhyuauhpqhl';
            $mail->SMTPSecure = 'tls'; 
            $mail->Port = 587;

           
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom('azerronaldo2004@gmail.com');
            $mail->addAddress($email); 

            $mail->isHTML(true);
            $mail->Subject = "Account Status Changed";
            $mail->Body = "Dear $fname $lname,<br><br>Your account status has been changed to blocked. Please contact the admin <br><br>Best regards,<br>GREEN & PURE";

            $mail->send();

            echo "<script>
            alert('Email sent successfully');
            window.location.href='affichage.php';
          </script>";
    exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    header("Location: affichage.php");
    exit();
} else {
    die("Méthode de requête non valide.");
}
?>
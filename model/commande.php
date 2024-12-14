<?php

use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; 

$host = 'localhost';
$dbname = 'greenandpure';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit();
}

$error = "";
$successMessage = "";
$errors = [];
$total_cmd = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_cmd = isset($_POST['date_cmd']) ? $_POST['date_cmd'] : '';
    $stat_cmd = isset($_POST['stat_cmd']) ? $_POST['stat_cmd'] : '';
    $adress_cmd = isset($_POST['adress_cmd']) ? $_POST['adress_cmd'] : '';

    if (empty($date_cmd) || !preg_match("/\d{4}-\d{2}-\d{2}/", $date_cmd)) {
        $errors[] = "La date de la commande est invalide ou manquante.";
    }

    if (empty($stat_cmd)) {
        $errors[] = "L'état de la commande doit être sélectionné.";
    }

    if (empty($adress_cmd)) {
        $errors[] = "L'adresse de la commande est obligatoire.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO commande (date_cmd, stat_cmd, adress_cmd, prix_total) 
                VALUES (:date_cmd, :stat_cmd, :adress_cmd, :total_cmd)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':date_cmd', $date_cmd);
        $stmt->bindParam(':stat_cmd', $stat_cmd);
        $stmt->bindParam(':adress_cmd', $adress_cmd);
        $stmt->bindParam(':total_cmd', $total_cmd);

        if ($stmt->execute()) {
            // Send email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'your-email@example.com';
                $mail->Password = 'your-email-password';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('your-email@example.com', 'Commande');
                $mail->addAddress($adress_cmd);

                // Construire le corps de l'e-mail
                $mail->isHTML(true);
                $mail->Subject = 'Confirmation de commande';
                $mail->Body = "
                    <h2>Merci pour votre commande !</h2>
                    <p>Votre commande a ete bien enregistree avec l'ID : <strong>$commande_id</strong>.</p>
                    <p><strong>Adresse de livraison :</strong> $adress_cmd</p>
                    <p><strong>Date de commande :</strong> $date_cmd</p>
                    <p><strong>État de la commande :</strong> $stat_cmd</p>
                ";

                $mail->send();
                $successMessage = "Commande ajoutée avec succès et email envoyé!";
                header('Location: ../../view/backoffice/bs-simple-admin/liste_commande.php');
                exit;
            } catch (Exception $e) {
                $errors[] = "Erreur lors de l'envoi de l'email: " . $mail->ErrorInfo;
            }

        } else {
            $errors[] = "Erreur lors de l'insertion de la commande.";
        }
    }

    if (!empty($errors)) {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande</title>
</head>

<body>
    <h1>Passer une commande</h1>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success">
            <?= $successMessage ?>
        </div>
    <?php endif; ?>
    <form action="commande.php" method="POST">
        <div class="form-group">
            <label for="date_cmd">Date de commande</label>
            <input type="date" name="date_cmd" id="date_cmd" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="stat_cmd">État de la commande</label>
            <select name="stat_cmd" id="stat_cmd" class="form-control" required>
                <option value="En attente">En attente</option>
                <option value="En cours">En cours</option>
                <option value="Livrée">Livrée</option>
            </select>
        </div>
        <div class="form-group">
            <label for="adress_cmd">Adresse de livraison</label>
            <input type="text" name="adress_cmd" id="adress_cmd" class="form-control" required>
        </div>
        <div class="form-group text-center">
            <button type="submit" class="send_btn">Passer la commande</button>
        </div>
    </form>
</body>

</html>

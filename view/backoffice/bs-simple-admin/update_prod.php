<?php

include 'D:\apache xampp\htdocs\projet-web2A\controller\prod_controller.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer.php';
require_once 'SMTP.php';
require_once 'Exception.php';

$controller = new TravelOfferController();
$conn = config::getConnexion();

if (isset($_GET['id'])) {
    $product = $controller->getProductById($_GET['id']);
    if (!$product) {
        echo "Product not found.";
        exit();
    }
} else {
    echo "Product ID is missing.";
    exit();
}

$categories = [];
$query2 = "SELECT id_categorie, nom_categorie FROM categorie ORDER BY nom_categorie";
$stmt2 = $conn->prepare($query2);

try {
    $stmt2->execute();
    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $categories[$row['id_categorie']] = $row['nom_categorie'];
    }
} catch (Exception $e) {
    echo "Error fetching categories: " . $e->getMessage();
    exit();
}

function sendEmailNotification($subject, $message, $to) {
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'benmaleksarra62@gmail.com'; // Replace with your email
        $mail->Password = 'urgargejdggthaos'; // Replace with your app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender details
        $mail->setFrom('benmaleksarra62@gmail.com', 'GREEN&PURE');
        $mail->addAddress($to); // Recipient

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br($message); // Convert newlines to <br> for HTML format

        // Send email
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_prod'];
    $nom_prod = $_POST['nom_prod'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $qte = $_POST['qte'];
    $cat = isset($_POST['categorie']) ? $_POST['categorie'] : null;
    
    // Handle file upload for image
    if (isset($_FILES['url_img']) && $_FILES['url_img']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['url_img']['name']);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES['url_img']['tmp_name'], $targetFilePath)) {
            $url_img = $fileName;
        } else {
            $url_img = null;
        }
    } else {
        $url_img = null;
    }

    // Check if category exists
    $queryCheckCategory = "SELECT COUNT(*) FROM categorie WHERE id_categorie = :cat";
    $stmtCheckCategory = $conn->prepare($queryCheckCategory);
    $stmtCheckCategory->execute(['cat' => $cat]);
    $categoryExists = $stmtCheckCategory->fetchColumn() > 0;

    if (!$categoryExists) {
        echo "Error: Selected category does not exist.";
        exit();
    }

    // Update the product
    $controller->updateProduct($id, $nom_prod, $description, $prix, $qte, $url_img, $cat);

    // Prepare email content
    $subject = "Product Updated: " . $nom_prod;
    $message = "<h1>Product Updated</h1>";
    $message .= "<p>The product <strong>" . htmlspecialchars($nom_prod) . "</strong> has been updated.</p>";
    $message .= "<p>Product ID: " . $id . "</p>";
    $message .= "<p>Description: " . htmlspecialchars($description) . "</p>";
    $message .= "<p>Price: " . $prix . " €</p>";
    $message .= "<p>Quantity: " . $qte . "</p>";
    $message .= "<p>Category: " . htmlspecialchars($cat) . "</p>";

    // Send email to admin
    $email = "benmaleksarra62@gmail.com";  // Replace with your email
    if (sendEmailNotification($subject, $message, $email)) {
        echo "Email sent successfully!";
    } else {
        echo "Email sending failed!";
    }

    // Redirect to the product list after update
    header('Location: list_products.php');
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #5a5a5a;
        }
        form {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #4CAF50;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        textarea {
            height: 100px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body onsubmit="return validatefroms() ;">
<a href="list_products.php"><-----BACK </a>
    <h1>Update Product</h1>
    <form method="POST">
        <input type="hidden" name="id_prod" value="<?= htmlspecialchars($product['id_prod']); ?>">

        <label>Product Name:</label>
        <input type="text" name="nom_prod" value="<?= htmlspecialchars($product['nom_prod']); ?>">

        <label>Description:</label>
        <textarea name="description"><?= htmlspecialchars($product['description']); ?></textarea>

        <label>Price:</label>
        <input type="number" step="0.01" name="prix" value="<?= htmlspecialchars($product['prix']); ?>">

        <label>Quantity:</label>
        <input type="number" name="qte" value="<?= htmlspecialchars($product['qte']); ?>">

        <label>Image URL:</label>
        <input type="file" name="url_img" value="<?= htmlspecialchars($product['url_img']); ?>">

        <label for="cat">Category:</label>
        <select name="categorie" id="cat">
    <option value="">-- Select a Category --</option>
    <?php foreach ($categories as $id => $name): ?>
        <option value="<?= htmlspecialchars($id) ?>" <?= $id == $product['categorie'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($name) ?>
        </option>
    <?php endforeach; ?>
</select>

        <button type="submit">Update Product</button>
        <script src="script.js"></script>
    </form>
</body>
</html>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer.php';
require_once 'SMTP.php';
require_once 'Exception.php';

include '../../../config.php' ;  
include 'D:\apache xampp\htdocs\projet-web2A\controller\prod_controller.php'; 

$conn = config::getConnexion();
$controller = new TravelOfferController();

// Fetch categories
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_prod = $_POST['nom_prod'] ?? '';
    $description = $_POST['description'] ?? '';
    $prix = $_POST['prix'] ?? '';
    $qte = $_POST['qte'] ?? '';
    $cat = $_POST['cat'] ?? '';

    if (empty($nom_prod) || empty($description) || empty($prix) || empty($qte) || empty($cat)) {
        echo "All fields must be filled out.";
        exit();
    }

    $file = $_FILES['url_img'] ?? null;

    if ($file && $file['error'] == 0) {
        $controller->createProduct($nom_prod, $description, $prix, $qte, $file, $cat);

        // Send Email after product is added
        $subject = "New Product Added: " . $nom_prod;
        $message = "<h1>New Product Added</h1>";
        $message .= "<p>A new product has been added to the system:</p>";
        $message .= "<p><strong>Name:</strong> " . htmlspecialchars($nom_prod) . "</p>";
        $message .= "<p><strong>Description:</strong> " . htmlspecialchars($description) . "</p>";
        $message .= "<p><strong>Price:</strong> " . $prix . " €</p>";
        $message .= "<p><strong>Quantity:</strong> " . $qte . "</p>";
        $message .= "<p><strong>Category:</strong> " . htmlspecialchars($cat) . "</p>";

        // Sending email to the admin or manager
        $email = "benmaleksarra62@gmail.com"; // Replace with your admin email
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'benmaleksarra62@gmail.com'; // Replace with your email
            $mail->Password = 'urgargejdggthaos'; // Replace with your app-specific password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Set sender and recipient
            $mail->setFrom('benmaleksarra62@gmail.com', 'GREEN&PURE');
            $mail->addAddress($email); // Admin email address

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            // Send the email
            $mail->send();

            // Redirect to the product list
            header('Location: list_products.php');
            exit();
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            exit();
        }
    } else {
        echo "Error: No file uploaded or file upload failed.";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
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
        input, textarea, select, button {
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
            margin-top: 20px;
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
<script>
    function validateForms() {
        const titre = document.getElementById('nom_prod').value.trim();
        const description = document.getElementById('description').value.trim();
        const prix = document.getElementById('prix').value;
        const qte = document.getElementById('qte').value.trim();
        const url_img = document.getElementById('url_img').value.trim();
        const cat = document.getElementById('cat').value.trim();

        let test = true;

        let expr = /^[A-Za-z\s]+$/;
        if (!expr.test(titre) || titre === "") {
            alert("Invalid title. Only letters and spaces are allowed.");
            test = false;
        } else if (description === "") {
            alert("Description cannot be empty.");
            test = false;
        } else if (cat === "") {
            alert("Please select a valid category.");
            test = false;
        } else if (url_img === "") {
            alert("Please select an image.");
            test = false;
        } else if (prix === "" || isNaN(prix)) {
            alert("Invalid price.");
            test = false;
        } else if (qte === "" || isNaN(qte)) {
            alert("Invalid quantity.");
            test = false;
        }

        return test;
    }
</script>
<body>
    
    <a href="list_products.php"><----- BACK </a>
    <h1>Add a New Product</h1>
    <form method="POST" enctype="multipart/form-data" onsubmit="return validateForms();">
        <label for="nom_prod">Product Name:</label>
        <input type="text" name="nom_prod" id="nom_prod">

        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea>

        <label for="prix">Price:</label>
        <input type="number" step="0.01" name="prix" id="prix">

        <label for="qte">Quantity:</label>
        <input type="number" name="qte" id="qte">

        <label for="url_img">Product Image:</label>
        <input type="file" name="url_img" id="url_img">

        <label for="cat">Category:</label>
        <select name="cat" id="cat">
            <option value="">-- Select a Category --</option>
            <?php foreach ($categories as $id => $name): ?>
                <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($name) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>

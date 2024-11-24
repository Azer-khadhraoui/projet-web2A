<?php
include('../../controller/prod_controller.php'); 
include_once('../../config.php');

// Initialize the controller
$controller = new TravelOfferController();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $nom_prod = $_POST['nom_prod'] ?? '';
    $description = $_POST['description'] ?? '';
    $prix = $_POST['prix'] ?? '';
    $qte = $_POST['qte'] ?? '';
    $cat = $_POST['cat'] ?? '';

    // Validate form inputs
    if (empty($nom_prod) || empty($description) || empty($prix) || empty($qte) || empty($cat)) {
        echo "All fields must be filled out.";
        exit();
    }

    // Handle file upload (using the controller's method)
    $file = $_FILES['url_img'] ?? null;

    if ($file && $file['error'] == 0) {
        // Call the controller's createProduct method to handle file upload and insertion
        $controller->createProduct($nom_prod, $description, $prix, $qte, $file, $cat);

        // Redirect after successful creation
        header('Location: list_products.php');
        exit();
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
<body>
    <a href="list_products.php"><----- BACK </a>
    <h1>Add a New Product</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="nom_prod">Product Name:</label>
        <input type="text" name="nom_prod" id="nom_prod" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="prix">Price:</label>
        <input type="number" step="0.01" name="prix" id="prix" required>

        <label for="qte">Quantity:</label>
        <input type="number" name="qte" id="qte" required>

        <label for="url_img">Product Image:</label>
        <input type="file" name="url_img" id="url_img" required>

        <label for="cat">Category:</label>
        <input type="number" name="cat" id="cat" required>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>

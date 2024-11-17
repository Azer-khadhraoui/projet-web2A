<?php
include('../../controller/prod_controller.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller = new TravelOfferController();

    $nom_prod = $_POST['nom_prod'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $qte = $_POST['qte'];
    $url_img = $_POST['url_img'];
    $cat = $_POST['cat'];

    $controller->createProduct($nom_prod, $description, $prix, $qte, $url_img, $cat);
    header('Location: list_products.php');
    exit();
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
    </style>
</head>
<body>
    <h1>Add a New Product</h1>
    <form method="POST">
        <label>Product Name:</label>
        <input type="text" name="nom_prod" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Price:</label>
        <input type="number" step="0.01" name="prix" required>

        <label>Quantity:</label>
        <input type="number" name="qte" required>

        <label>Image URL:</label>
        <input type="text" name="url_img" required>

        <label>Category:</label>
        <input type="number" name="cat" required>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>

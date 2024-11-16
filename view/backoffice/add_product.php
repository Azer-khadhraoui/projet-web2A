<?php
include(__DIR__ . '/../controller/prod_controller.php');

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
    <title>Add Product</title>
</head>
<body>
    <h1>Add a New Product</h1>
    <form method="POST">
        <label>Product Name:</label>
        <input type="text" name="nom_prod" required><br>

        <label>Description:</label>
        <textarea name="description" required></textarea><br>

        <label>Price:</label>
        <input type="number" step="0.01" name="prix" required><br>

        <label>Quantity:</label>
        <input type="number" name="qte" required><br>

        <label>Image URL:</label>
        <input type="text" name="url_img" required><br>

        <label>Category:</label>
        <input type="number" name="cat" required><br>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>

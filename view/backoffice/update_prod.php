<?php
include('../../controller/prod_controller.php'); 

$controller = new TravelOfferController();

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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_prod'];
    $nom_prod = $_POST['nom_prod'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $qte = $_POST['qte'];
    $url_img = $_POST['url_img'];
    $cat = $_POST['categorie'];

    $controller->updateProduct($id, $nom_prod, $description, $prix, $qte, $url_img, $cat);
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
<body>
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

        <label>Category:</label>
        <input type="number" name="categorie" value="<?= htmlspecialchars($product['categorie']); ?>">

        <button type="submit">Update Product</button>
        <script src="script.js"></script>
    </form>
</body>
</html>

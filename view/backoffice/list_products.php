<?php
include '../../config.php';  // Adjust the path to the config file
include '../../model/products_mod.php'; // Adjust the path to the model
include '../../controller/prod_controller.php'; // Adjust the path to the controller

$controller = new TravelOfferController();

// Fetch all products
$products = $controller->getAllProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
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
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e1f5fe;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style></head>
<body>
<h1>Product List</h1>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Category</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
            <td><?= $product['id_prod']; ?></td>
            <td><?= $product['nom_prod']; ?></td>
            <td><?= $product['description']; ?></td>
            <td><?= $product['prix']; ?> €</td>
            <td><?= $product['qte']; ?></td>
            <td><?= $product['categorie']; ?></td>
            <td><img src="images/<?= $product['url_img']; ?>" width="50" alt="<?= $product['nom_prod']; ?>"></td>
            <td>
                <a href="update_prod.php?id=<?= $product['id_prod']; ?>">Edit</a> |
                <a href="delete_prod.php?id=<?= $product['id_prod']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<a href="add_product.php">Add New Product</a>
</body>
</html>

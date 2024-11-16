<?php
include(__DIR__ . '/../controller/prod_controller.php');

$controller = new TravelOfferController();
$products = $controller->getAllProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List</title>
</head>
<body>
    <h1>Product List</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Image URL</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['id']; ?></td>
            <td><?= $product['nom_prod']; ?></td>
            <td><?= $product['description']; ?></td>
            <td><?= $product['prix']; ?></td>
            <td><?= $product['qte']; ?></td>
            <td><?= $product['url_img']; ?></td>
            <td><?= $product['cat']; ?></td>
            <td>
                <a href="update_product.php?id=<?= $product['id']; ?>">Update</a> |
                <a href="delete_product.php?id=<?= $product['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

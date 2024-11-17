<?php
include('../../controller/prod_controller.php'); 

$controller = new TravelOfferController();
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
    </style>
</head>
<body>
    <h1>Product List</h1>
    <table>
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
            <td><?= htmlspecialchars($product['id_prod']); ?></td>
            <td><?= htmlspecialchars($product['nom_prod']); ?></td>
            <td><?= htmlspecialchars($product['description']); ?></td>
            <td>$<?= number_format($product['prix'], 2); ?></td>
            <td><?= htmlspecialchars($product['qte']); ?></td>
            <td><a href="<?= htmlspecialchars($product['url_img']); ?>" target="_blank">View Image</a></td>
            <td><?= htmlspecialchars($product['categorie']); ?></td>
            <td>
                <a href="update_prod.php?id=<?= $product['id_prod']; ?>">Update</a> |
                <a href="delete_prod.php?id=<?= $product['id_prod']; ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

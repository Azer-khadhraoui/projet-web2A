<?php
require_once('../../controller/pannierC.php');

// Check if product is added to cart
if (isset($_POST['id_prod'])) {
    $id_prod = $_POST['id_prod'];
    $quantity = $_POST['quantity']; // Ensure quantity is provided
    $pannierC = new PannierC();
    $pannierC->addToPannier($id_prod, $quantity);
    echo "Product ID " . $id_prod . " added to cart with quantity " . $quantity . ".";
}

// Instantiate PannierC class
$pannierC = new PannierC();

// Fetch all products in the cart
$panniers = $pannierC->getAllPanniers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green&Pure - Pannier</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a.btn{
            text-decoration: none;
            color: #ff5e57;
        }

        a.btn:hover {
            text-decoration: underline;
            color:#ffffff;
        }

        .empty-message {
            text-align: center;
            font-size: 18px;
            color: #777;
            margin-top: 20px;
        }

        .action-btn {
            background-color: #ff5e57;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .action-btn:hover {
            background-color: #e54e4e;
        }
    </style>

</head>
<body>
    
    <nav>
        <img src="images/green&purelogo.png" alt="Green & Pure Logo" class="logo">
        <ul>
            <li><a href="index.html">HOME</a></li>
            <li><a href="products.php">PRODUCTS</a></li>
            <li><a href="forum.php">FORUM</a></li>
            <li><a href="#about">ABOUT</a></li>
            <li><a href="../login.php">RECLAMATION</a></li>
            <li><a href="index.html" id="gq">logout</a></li>
            <li><a href="pannier.php"><img src="images/cart_icon.jpg" alt="Panier" class="cart-icon"></a></li>
        </ul>
    </nav>

    <h1>Your Cart</h1>
    <div class="container">
        <?php if (!empty($panniers)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($panniers as $pannier): ?>
                        <?php
                        // Get product details by ID
                        $product = $pannierC->getProductById($pannier['id_prod']);
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($product['nom_prod']) ?></td>
                            <td><?= htmlspecialchars($product['prix']) ?></td>
                            <td><?= htmlspecialchars($pannier['qt_prod']) ?></td>
                            <td>In Person</td>
                            <td><?= htmlspecialchars($pannier['statut_pannier']) ?></td>
                            <td>
                                <a href="delete_pannier.php?id=<?= $pannier['id_pannier'] ?>" class="action-btn">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Green&Pure - All rights reserved.</p>
    </footer>

</body>
</html>

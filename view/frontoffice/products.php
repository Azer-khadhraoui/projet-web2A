<?php

include_once '../../config.php';

$conn = config::getConnexion(); 


$query = "SELECT id_categorie, nom_categorie FROM categorie ORDER BY nom_categorie";
$stmt = $conn->prepare($query);
$stmt->execute();


$categories = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $categories[$row['id_categorie']] = $row['nom_categorie'];  
}


$query = "SELECT * FROM produits ORDER BY categorie";
$stmt = $conn->prepare($query);
$stmt->execute();

$products = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $categoryName = $categories[$row['categorie']] ?? 'Unknown';  
    $products[$categoryName][] = $row; 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green&Pure - Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
 
    <nav>
        <img src="images/green&purelogo.png" alt="Green & Pure Logo" class="logo">
        <ul>
            <li><a href="index.php">HOME</a></li>
            <li><a href="products.php">PRODUCTS</a></li>
            <li><a href="forum.php">FORUM</a></li>
            <li><a href="#about">ABOUT</a></li>
            <li><a href="#contact">LOG IN</a></li>
            <li><a href="reclamation.php" id="gq">GET A QUOTE</a></li>
            <li><a href="panier.php"><img src="images/cart_icon.jpg" alt="Panier" class="cart-icon"></a></li>
        </ul>
    </nav>

   
    <section class="category-list">
        <?php foreach ($categories as $categoryId => $categoryName): ?>
            <button class="category-btn" onclick="showCategory('<?= htmlspecialchars($categoryName) ?>')">
                <?= htmlspecialchars($categoryName) ?>
            </button>
        <?php endforeach; ?>
    </section>

  
    <main class="product-main">
        <h1>Shop Now ....</h1>

        <?php foreach ($products as $category => $items): ?>
            <section class="product-category" id="<?= htmlspecialchars($category) ?>">
                
                <div class="product-catalog">
                    <?php foreach ($items as $product): ?>
                        <div class="product-card">
                            <img src="images/<?php echo $product['url_img']; ?>" alt="<?php echo $product['nom_prod']; ?>" class="product-image">
                            <h3><?= htmlspecialchars($product['nom_prod']) ?></h3>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                            <p><strong>Price:</strong> $<?= htmlspecialchars($product['prix']) ?></p>
                            <button>Add to Cart</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>

    <footer>
        <p>&copy; 2024 Green&Pure - All rights reserved.</p>
    </footer>

    <script src="script_prod.js"></script>
</body>
</html>

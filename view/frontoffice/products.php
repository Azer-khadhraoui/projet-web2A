<?php
include_once '../../config.php';

$conn = config::getConnexion(); 


$categories = [];
$query = "SELECT id_categorie, nom_categorie FROM categorie ORDER BY nom_categorie";
$stmt = $conn->prepare($query);

try {
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categories[$row['id_categorie']] = $row['nom_categorie'];
    }
} catch (Exception $e) {
    echo "Erreur lors de la récupération des catégories : " . $e->getMessage();
}

$search = $_GET['search'] ?? ''; 
$sortOrder = $_GET['sort_order'] ?? 'asc'; 
$sortBy = $_GET['sort_by'] ?? 'prix'; 


$query = "SELECT p.*, c.nom_categorie FROM produits p
          JOIN categorie c ON p.categorie = c.id_categorie";

// Appliquer la recherche
if ($search !== '') {
    $query .= " WHERE p.nom_prod LIKE :search"; 
}

// Appliquer le tri 
if ($sortBy) {
    if ($sortBy === 'nom_categorie') {
        
        $query .= " ORDER BY p.nom_prod " . ($sortOrder === 'desc' ? 'DESC' : 'ASC');
    } else {
        $query .= " ORDER BY p." . $sortBy . " " . ($sortOrder === 'desc' ? 'DESC' : 'ASC');
    }
}

$stmt = $conn->prepare($query);


if ($search !== '') {
    $stmt->bindValue(':search', '%' . $search . '%');
}


$filteredProducts = [];

try {
    $stmt->execute();
   
    $filteredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Erreur lors de la récupération des produits : " . $e->getMessage();
}

// Regroupement des produits par catégorie
$productsByCategory = [];
foreach ($filteredProducts as $product) {
    $productsByCategory[$product['categorie']][] = $product;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green&Pure - Products</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script_prod.js"> </script>
    <style>
        .search-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin: auto;
            max-width: 600px;
        }

        .search-bar input {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 25px;
            outline: none;
            margin-right: 10px;
            transition: border-color 0.3s ease;
        }

        .search-bar input:focus {
            border-color: #d57b20;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #d57b20;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .search-bar button:hover {
            background-color: #efa55b;
        }

        .product-catalog {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            max-width: 200px;
        }

        .category-btn {
            margin-bottom: 10px;
            cursor: pointer;
        }

        .sort-dropdown {
            padding: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <nav>
        <img src="images/green&purelogo.png" alt="Green & Pure Logo" class="logo">
        <ul>
            <li><a href="index.php">HOME</a></li>
            <li><a href="products.php">PRODUCTS</a></li>
            <li><a href="../frontoffice/PROJET MODULE/views/front office/index.php">FORUM</a></li>
            <li><a href="#about">ABOUT</a></li>
            <li><a href="#contact">LOG IN</a></li>
            <li><a href="reclamation.php" id="gq">GET A QUOTE</a></li>
            <li><a href="panier.php"><img src="images/cart_icon.jpg" alt="Panier" class="cart-icon"></a></li>
        </ul>
    </nav>
    <!--tri et recherche -->
    <form method="GET" action="" class="search-sort-form">
 
    <input type="text" id="search" name="search" placeholder="Rechercher un produit..." value="<?= htmlspecialchars($search) ?>">

    <
    <select name="sort_by">
        <option value="prix" <?= $sortBy === 'prix' ? 'selected' : '' ?>>Price</option>
        <option value="nom_categorie" <?= $sortBy === 'nom_categorie' ? 'selected' : '' ?>>Name</option>
    </select>

   
    <select name="sort_order">
        <option value="asc" <?= $sortOrder === 'asc' ? 'selected' : '' ?>>low to high </option>
        <option value="desc" <?= $sortOrder === 'desc' ? 'selected' : '' ?>>high to low</option>
    </select>

    <button type="submit" >Appliquer</button>
</form>

    <!-- Affichage des catégories -->
    <section class="category-list">
        <?php foreach ($categories as $categoryId => $categoryName): ?>
            <button class="category-btn" onclick="showCategory('<?= htmlspecialchars($categoryName) ?>')">
                <?= htmlspecialchars($categoryName) ?>
            </button>
        <?php endforeach; ?>
    </section>

    <!-- la recherche -->
    <?php if ($search !== ''): ?>
        <h1>Résultats de recherche pour "<?= htmlspecialchars($search) ?>" :</h1>
        <section class="search-results">
            <div class="product-catalog">
                <?php foreach ($filteredProducts as $product): ?>
                    <div class="product-card">
                        <img src="images/<?= htmlspecialchars($product['url_img']) ?>" alt="<?= htmlspecialchars($product['nom_prod']) ?>" class="product-image">
                        <h3><?= htmlspecialchars($product['nom_prod']) ?></h3>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                        <p><strong>Price:</strong> $<?= htmlspecialchars($product['prix']) ?></p>
                        <button class="add-to-cart" data-product-name="<?= htmlspecialchars($product['nom_prod']) ?>">Add to Cart</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Affichage des produits par catégories sans recherche -->
<?php if ($search === ''): ?>
    <main class="product-main">
        <h1>Shop Now ....</h1>
        <?php foreach ($categories as $categoryId => $categoryName): ?>
            <section class="product-category" id="<?= htmlspecialchars($categoryName) ?>">
                <h2><?= htmlspecialchars($categoryName) ?></h2>
                <div class="product-catalog">
                    <?php foreach ($productsByCategory[$categoryId] ?? [] as $product): ?>
                        <div class="product-card">
                            <img src="images/<?= htmlspecialchars($product['url_img']) ?>" alt="<?= htmlspecialchars($product['nom_prod']) ?>" class="product-image">
                            <h3><?= htmlspecialchars($product['nom_prod']) ?></h3>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                            <p><strong>Price:</strong> $<?= htmlspecialchars($product['prix']) ?></p>
                            <button class="add-to-cart" data-product-name="<?= htmlspecialchars($product['nom_prod']) ?>" >Add to Cart</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>
<?php endif; ?>

<!-- Message Box -->
<div class="message-box" id="messageBox">
    <p id="messageContent"></p>
    <button onclick="closeMessageBox()">Close</button>
</div>

<style>
    
    .message-box {
        display: none; 
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        padding: 20px;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
    }

    .message-box button {
        margin-top: 10px;
        padding: 10px 15px;
        background-color: #d57b20;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .message-box button:hover {
        background-color: #efa55b;
    }
</style>

<script>
   
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.onclick = function () {
            const productName = this.getAttribute('data-product-name');
            const messageBox = document.getElementById('messageBox');
            const messageContent = document.getElementById('messageContent');

           
            messageContent.textContent = `The product "${productName}" has been added to your cart!`;

          
            messageBox.style.display = 'block';
        };
    });

   
    function closeMessageBox() {
        const messageBox = document.getElementById('messageBox');
        messageBox.style.display = 'none';
    }

</script>


    <footer>
        <p>&copy; 2024 Green&Pure - All rights reserved.</p>
    </footer>

</body>
</html>

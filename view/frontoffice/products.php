<?php
include_once '../../config.php';

$conn = config::getConnexion(); // Connexion à la base de données

// Récupération des catégories
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

// Récupération des produits
$products = [];
$query = "SELECT * FROM produits ORDER BY categorie";
$stmt = $conn->prepare($query);

try {
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categoryName = $categories[$row['categorie']] ?? 'Unknown';
        $products[$categoryName][] = $row;
    }
} catch (Exception $e) {
    echo "Erreur lors de la récupération des produits : " . $e->getMessage();
}

// Gestion de la recherche
$search = isset($_POST['search']) ? trim($_POST['search']) : '';
$filteredProducts = [];

// Si une recherche est effectuée
if ($search !== '') {
    foreach ($products as $category => $items) {
        $filteredItems = array_filter($items, function($product) use ($search) {
            return stripos($product['nom_prod'], $search) !== false || stripos($product['description'], $search) !== false;
        });
        if (!empty($filteredItems)) {
            $filteredProducts = array_merge($filteredProducts, $filteredItems); // Ajout des résultats trouvés
        }
    }
} else {
    // Si aucune recherche, afficher tous les produits par catégories
    $filteredProducts = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green&Pure - Products</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script_prod.js"></script>
    <style>
        /* Search Bar Styles */
.search-bar {
    display: flex;
    justify-content: flex-end; /* Aligns the search bar to the right */
    align-items: center;
    margin: auto;
    max-width: 600px; /* Set a max width for the search bar */
}

.search-bar input {
    width: 80%; /* Make input take 80% of the search bar width */
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 25px;
    outline: none;
    margin-right: 10px; /* Space between the input and button */
    transition: border-color 0.3s ease; /* Smooth transition for border color */
}

.search-bar input:focus {
    border-color: #d57b20; /* Green border when focused */
}

.search-bar button {
    padding: 10px 20px;
    background-color: #d57b20; /* Green background */
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.search-bar button:hover {
    background-color: #efa55b; /* Darker green on hover */
}

.search-bar button:active {
    background-color: #efa55b; /* Dark green when button is pressed */
}
.search-and-category {
    display: block;
    justify-content: space-between; /* Space between the category buttons and search bar */
    align-items: center;
    margin: 20px;
    flex-wrap: wrap; /* Allow wrapping on smaller screens */
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

    <!-- Barre de recherche et catgory list-->
     <section class="search-and-category">
     </section>
     <!-- Liste des catégories -->
     <section class="category-list">
        <?php foreach ($categories as $categoryId => $categoryName): ?>
            <button class="category-btn" onclick="showCategory('<?= htmlspecialchars($categoryName) ?>')">
                <?= htmlspecialchars($categoryName) ?>
            </button>
        <?php endforeach; ?>
     <section class="search-bar">
        <form method="POST" action="products.php">
            <input 
                type="text" 
                name="search" 
                placeholder="Search for a product..." 
                value="<?= htmlspecialchars($search) ?>"
            >
            <button type="submit">Search</button>
        </form>
  
    </section>

     </section>
    

    <!-- Résultats de la recherche si elle est effectuée -->
    <?php if ($search !== ''): ?>
        <h1>Résultats de recherche pour "<?= htmlspecialchars($search) ?>" :</h1>
        <section class="search-results">
            <div class="product-catalog">
                <?php foreach ($filteredProducts as $product): ?>
                    <div class="product-card">
                        <img src="images/<?php echo htmlspecialchars($product['url_img']); ?>" alt="<?php echo htmlspecialchars($product['nom_prod']); ?>" class="product-image">
                        <h3><?= htmlspecialchars($product['nom_prod']) ?></h3>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                        <p><strong>Price:</strong> $<?= htmlspecialchars($product['prix']) ?></p>
                        <button>Add to Cart</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

   
    <!-- Affichage des produits par catégories, uniquement si aucune recherche n'est effectuée -->
    <?php if ($search === ''): ?>
        <main class="product-main">
            <h1>Shop Now ....</h1>
            <?php foreach ($products as $category => $items): ?>
    <section class="product-category" id="<?= htmlspecialchars($category) ?>" style="display: none;">
        <h2><?= htmlspecialchars($category) ?></h2>
        <div class="product-catalog">
            <?php foreach ($items as $product): ?>
                <div class="product-card">
                    <img src="images/<?php echo htmlspecialchars($product['url_img']); ?>" alt="<?php echo htmlspecialchars($product['nom_prod']); ?>" class="product-image">
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
    <?php endif; ?>

    <footer>
        <p>&copy; 2024 Green&Pure - All rights reserved.</p>
    </footer>

</body>
</html>

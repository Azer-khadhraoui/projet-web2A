<?php
include_once '../../config.php';

$conn = config::getConnexion();

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

// Récupération des paramètres pour la recherche, tri et pagination
$search = $_GET['search'] ?? '';
$sortOrder = $_GET['sort_order'] ?? 'asc';
$sortBy = $_GET['sort_by'] ?? 'prix';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Requête principale
$query = "SELECT p.*, c.nom_categorie 
          FROM produits p 
          JOIN categorie c ON p.categorie = c.id_categorie";

// Appliquer la recherche
if ($search !== '') {
    $query .= " WHERE p.nom_prod LIKE :search";
}

// Appliquer le tri
if ($sortBy === 'nom_categorie') {
    $query .= " ORDER BY c.nom_categorie " . ($sortOrder === 'desc' ? 'DESC' : 'ASC');
} else {
    $query .= " ORDER BY p." . $sortBy . " " . ($sortOrder === 'desc' ? 'DESC' : 'ASC');
}

// Ajouter la pagination
$query .= " LIMIT :limit OFFSET :offset";

$stmt = $conn->prepare($query);

// Liaison des paramètres
if ($search !== '') {
    $stmt->bindValue(':search', '%' . $search . '%');
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$filteredProducts = [];
try {
    $stmt->execute();
    $filteredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Erreur lors de la récupération des produits : " . $e->getMessage();
}

// Calcul du nombre total de produits pour la pagination
$countQuery = "SELECT COUNT(*) as total 
               FROM produits p 
               JOIN categorie c ON p.categorie = c.id_categorie";

if ($search !== '') {
    $countQuery .= " WHERE p.nom_prod LIKE :search";
}

$countStmt = $conn->prepare($countQuery);
if ($search !== '') {
    $countStmt->bindValue(':search', '%' . $search . '%');
}
$countStmt->execute();
$totalProducts = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalProducts / $limit);

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
    <script src="script_prod.js">
        
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
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            text-decoration: none;
            color: #d57b20;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .pagination a.active {
            background-color: #d57b20;
            color: white;
        }

        .pagination a:hover {
            background-color: #efa55b;
            color: white;
        }
        .search-sort-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 15px;
    margin: 20px auto;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 800px;
}

.search-sort-form input[type="text"] {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    max-width: 300px;
}

.search-sort-form select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    background-color: white;
    cursor: pointer;
}

.search-sort-form button {
    padding: 10px 20px;
    font-size: 16px;
    color: white;
    background-color: #d57b20;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-sort-form button:hover {
    background-color: #efa55b;
}
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

    <!-- Formulaire de recherche et tri -->
    <form method="GET" action="" class="search-sort-form">
        <input type="text" id="search" name="search" placeholder="Rechercher un produit..." value="<?= htmlspecialchars($search) ?>">
        <select name="sort_by">
            <option value="prix" <?= $sortBy === 'prix' ? 'selected' : '' ?>>Price</option>
            <option value="nom_categorie" <?= $sortBy === 'nom_categorie' ? 'selected' : '' ?>>Name</option>
        </select>
        <select name="sort_order">
            <option value="asc" <?= $sortOrder === 'asc' ? 'selected' : '' ?>>Low to High</option>
            <option value="desc" <?= $sortOrder === 'desc' ? 'selected' : '' ?>>High to Low</option>
        </select>
        <button type="submit">Apply</button>
    </form>
 <!-- Affichage des catégories -->
 <section class="category-list" style=" justify-content: center; 
    align-items: center;">
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


    <!-- Pagination -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&search=<?= htmlspecialchars($search) ?>&sort_by=<?= htmlspecialchars($sortBy) ?>&sort_order=<?= htmlspecialchars($sortOrder) ?>">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= htmlspecialchars($search) ?>&sort_by=<?= htmlspecialchars($sortBy) ?>&sort_order=<?= htmlspecialchars($sortOrder) ?>" 
           class="<?= $i === $page ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&search=<?= htmlspecialchars($search) ?>&sort_by=<?= htmlspecialchars($sortBy) ?>&sort_order=<?= htmlspecialchars($sortOrder) ?>">Next</a>
    <?php endif; ?>
</div>

    <!-- Message Box -->
<div class="message-box" id="messageBox">
    <p id="messageContent"></p>
    <button onclick="closeMessageBox()">Close</button>
</div>

    <footer>
        <p>&copy; 2024 Green&Pure - All rights reserved.</p>
    </footer>
</body>
</html>


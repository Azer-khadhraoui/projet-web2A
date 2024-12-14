<?php
include_once '../../config.php';

$conn = config::getConnexion();

// Fetch categories
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

// Get parameters for search, sort, and pagination
$search = $_GET['search'] ?? '';
$sortOrder = $_GET['sort_order'] ?? 'asc';
$sortBy = $_GET['sort_by'] ?? 'prix';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6; // Products per page per category
$offset = ($page - 1) * $limit;

// Fetch total products for pagination
$totalQuery = "SELECT COUNT(*) as total 
               FROM produits p 
               JOIN categorie c ON p.categorie = c.id_categorie";

if (!empty($search)) {
    $totalQuery .= " WHERE p.nom_prod LIKE :search";
}

$totalStmt = $conn->prepare($totalQuery);
if (!empty($search)) {
    $totalStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}

$totalStmt->execute();
$totalProducts = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalProducts / $limit);

// Fetch products for all categories with search filter applied
$productsByCategory = [];
foreach ($categories as $categoryId => $categoryName) {
    // Prepare query for each category
    $query = "SELECT p.*, c.nom_categorie 
              FROM produits p 
              JOIN categorie c ON p.categorie = c.id_categorie
              WHERE p.categorie = :categoryId";

    // Apply the search filter if present
    if (!empty($search)) {
        $query .= " AND p.nom_prod LIKE :search";
    }

    // Apply ordering and pagination
    if ($sortBy) {
        if ($sortBy === 'nom_categorie') {
            
            $query .= " ORDER BY p.nom_prod " . ($sortOrder === 'desc' ? 'DESC' : 'ASC');
        } else {
            $query .= " ORDER BY p." . $sortBy . " " . ($sortOrder === 'desc' ? 'DESC' : 'ASC');
        }
    }
    
    
    $query .= " LIMIT :limit OFFSET :offset";

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    // Bind search parameter if present
    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }

    try {
        $stmt->execute();
        $productsByCategory[$categoryId] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo "Erreur lors de la récupération des produits : " . $e->getMessage();
    }
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
    <script>
    document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.rating').forEach(ratingElement => {
        const productId = ratingElement.getAttribute('data-product-id');
        const ratingDisplay = document.getElementById(`rating-${productId}`);

        // Add click event listener to each star
        ratingElement.querySelectorAll('span').forEach(star => {
            star.addEventListener('click', () => {
                const selectedRating = parseInt(star.getAttribute('data-value'), 10);

                console.log("Selected Rating:", selectedRating); // Debugging

                // Highlight stars based on the selected rating
                updateStars(ratingElement, selectedRating);

                // Update the displayed rating
                if (ratingDisplay) {
                    ratingDisplay.textContent = `You rated this product: ${selectedRating} star${selectedRating > 1 ? 's' : ''}`;
                }
            });
        });
    });

    // Function to update star colors
    function updateStars(ratingElement, rating) {
        const stars = ratingElement.querySelectorAll('span');
        stars.forEach(star => {
            const starValue = parseInt(star.getAttribute('data-value'), 10);
            star.style.color = starValue <= rating ? '#ffa500' : '#ddd'; // Highlight selected stars
        });
    }
});

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
    .rating {
    display: inline-block;
    font-size: 1.5rem;
    color: #ddd;
    cursor: pointer;
}

.rating span {
    margin-right: 5px;
    transition: color 0.3s;
}

.rating span:hover,
.rating span:hover ~ span {
    color: #ffa500; /* Highlight color */
}

.rating[data-selected] span {
    color: #ffa500; /* Default selected color */
}

.rating[data-selected] span:hover,
.rating[data-selected] span:hover ~ span {
    color: #ffd700; /* Hover effect after selection */
}
.selected-rating {
    margin-top: 10px;
    font-size: 14px;
    color: #555;
    font-style: italic;
}

/* Product Category and Main Content */
.product-main {
    margin: 20px;
}
.product-category {
    display: none; /* Hidden by default, shown on category button click */
}
.product-catalog {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.product-card {
    background-color: #f8f8f8;
    border: 1px solid #ddd;
    padding: 15px;
    text-align: center;
    border-radius: 8px;
}
.product-card img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}
.product-card h3 {
    margin: 10px 0;
    font-size: 18px;
}
.product-card p {
    color: #555;
    font-size: 14px;
}
.product-card button {
    background-color: #d57b20;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
    border-radius: 4px;
}
.product-card button:hover {
    background-color: #efa55b;
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

    <?php foreach ($productsByCategory as $categoryId => $products): ?>
        <!-- Check if there are any products in the category -->
        <?php if (!empty($products)): ?>
            <section class="search-results">
                <h2><?= htmlspecialchars($categories[$categoryId]) ?> :</h2>
                <div class="product-catalog">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <img src="images/<?= htmlspecialchars($product['url_img']) ?>" alt="<?= htmlspecialchars($product['nom_prod']) ?>" class="product-image">
                            <h3><?= htmlspecialchars($product['nom_prod']) ?></h3>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                            <p><strong>Price:</strong> $<?= htmlspecialchars($product['prix']) ?></p>
                            <form action="../ajouter_pannier.php" method="POST">
                                <input type="hidden" name="id_prod" value="<?= $product['id_prod'] ?>">
                                <input type="hidden" name="qt_prod" value="1"> <!-- Default quantity to 1 -->
                                <input type="hidden" name="prix" value="<?= $product['prix'] ?>">
                                <label for="mode_paiement">Mode de paiement:</label>
                                <select name="mode_paiement" required>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="paypal">PayPal</option>
                                </select>
                                <button type="submit">Add to Cart</button>
                            </form>
                            <!-- Rating Bar -->
                            <div class="rating" data-product-id="<?= $product['id_prod'] ?>">
                                <span data-value="5">★</span>
                                <span data-value="4">★</span>
                                <span data-value="3">★</span>
                                <span data-value="2">★</span>
                                <span data-value="1">★</span>
                            </div>
                            <!-- Selected Rating Display -->
                            <p class="selected-rating" id="rating-<?= $product['id_prod'] ?>">No rating yet</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>


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
                            <form action="../ajouter_pannier.php" method="POST">
                                <input type="hidden" name="id_prod" value="<?= $product['id_prod'] ?>">
                                <input type="hidden" name="qt_prod" value="1"> <!-- Default quantity to 1 -->
                                <input type="hidden" name="prix" value="<?= $product['prix'] ?>">
                                <label for="mode_paiement">Mode de paiement:</label>
                                <select name="mode_paiement" required>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="paypal">PayPal</option>
                                </select>
                                <button type="submit">Add to Cart</button>
                            </form>
                            <!-- Rating Bar -->
                            <div class="rating" data-product-id="<?= $product['id_prod'] ?>">
                                <span data-value="5">★</span>
                                <span data-value="4">★</span>
                                <span data-value="3">★</span>
                                <span data-value="2">★</span>
                                <span data-value="1">★</span>
                            </div>
                            <!-- Selected Rating Display -->
                            <p class="selected-rating" id="rating-<?= $product['id_prod'] ?>">No rating yet</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>
<?php endif; ?>


<!-- HTML Pagination -->
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
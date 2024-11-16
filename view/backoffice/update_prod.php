<?php
include(__DIR__ . '/../controller/prod_controller.php');

$controller = new TravelOfferController();

if (isset($_GET['id'])) {
    $product = $controller->getProductById($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nom_prod = $_POST['nom_prod'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $qte = $_POST['qte'];
    $url_img = $_POST['url_img'];
    $cat = $_POST['cat'];

    $controller->updateProduct($id, $nom_prod, $description, $prix, $qte, $url_img, $cat);
    header('Location: list_products.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product</title>
</head>
<body>
    <h1>Update Product</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $product['id']; ?>">

        <label>Product Name:</label>
        <input type="text" name="nom_prod" value="<?= $product['nom_prod']; ?>" required><br>

        <label>Description:</label>
        <textarea name="description" required><?= $product['description']; ?></textarea><br>

        <label>Price:</label>
        <input type="number" step="0.01" name="prix" value="<?= $product['prix']; ?>" required><br>

        <label>Quantity:</label>
        <input type="number" name="qte" value="<?= $product['qte']; ?>" required><br>

        <label>Image URL:</label>
        <input type="text" name="url_img" value="<?= $product['url_img']; ?>" required><br>

        <label>Category:</label>
        <input type="number" name="cat" value="<?= $product['cat']; ?>" required><br>

        <button type="submit">Update Product</button>
    </form>
</body>
</html>

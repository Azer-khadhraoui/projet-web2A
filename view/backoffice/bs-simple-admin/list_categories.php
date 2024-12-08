<?php
include '../../../config.php' ; 
include 'D:\apache xampp\htdocs\projet-web2A\model\catg_mod.php'; 
include 'D:\apache xampp\htdocs\projet-web2A\controller\categorie_controller.php'; 

$controller = new CategoriesController();
$categories = $controller->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category List</title>
    <style>
  body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex; /* Ensures sidebar and content are aligned horizontally */
        background-color: #f8f9fa;
        color: #333;
    }

    .side_bar {
        width: 20%;
        background-color: #4CAF50;
        color: white;
        padding: 20px;
        box-sizing: border-box;
        min-height: 100vh; /* Stretches sidebar to full height */
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .side_bar .logo {
        display: flex;
        align-items: center; /* Vertically aligns logo and text */
        justify-content: center; /* Centers content horizontally */
        gap: 10px; /* Adds spacing between logo and text */
        margin-bottom: 20px;
    }

    .side_bar .logo img {
        width: 50px;
        height: auto;
    }

    .side_bar nav ul {
        list-style-type: none;
        padding: 0;
        width: 100%;
        text-align: center;
    }

    .side_bar nav li {
        margin: 10px 0;
    }

    .side_bar nav a {
        color: white;
        text-decoration: none;
        font-weight: bold;
        display: block;
        padding: 10px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .side_bar nav a:hover {
        background-color: black;
    }

    .content {
        width: 80%; /* Takes up the remaining space */
        padding: 20px;
        box-sizing: border-box;
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
    
<!-- BOOTSTRAP STYLES-->
<link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />    
</head>
<body>
<section class="side_bar">
        <div class="logo">
            <img src="assets/img/logoweb.jpg" alt="Logo" />
            <span>GREEN & PURE</span>
        </div>
        <nav>
            <ul>
            <li><a href="../backoffice/bs-simple-admin/index.html">Dashboard</a></li>
                <li><a href="../backoffice/bs-simple-admin/ui.html">UI Elements</a></li>
                <li><a href="blank.html">Blank Page</a></li>
                <li><a href="#">My Database</a></li>
                <li><a href="list_categories.php">Checkout Categories</a></li>
                <li><a href="list_products.php">Checkout Products</a></li>
            </ul>
        </nav>
    </section>
    <section class="content">
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category) { ?>
            <tr>
                <td><?= $category['id_categorie']; ?></td>
                <td><?= $category['nom_categorie']; ?></td>
                <td>
                    <a href="update_catg.php?id=<?= $category['id_categorie']; ?>">Edit</a> |
                    <a href="delete_catg.php?id=<?= $category['id_categorie']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="add_catg.php">Add New Category</a>
    </section>
    
    
</body>
</html>

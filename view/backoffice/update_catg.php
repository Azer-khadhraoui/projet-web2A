<?php
include('../../controller/categorie_controller.php'); 

$controller = new CategoriesController();


if (isset($_GET['id'])) {
    $category = $controller->getCategoryById($_GET['id']);
    if (!$category) {
        echo "Category not found.";
        exit();
    }
} else {
    echo "Category ID is missing.";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_categorie = $_POST['id_categorie'];
    $nom_categorie = $_POST['nom_categorie'];

    $controller->updateCategory($id_categorie, $nom_categorie);
    header('Location: list_categories.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Category</title>
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
        form {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #4CAF50;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
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
<script>
         function validate_cat(){
        const cat = document.getElementById('nom_categorie').value.trim();

        if (!expr.test(cat) || cat === "") {
            alert("La categorie n'est pas valide. elle doit contenir uniquement des lettres et des espaces !! .");
            test = false;
        }
        return test;
    }
    </script>
<body onsubmit='return validate_cat() ;'>
<a href="list_categories.php"><-----BACK </a>
    <h1>Update Category</h1>
    <form method="POST">
        <input type="hidden" name="id_categorie" value="<?= htmlspecialchars($category['id_categorie']); ?>">

        <label>Category Name:</label>
        <input type="text" name="nom_categorie" value="<?= htmlspecialchars($category['nom_categorie']); ?>">

        <button type="submit">Update Category</button>
    </form>
</body>
</html>
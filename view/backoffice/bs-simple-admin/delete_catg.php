<?php
include 'D:\apache xampp\htdocs\projet-web2A\controller\categorie_controller.php'; 

$controller = new CategoriesController();


if (isset($_GET['id'])) {
    $id_categorie = $_GET['id'];

    $controller->deleteCategory($id_categorie);
    header('Location: list_categories.php');  
    exit();
} else {
    echo "Category ID is missing.";
    exit();
}
?>


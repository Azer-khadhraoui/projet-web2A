<?php
include('../../controller/prod_controller.php'); 

if (isset($_GET['id'])) { 
    $controller = new TravelOfferController();
    
    $id = $_GET['id'];
    $controller->deleteProduct($id);
    
    header('Location: list_products.php');
    exit();
} else {
    echo "Product ID is missing.";
    exit();
}
?>

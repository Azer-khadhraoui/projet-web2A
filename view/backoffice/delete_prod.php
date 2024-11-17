<?php
include('../../controller/prod_controller.php'); 


if (isset($_GET['id'])) {
    $controller = new TravelOfferController();
    $controller->deleteProduct($_GET['id']);
    header('Location: list_products.php');
    exit();
}
?>

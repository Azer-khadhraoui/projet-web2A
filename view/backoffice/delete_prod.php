<?php
include('../../controller/prod_controller.php'); 


if (isset($_POST['id'])) {
    $controller = new TravelOfferController();
    $controller->deleteProduct($_POST['id_prod']);
    header('Location: list_products.php');
    exit();
}
?>

<?php
include '../../controller/prod_controller.php';
$products = new TravelOfferController();
$list ->delete_prod($_GET["id"]);
header('Location:list_products.php');

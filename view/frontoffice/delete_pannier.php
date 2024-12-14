<?php
require_once('../../controller/pannierC.php');

if (isset($_GET['id'])) {
    $id_pannier = $_GET['id'];

    $pannierC = new PannierC();
    $pannierC->deletePannier($id_prod);
    
    header('Location: pannier.php'); // Redirect back to the pannier page
    exit();
}
?>

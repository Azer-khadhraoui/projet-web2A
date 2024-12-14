<?php
require_once('C:\xampp\htdocs\pannier\controller\pannierC.php');
$PannierC= new PannierC();
$PannierC->deletePannier($_GET['id_pannier']);
header('Location: liste_pannier.php');




?>
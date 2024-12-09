<?php
require_once('..\..\..\controller/commandec.php');

// Vérifier si le paramètre "id_cmd" est défini et est valide
if (isset($_GET['id_cmd']) && is_numeric($_GET['id_cmd']) && $_GET['id_cmd'] > 0) {
    $commandeC = new CommandeC();
    $commandeC->deleteCommande($_GET['id_cmd']);
    header('Location: liste_commande.php');
} else {
    // Si l'ID n'est pas valide, afficher un message d'erreur
    echo "<div class='alert alert-danger'>ID Commande invalide.</div>";
    exit;
}
?>

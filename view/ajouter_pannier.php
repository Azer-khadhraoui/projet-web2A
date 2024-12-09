<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../controller/pannierC.php');
    require_once('../model/pannier.php');

    $pannier = null;
    $pannierC = new PannierC();

    if (
        isset($_POST["id_prod"]) &&
        isset($_POST["qt_prod"]) &&
        isset($_POST["mode_paiement"]) 
    ) {
        if (
            !empty($_POST["id_prod"]) &&
            !empty($_POST["qt_prod"]) &&
            !empty($_POST["mode_paiement"]) 
        ) {

            $pannier = new Pannier(
                $_POST["id_prod"],
                $_POST["qt_prod"],
                $_POST["mode_paiement"]
            );

            $pannierC->addPannier($pannier);

             header('Location: ../view/frontoffice/commande.php?id_prod=' . $_POST["id_prod"]);

        } else {
            echo '<script>
                    alert("Tous les champs doivent être remplis");
                    window.location.href = "../view/frontoffice/ajouter_pannier.php";
                  </script>';
        }
    } else {
        echo '<script>
                alert("Veuillez remplir tous les champs du formulaire");
                window.location.href = "../view/frontoffice/ajouter_pannier.php";
              </script>';
    }
}
?>

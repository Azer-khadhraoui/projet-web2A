<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../controller/pannierC.php');
    require_once('../model/products_mod.php');
    //require_once('../model/pannier.php');

    $product = null;
    $pannierC = new PannierC();

    if (
        isset($_POST["id_prod"]) &&
        isset($_POST["qt_prod"]) &&
        isset($_POST["mode_paiement"]) &&
        isset($_POST["prix"])
    ) {
        if (
            !empty($_POST["id_prod"]) &&
            !empty($_POST["qt_prod"]) &&
            !empty($_POST["mode_paiement"]) &&
            !empty($_POST["prix"])
        ) {
            $total = $_POST["qt_prod"] * $_POST["prix"];

            $product = new prod_mang(
                $_POST["id_prod"],
                null, // Assuming nom_prod is not needed here
                null, // Assuming description is not needed here
                $_POST["prix"],
                $_POST["qt_prod"],
                null, // Assuming url_img is not needed here
                null  // Assuming cat is not needed here
            );

            $pannierC->addProductToPannier($product, $_POST["mode_paiement"], $total);

            header('Location: ../view/frontoffice/pannier.php?id_prod=' . $_POST["id_prod"]);
            exit();

        } else {
            echo '<script>
                    alert("Tous les champs doivent être remplis");
                    window.location.href = "../view/frontoffice/ajouter_pannier.php";
                  </script>';
        }
    } else {
        echo '<script>
                alert("Tous les champs doivent être remplis");
                window.location.href = "../view/frontoffice/ajouter_pannier.php";
              </script>';
    }
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../controller/commandeC.php');
    require_once('../model/commande.php');

    $commande = null;
    $commandeC = new CommandeC();

    if (
        isset($_POST["date_cmd"]) &&
        isset($_POST["stat_cmd"]) &&
        isset($_POST["adress_cmd"]) &&
        isset($_POST["desc_cmd"])
    ) {
        if (
            !empty($_POST["date_cmd"]) &&
            !empty($_POST["stat_cmd"]) &&
            !empty($_POST["adress_cmd"]) &&
            !empty($_POST["desc_cmd"])
        ) {

            $commande = new Commande(
                $_POST["date_cmd"],
                $_POST["stat_cmd"],
                $_POST["adress_cmd"],
                $_POST["desc_cmd"]
            );

            $commandeC->addCommande($commande);

            header('Location: ../view/backoffice/bs-simple-admin/liste_commande.php');
        } else {
            echo '<script>
                    alert("Tous les champs doivent être remplis");
                    window.location.href = "../view/frontoffice/ajouter_commande.php";
                  </script>';
        }
    } else {
        echo '<script>
                alert("Veuillez remplir tous les champs du formulaire");
                window.location.href = "../view/frontoffice/ajouter_commande.php";
              </script>';
    }
}
?>

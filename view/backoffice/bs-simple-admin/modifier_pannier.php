<?php
// Including required files
require_once('C:\xampp\htdocs\pannier\controller\pannierC.php');
require_once('C:\xampp\htdocs\pannier\model\pannier.php');

$pannierC = new PannierC();
$pannier = null;

// Check if the panier ID is passed in the URL
if (isset($_GET['id_pannier'])) {
    $id_pannier = $_GET['id_pannier'];
    // Fetch the panier details based on ID
    $pannier = $pannierC->showPannier($id_pannier);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate that all fields are set and not empty
    if (
        isset($_POST["id_prod"]) &&
        isset($_POST["qt_prod"]) &&
        isset($_POST["mode_paiement"]) &&
        isset($_POST["statut_pannier"]) &&
        !empty($_POST["id_prod"]) &&
        !empty($_POST["qt_prod"]) &&
        !empty($_POST["mode_paiement"]) &&
        !empty($_POST["statut_pannier"])
    ) {
        // Create a new Pannier object with updated data
        $pannier = new Pannier(
            $_POST["id_prod"],
            $_POST["qt_prod"],
            $_POST["mode_paiement"],
            $_POST["statut_pannier"]
        );
        // Update the panier in the database
        $pannierC->updatePannier($pannier, $id_pannier);
        // Redirect to the list of paniers after successful update
        header('Location: liste_pannier.php');
        exit(); // Ensure that the header is sent after processing
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier Panier</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <div id="wrapper">
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <span class="logout-spn">
                    <a href="#" style="color:#fff;">LOGOUT</a>
                </span>
            </div>
        </div>

        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li><a href="#"><i class="fa fa-qrcode"></i>Gestion des paniers</a></li>
                    <li><a href="#"><i class="fa fa-bar-chart-o"></i>Gestion des commandes</a></li>
                </ul>
            </div>
        </nav>

        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Modifier Panier</h2>
                    </div>
                </div>
                <hr />
                <br />
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <h5>Modifier les détails du panier</h5>
                        <?php
                        // Check if panier data was fetched
                        if ($pannier) {
                        ?>
                            <form action="" method="POST">
                                <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                        <td><label for="id_prod">ID Produit:</label></td>
                                        <td><input type="text" id="id_prod" name="id_prod" value="<?php echo $pannier['id_prod']; ?>" required /></td>
                                    </tr>
                                    <tr>
                                        <td><label for="qt_prod">Quantité:</label></td>
                                        <td><input type="number" id="qt_prod" name="qt_prod" value="<?php echo $pannier['qt_prod']; ?>" required /></td>
                                    </tr>
                                    <tr>
                                        <td><label for="mode_paiement">Mode de Paiement:</label></td>
                                        <td>
                                            <select id="mode_paiement" name="mode_paiement">
                                                <option value="Carte" <?php if ($pannier['mode_paiement'] == 'Carte') echo 'selected'; ?>>Carte</option>
                                                <option value="espece" <?php if ($pannier['mode_paiement'] == 'espece') echo 'selected'; ?>> Espece</option>

                                                <option value="paypal" <?php if ($pannier['mode_paiement'] == 'paypal') echo 'selected'; ?>>Paypal</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="statut_pannier">Statut de Panier:</label></td>
                                        <td>
                                            <select id="statut_pannier" name="statut_pannier">
                                                <option value="en_attente" <?php if ($pannier['statut_pannier'] == 'en_attente') echo 'selected'; ?>>En attente</option>
                                                <option value="valide" <?php if ($pannier['statut_pannier'] == 'valide') echo 'selected'; ?>>validé</option>
                                                <option value="annule" <?php if ($pannier['statut_pannier'] == 'annule') echo 'selected'; ?>>Annulé</option>
                                                <option value="Terminé" <?php if ($pannier['statut_pannier'] == 'Terminé') echo 'selected'; ?>>Terminé</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                                <input class="btn btn-outline-primary btn-sm mb-0" type="submit" value="Mettre à jour" />
                                <a class="btn btn-outline-danger btn-sm mb-0" href="liste_pannier.php">Annuler</a>
                            </form>
                        <?php
                        } else {
                            echo '<div class="alert alert-danger">Panier introuvable.</div>';
                        }
                        ?>
                    </div>
                </div>
                <hr />
            </div>
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->

    <!-- SCRIPTS - AT THE BOTTOM TO REDUCE THE LOAD TIME -->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>

<?php
// Ensure that the "id_pannier" parameter is set and is a valid positive integer
if (isset($_GET["id_pannier"]) && is_numeric($_GET["id_pannier"]) && $_GET["id_pannier"] > 0) {
    $id_pannier = (int)$_GET["id_pannier"]; // Cast to integer
} else {
    // If the ID is not valid, display an error and exit
    echo "<div class='alert alert-danger'>ID Panier invalide.</div>";
    exit;
}

require_once('C:/xampp/htdocs/pannier/controller/pannierC.php');

// Initialize the panier controller
$pannierC = new PannierC();

// Fetch the panier details using the provided ID
$pannier = $pannierC->showPannier($id_pannier);

// Check if the panier exists and is an array
if (!$pannier || !is_array($pannier)) {
    // Display an error message if no panier is found
    echo "<div class='alert alert-danger'>Aucun panier trouvé pour cet ID.</div>";
    exit; // Stop further processing if panier is not found
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panier Détails</title>
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
        <!-- Top Navbar -->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <span class="logout-spn"><a href="#" style="color:#fff;">LOGOUT</a></span>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li><a href="#"><i class="fa fa-qrcode "></i>Gestion des panniers</a></li>
                    <li><a href="#"><i class="fa fa-bar-chart-o"></i>Gestion des commandes</a></li>
                </ul>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Détails du Panier</h2>
                    </div>
                </div>
                <hr />
                
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <h5>Informations du Panier</h5>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Champ</th>
                                    <th>Détail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ID Panier</td>
                                    <td><?php echo htmlspecialchars($pannier['id_pannier']); ?></td>
                                </tr>
                                <tr>
                                    <td>ID Produit</td>
                                    <td><?php echo htmlspecialchars($pannier['id_prod']); ?></td>
                                </tr>
                                <tr>
                                    <td>Quantité</td>
                                    <td><?php echo htmlspecialchars($pannier['qt_prod']); ?></td>
                                </tr>
                                <tr>
                                    <td>Mode de Paiement</td>
                                    <td><?php echo htmlspecialchars($pannier['mode_paiement']); ?></td>
                                </tr>
                                <tr>
                                    <td>Statut du Panier</td>
                                    <td><?php echo htmlspecialchars($pannier['statut_pannier']); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr />
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© 2024 Your Company</p>
    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>

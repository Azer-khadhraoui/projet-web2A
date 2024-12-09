<?php
// Ensure that the "id_cmd" parameter is set and is a valid positive integer
if (isset($_GET["id_cmd"]) && is_numeric($_GET["id_cmd"]) && $_GET["id_cmd"] > 0) {
    $id_cmd = (int)$_GET["id_cmd"]; // Cast to integer
} else {
    // If the ID is not valid, display an error and exit
    echo "<div class='alert alert-danger'>ID Commande invalide.</div>";
    exit;
}

require_once('..\..\..\controller/commandec.php');

// Initialize the commande controller
$commandeC = new CommandeC();

// Fetch the commande details using the provided ID
$commande = $commandeC->showCommande($id_cmd);

// Check if the commande exists and is an array
if (!$commande || !is_array($commande)) {
    // Display an error message if no commande is found
    echo "<div class='alert alert-danger'>Aucune commande trouvée pour cet ID.</div>";
    exit; // Stop further processing if commande is not found
}
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>commande Détails</title>
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
                    <li><a href="liste_commande.php"><i class="fa fa-qrcode "></i>Gestion des commandes</a></li>
                    <li><a href="liste_pannier.php"><i class="fa fa-bar-chart-o"></i>Gestion des panniers</a></li>
                </ul>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Détails du commande</h2>
                    </div>
                </div>
                <hr />
                
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <h5>Informations du commande</h5>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Champ</th>
                                    <th>Détail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ID commande</td>
                                    <td><?php echo htmlspecialchars($commande['id_cmd']); ?></td>
                                </tr>
                                <tr>
                                    <td>Date commande </td>
                                    <td><?php echo htmlspecialchars($commande['date_cmd']); ?></td>
                                </tr>
                                <tr>
                                    <td>adresse commande</td>
                                    <td><?php echo htmlspecialchars($commande['adress_cmd']); ?></td>
                                </tr>
                                <tr>
                                    <td>statut commande</td>
                                    <td><?php echo htmlspecialchars($commande['stat_cmd']); ?></td>
                                </tr>
                                <tr>
                                    <td>Description commande</td>
                                    <td><?php echo htmlspecialchars($commande['desc_cmd']); ?></td>
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

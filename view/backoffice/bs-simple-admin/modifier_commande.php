<?php
// Including required files
require_once('D:\apache xampp\htdocs\projet-web2A\controller\commandeC.php');
require_once('D:\apache xampp\htdocs\projet-web2A\model\commande.php');

$commandeC = new CommandeC();
$commande = null;

// Check if the commande ID is passed in the URL
if (isset($_GET['id_cmd']) && is_numeric($_GET['id_cmd']) && $_GET['id_cmd'] > 0) {
    $id_cmd = $_GET['id_cmd'];
    // Fetch the commande details based on ID
    $commande = $commandeC->showCommande($id_cmd);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate that all fields are set and not empty
    if (
        isset($_POST["date_cmd"]) &&
        isset($_POST["stat_cmd"]) &&
        isset($_POST["adress_cmd"]) &&
        isset($_POST["desc_cmd"]) &&
        !empty($_POST["date_cmd"]) &&
        !empty($_POST["stat_cmd"]) &&
        !empty($_POST["adress_cmd"]) &&
        !empty($_POST["desc_cmd"])
    ) {
        // Create a new Commande object with updated data
        $commande = new Commande(
            $_POST["date_cmd"],
            $_POST["stat_cmd"],
            $_POST["adress_cmd"],
            $_POST["desc_cmd"]
        );
        // Update the commande in the database
        $commandeC->updateCommande($commande, $id_cmd);
        // Redirect to the list of commandes after successful update
        header('Location: D:\apache xampp\htdocs\projet-web2A\view\backoffice\bs-simple-admin\liste_pannier.php');
        exit(); // Ensure that the header is sent after processing
    }
}
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modify command</title>
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
                    <li><a href="#"><i class="fa fa-qrcode"></i>Gestion of command</a></li>
                    <li><a href="#"><i class="fa fa-bar-chart-o"></i>Gestion of carts</a></li>
                </ul>
            </div>
        </nav>

        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Modify command</h2>
                    </div>
                </div>
                <hr />
                <br />
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <h5>Modify  details of command</h5>
                        <?php
                        // Check if panier data was fetched
                        if ($commande) {
                            ?>
                            <form action="" method="POST">
                                <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                        <td><label for="id_cmd">ID command:</label></td>
                                        <td><input type="text" id="id_cmd" name="id_cmd"
                                                value="<?php echo $commande['id_cmd']; ?>"    readonly/></td>
                                    </tr>
                                    <tr>
                                        <td><label for="date_cmd">Date of command:</label></td>
                                        <td><input type="date" id="date_cmd" name="date_cmd"
                                                value="<?php echo $commande['date_cmd']; ?>" required /></td>
                                    </tr>
                                    <tr>
                                        <td><label for="adress_cmd">Adress of command:</label></td>
                                        <td><input type="text" id="adress_cmd" name="adress_cmd"
                                                value="<?php echo $commande['adress_cmd']; ?>" required /></td>
                                    </tr>
                                    <tr>
                                        <td><label for="desc_cmd">description of command:</label></td>
                                        <td><input type="text" id="desc_cmd" name="desc_cmd"
                                                value="<?php echo $commande['desc_cmd']; ?>" required /></td>
                                    </tr>
                                    <tr>
                                        <td><label for="stat_cmd">Status of Command:</label></td>
                                        <td>
                                            <select id="stat_cmd" name="stat_cmd">
                                                <option value="en_attente" <?php if ($commande['stat_cmd'] == 'en_attente')
                                                    echo 'selected'; ?>>waiting</option>
                                                <option value="valide" <?php if ($commande['stat_cmd'] == 'valide')
                                                    echo 'selected'; ?>>done</option>
                                                <option value="annule" <?php if ($commande['stat_cmd'] == 'annule')
                                                    echo 'selected'; ?>>discard</option>
                                                <option value="termine" <?php if ($commande['stat_cmd'] == 'termine')
                                                    echo 'selected'; ?>>finished</option>
                                            </select>
                                        </td>
                                    </tr>

                                </table>
                                <input class="btn btn-outline-primary btn-sm mb-0" type="submit" value="Mettre à jour" />
                                <a class="btn btn-outline-danger btn-sm mb-0" href="liste_pannier.php">discard</a>
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
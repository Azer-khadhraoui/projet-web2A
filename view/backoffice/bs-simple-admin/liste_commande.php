<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion of Commandes</title>
    <!-- BOOTSTRAP STYLES -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES -->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
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
        <!-- /. NAV TOP -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li><a href="#"><i class="fa fa-qrcode"></i> Gestion of command</a></li>
                    <li><a href="liste_pannier.php"><i class="fa fa-bar-chart-o"></i> Gestion ofcarts</a></li>
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Manipulation of command</h2>
                    </div>
                </div>
                <hr />

                <!-- Logique PHP pour récupérer les données -->
                <?php
                // Connexion à la base de données
                try {
                    $pdo = new PDO("mysql:host=localhost;dbname=greenandpure", "root", "");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die("Erreur de connexion : " . $e->getMessage());
                }

                // Déterminer l'ordre de tri (par défaut : croissant)
                $sortOrder = isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'DESC' : 'ASC';

                // Requête pour récupérer les commandes triées
                $query = $pdo->prepare("SELECT * FROM commande ORDER BY prix_total $sortOrder");
                $query->execute();
                $listecommande = $query->fetchAll();
                ?>

                <!-- Formulaire pour le tri -->
                <form method="GET" action="">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="sort">sort by price :</label>
                            <select name="sort" id="sort" class="form-control" onchange="this.form.submit()">
                                <option value="asc" <?php if ($sortOrder == 'ASC') echo 'selected'; ?>>Croissant</option>
                                <option value="desc" <?php if ($sortOrder == 'DESC') echo 'selected'; ?>>Décroissant</option>
                            </select>
                        </div>
                    </div>
                </form>
                <br />

                <!-- Tableau des commandes -->
                <div class="row">
                    <div class="col-lg-12">
                        <h5>Table of command</h5>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Date </th>
                                    <th>Statut </th>
                                    <th>Adress</th>
                                    <th>Description </th>
                                    <th>Price</th>
                                    <th>details</th>
                                    <th>Modify</th>
                                    <th>delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listecommande as $commande): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($commande['id_cmd']) ?></td>
                                        <td><?= htmlspecialchars($commande['date_cmd']) ?></td>
                                        <td><?= htmlspecialchars($commande['stat_cmd']) ?></td>
                                        <td><?= htmlspecialchars($commande['adress_cmd']) ?></td>
                                        <td><?= htmlspecialchars($commande['desc_cmd']) ?></td>
                                        <td><?= htmlspecialchars($commande['prix_total']) ?></td>
                                        <td><a href="show_commande.php?id_cmd=<?= $commande['id_cmd'] ?>">Détails</a></td>
                                        <td><a href="modifier_commande.php?id_cmd=<?= $commande['id_cmd'] ?>">Modify</a></td>
                                        <td><a href="supprimer_commande.php?id_cmd=<?= $commande['id_cmd'] ?>">delete</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>

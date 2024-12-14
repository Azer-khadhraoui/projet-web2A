<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Simple Responsive Admin</title>
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
              
                 <span class="logout-spn" >
                  <a href="#" style="color:#fff;">LOGOUT</a>  

                </span>
            </div>
        </div>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                 


                    
                <li>
                        <a href="index.html"><i class="fa fa-bar-chart-o"></i>return to dashboard</a>
                    </li>
                     <li>
                        <a href="#"><i class="fa fa-qrcode "></i>Gestion des panniers</a>
                    </li>
                    <li>
                        <a href="liste_commande.php"><i class="fa fa-bar-chart-o"></i>Gestion des commandes</a>
                    </li>

                 
                </ul>
                            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
          <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Manipulation des panniers </h2>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr />
    
<br />
 
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <h5>Table des paniers ajoutés récemment </h5>
                        <table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>ID Panier </th>
            <th>ID Produit</th>
            <th>Quantité</th>
            <th>Mode de Paiement</th>
            <th>Statut de Panier</th>
            <th>affichage detaillé</th>
            <th> Modifier panier</th>
            <th>Supprimer panier</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Inclure le contrôleur pour accéder aux fonctions de manipulation des paniers
        require_once('D:/apache xampp/htdocs/projet-web2A/controller/pannierC.php');
        $pannierC = new PannierC();

        // Récupérer tous les paniers de la base de données
        $listePaniers = $pannierC->listePanniers();

        // Parcourir la liste des paniers et remplir les lignes du tableau
        foreach ($listePaniers as $pannier) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($pannier['id_pannier']) . '</td>';
            echo '<td>' . htmlspecialchars($pannier['id_prod']) . '</td>';
            echo '<td>' . htmlspecialchars($pannier['qt_prod']) . '</td>';
            echo '<td>' . htmlspecialchars($pannier['mode_paiement']) . '</td>';
            echo '<td>' . htmlspecialchars($pannier['statut_pannier']) . '</td>';
            echo '<td><a href="show_pannier.php?id_pannier=' . $pannier['id_pannier'] . '">Détails</a></td>';
            echo '<td><a href="modifier_pannier.php?id_pannier=' . $pannier['id_pannier'] . '">Modifier</a></td>';
            echo '<td><a href="supprimer_pannier.php?id_pannier=' . $pannier['id_pannier'] . '">Supprimer</a></td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>

                    </div>
                    
                </div>
                <!-- /. ROW  -->
                <hr>
  
                <!-- /. ROW  -->
                <hr />
           
           

            </div>
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
    <div class="footer">
      
     
        </div>
          

     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
   
</body>
</html>

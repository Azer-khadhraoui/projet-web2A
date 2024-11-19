<?php
include '../../Controller/usercontroller.php';

if (isset($_GET['cin'])) {
    $cin = $_GET['cin'];

    $controller = new usercontroller();
    $user = $controller->getUserByCin($cin);

    if ($user) {
        $fname = $user['nom'];
        $lname = $user['prenom'];
        $num = $user['numero'];
        $pwd = $user['pwd'];
        $role = $user['role'];
    } else {
        die("Utilisateur non trouvé.");
    }
} else {
    die("Aucun CIN fourni pour la modification.");
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>backoffice</title>
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
        <div class="navbar navbar-inverse navbar-fixed-top" style="background-color: #4CAF50; padding: 10px 0;">
            <div class="adjust-nav" style="display: flex; justify-content: space-between; align-items: center; width: 100%; position: relative;">
                <div class="navbar-header" style="display: flex; align-items: center;">
                    <a class="navbar-brand" href="#">
                        <img src="assets/img/logoweb.jpg" style="width: 50px; height: auto;" alt="Logo" />
                    </a>
                </div>
                <span class="navbar-text" style="color: #fff; font-size: 22px; position: absolute; left: 50%; transform: translateX(-50%);">
                    Strategic Minds presents: GREEN & PURE
                </span>
                <span class="logout-spn" style="position: absolute; right: 20px; top: 15px;">
                    <a href="#" style="color:#fff;">LOGOUT</a>
                </span>
                <span class="return-dashboard" style="position: absolute; right: 120px; top: 15px;">
                    <a href="../backoffice/bs-simple-admin/index.html" class="btn btn-primary">Return to Dashboard</a>
                </span>
            </div>
        </div>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="active-link">
                        <a href="../backoffice/bs-simple-admin/index.html" style="color: #4CAF50;"><i class="fa fa-desktop" style="color: #4CAF50;"></i>Dashboard <span class="badge" style="color: #4CAF50;">Included</span></a>
                    </li>
                   
                    <li>
                        <a href="../../view/frontoffice/affichage.php" style="color: #4CAF50;"><i class="fa fa-qrcode" style="color: #4CAF50;"></i>my data base</a>
                    </li>
                
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Update User</h2>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr />
                <div class="row">
                    <div class="col-lg-12">
                        <form action="update.php" method="POST">
                            <input type="hidden" name="cin" value="<?php echo $cin; ?>">
                            <div class="form-group">
                                <label for="fname">First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="lname">Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="num">Number</label>
                                <input type="text" class="form-control" id="num" name="num" value="<?php echo $num; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Password</label>
                                <input type="password" class="form-control" id="pwd" name="pwd" value="<?php echo $pwd; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <input type="text" class="form-control" id="role" name="role" value="<?php echo $role; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </form>
                    </div>
                </div>
                <!-- /. ROW  -->
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <div class="footer">
        <div class="row">
            <div class="col-lg-12">
                &copy;  2014 yourdomain.com | Design by: <a href="http://binarytheme.com" style="color:#fff;" target="_blank">azer khadhraoui</a>
            </div>
        </div>
    </div>
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update user</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center">update user</h2>
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
    </div>
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
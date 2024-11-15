<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage des Utilisateurs</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
</head>
<body>
    <!-- Bande verte avec le logo -->
    <div class="navbar navbar-inverse navbar-fixed-top" style="background-color: #4CAF50; padding: 10px 0;">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <img src="assets/img/logoweb.jpg" style="width: 50px; height: auto;" alt="Logo" />
                </a>
            </div>
        </div>
    </div>
    <!-- Fin de la bande verte avec le logo -->

    <div class="container" style="margin-top: 70px;">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center">Liste des Utilisateurs</h2>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>CIN</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Number</th>
                            <th>Password</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include '../../Controller/usercontroller.php';
                        $controller = new UserController();
                        $list = $controller->listUsers();

                        foreach ($list as $row) {
                            $cin = $row['cin'];
                            $fname = $row['nom'];
                            $lname = $row['prenom'];
                            $num = $row['numero'];
                            $pwd = $row['pwd'];
                            $role = $row['role'];

                            echo "<tr>
                                    <td>{$cin}</td>
                                    <td>{$fname}</td>
                                    <td>{$lname}</td>
                                    <td>{$num}</td>
                                    <td>{$pwd}</td>
                                    <td>{$role}</td>
                                    <td>
                                        <a href='delete.php?cin={$cin}' class='btn btn-danger btn-sm' onclick=\"return confirm('Êtes-vous sûr de supprimer cet utilisateur ?');\">Supprimer</a>
                                        <a href='edit.php?cin={$cin}' class='btn btn-primary btn-sm'>Modifier</a>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <style>
        .navbar-custom {
            background-color: #4CAF50;
            padding: 10px 0;
        }
        .navbar-custom .navbar-brand img {
            margin-top: -17px; 
            width: 60px;
            height: 60px;
        }
        .navbar-custom .navbar-text {
            color: #fff;
            font-size: 48px;
            margin-left: 400px;
        }
        .table-custom {
            margin-top: 120px; 
        }
        .table-custom th, .table-custom td {
            text-align: center;
            vertical-align: middle;
        }
        .btn-custom {
            margin: 0 5px;
        }
        .ml-auto {
            margin-left: 1100px;
        }
    </style>
</head>
<body>
    <!-- Bande verte avec le logo et le titre -->
    <nav class="navbar navbar-expand-lg navbar-custom navbar-fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="assets/img/logoweb.jpg" alt="Logo" />
            </a>
            <span class="navbar-text">
                Users List
            </span>
            <div class="ml-auto">
                <a href="../backoffice/bs-simple-admin/index.html" class="btn btn-primary">Return to Dashboard</a>
            </div>
        </div>
    </nav>
    <!-- Fin de la bande verte avec le logo et le titre -->

    <div class="container table-custom">
        <div class="row">
            <div class="col-lg-12">
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
                                        <a href='delete.php?cin={$cin}' class='btn btn-danger btn-sm btn-custom' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>
                                        <a href='edit.php?cin={$cin}' class='btn btn-primary btn-sm btn-custom'>Edit</a>
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
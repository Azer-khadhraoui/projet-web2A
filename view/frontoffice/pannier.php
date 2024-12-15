<?php
$host = 'localhost';
$dbname = 'greenandpure';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit();
}

$query = "SELECT id_prod, nom_prod AS nom_prod,qte AS qt_prod, description AS description, prix AS prix_prod, url_img AS image_prod FROM produits";
$stmt = $pdo->prepare($query);
$stmt->execute();

$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
$error = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('D:\apache xampp\htdocs\projet-web2A\controller\pannierC.php');
    require_once('D:\apache xampp\htdocs\projet-web2A\model\pannier.php');

    $pannier = null;
    $pannierC = new PannierC();

    if (
        isset($_POST["id_prod"]) &&
        isset($_POST["qt_prod"]) &&
        isset($_POST["mode_paiement"])
    ) {
        if (
            !empty($_POST["id_prod"]) &&
            !empty($_POST["qt_prod"]) &&
            !empty($_POST["mode_paiement"])
        ) {
            $qtProd = $_POST["qt_prod"];
            if (filter_var($qtProd, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
                $pannier = new Pannier(
                    $_POST["id_prod"],
                    $_POST["qt_prod"],
                    $_POST["mode_paiement"]
                );

                $pannierC->addPannier($pannier);
                $successMessage = "Produit ajouté au panier avec succès !";
                header('Location: ../../view/frontoffice/commande.php');

            } else {
                $error = "La quantité doit être un nombre entier positif.";
            }
        } else {
            $error = "You must fill all..";
        }
    } else {
        $error = "you must fill all.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css"
        media="screen">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
</head>

<body class="main-layout">
    <header>
        <div class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                        <div class="full">
                            <div class="center-desk">
                                <div class="logo">
                                    <a href="index.html"><img src="images/logo.png" alt="Logo"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                        <nav class="navigation navbar navbar-expand-md navbar-dark ">
                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarsExample04">
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item">
                                        <a class="nav-link" href="index.html">Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="about.html">About</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="products.php">Products</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="contact.html">Contact</a>
                                    </li>
                                    <li class="nav-item active">
                                        <a class="nav-link" href="pannier.php">cart</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="titlepage">
                    <h2>Add to Cart</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="pannier.php" method="POST" class="main_form">
                    <div class="form-group">
                        <label for="id_prod">Products</label>
                        <select id="id_prod" name="id_prod" class="form-control selectpicker" data-live-search="true"
                            border-raduis="none">
                            <?php foreach ($produits as $produit): ?>
                                <option value="<?= $produit['id_prod'] ?>" data-content="
                                    <div style='display: flex; align-items: center;'>
                                        <img src='<?= $produit['image_prod'] ?>' alt='<?= $produit['nom_prod'] ?>' 
                                             style='width:80px; height:80px; margin-right: 10px; border-radius: 2px;'>
                                        <span><?= $produit['nom_prod'] ?></span>
                                    </div>
                                ">
                                    <?= $produit['nom_prod'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <br><br>
                    <div class="form-group">
                        <label for="qt_prod">Quantity</label>
                        <input type="text" id="qt_prod" name="qt_prod" class="form-control contactus"
                            placeholder="Add Quantity">
                    </div>

                    <div class="form-group">
                        <label for="mode_paiement">Payment method</label>
                        <select id="mode_paiement" name="mode_paiement" class="form-control contactus">
                            <option value="carte">Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="espece">money</option>
                        </select>
                    </div>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php elseif (!empty($successMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo $successMessage; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group text-center">
                        <button type="submit" class="send_btn">Add to Cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer id="contact">
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p>Copyright 2024 All Right Reserved By <a href="https://html.design/">Free html Templates</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.0.0.min.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/custom.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#id_prod').selectpicker();
        });
    </script>
</body>

</html>

<?php
?>
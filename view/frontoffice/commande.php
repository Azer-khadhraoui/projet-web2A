<?php
// Inclusion de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; 

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

$error = "";
$successMessage = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_cmd = isset($_POST['date_cmd']) ? $_POST['date_cmd'] : '';
    $stat_cmd = isset($_POST['stat_cmd']) ? $_POST['stat_cmd'] : '';
    $adress_cmd = isset($_POST['adress_cmd']) ? $_POST['adress_cmd'] : '';
    $desc_cmd = isset($_POST['desc_cmd']) ? $_POST['desc_cmd'] : '';
    $paniers = isset($_POST['paniers']) ? $_POST['paniers'] : [];

    $total_cmd = 0;

    if (empty($date_cmd) || !preg_match("/\d{4}-\d{2}-\d{2}/", $date_cmd)) {
        $errors[] = "La date de la commande est invalide ou manquante.";
    }

    if (empty($stat_cmd)) {
        $errors[] = "L'état de la commande doit être sélectionné.";
    }

    if (empty($adress_cmd)) {
        $errors[] = "L'adresse de la commande est obligatoire.";
    }

    if (empty($desc_cmd)) {
        $errors[] = "La description de la commande est obligatoire.";
    }

    if (empty($paniers)) {
        $errors[] = "Aucun panier sélectionné.";
    } else {
        foreach ($paniers as $panier_id) {
            $sql_panier = "SELECT pr.prix, p.qt_prod FROM pannier p 
                           JOIN produit pr ON p.id_prod = pr.id_produit 
                           WHERE p.id_pannier = :panier_id";
            $stmt_panier = $pdo->prepare($sql_panier);
            $stmt_panier->bindParam(':panier_id', $panier_id, PDO::PARAM_INT);
            $stmt_panier->execute();

            if ($stmt_panier->rowCount() > 0) {
                $row = $stmt_panier->fetch(PDO::FETCH_ASSOC);
                $prix = $row['prix'];
                $qt = $row['qt_prod'];
                $total_cmd += $prix * $qt;
            } else {
                $errors[] = "Le panier avec l'ID $panier_id est introuvable.";
            }
        }
    }

    if (empty($errors)) {
        $sql = "INSERT INTO commande (date_cmd, stat_cmd, adress_cmd, desc_cmd, prix_total) 
                VALUES (:date_cmd, :stat_cmd, :adress_cmd, :desc_cmd, :total_cmd)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':date_cmd', $date_cmd);
        $stmt->bindParam(':stat_cmd', $stat_cmd);
        $stmt->bindParam(':adress_cmd', $adress_cmd);
        $stmt->bindParam(':desc_cmd', $desc_cmd);
        $stmt->bindParam(':total_cmd', $total_cmd);

        if ($stmt->execute()) {
            $commande_id = $pdo->lastInsertId();

            foreach ($paniers as $panier_id) {
                $sql_panier = "INSERT INTO commande_panier (commande_id, panier_id) 
                               VALUES (:commande_id, :panier_id)";
                $stmt_panier = $pdo->prepare($sql_panier);
                $stmt_panier->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
                $stmt_panier->bindParam(':panier_id', $panier_id, PDO::PARAM_INT);
                $stmt_panier->execute();
            }

            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  
                $mail->SMTPAuth = true;
                $mail->Username = 'gharbi.wided@esprit.tn';  
                $mail->Password = 'mhmk qlhd bqqd vkdy'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
            
                $mail->setFrom('your-email@example.com', 'Commande');
                $mail->addAddress($adress_cmd);
            
                // Récupérer les détails des produits
                $produits_details = "";
                foreach ($paniers as $panier_id) {
                    $sql_panier = "SELECT pr.nom, pr.prix, p.qt_prod 
                                   FROM pannier p 
                                   JOIN produit pr ON p.id_prod = pr.id_produit 
                                   WHERE p.id_pannier = :panier_id";
                    $stmt_panier = $pdo->prepare($sql_panier);
                    $stmt_panier->bindParam(':panier_id', $panier_id, PDO::PARAM_INT);
                    $stmt_panier->execute();
            
                    while ($row = $stmt_panier->fetch(PDO::FETCH_ASSOC)) {
                        $produits_details .= "<tr>
                            <td>{$row['nom']}</td>
                            <td>{$row['qt_prod']}</td>
                            <td>{$row['prix']} TND</td>
                            <td>" . ($row['prix'] * $row['qt_prod']) . " TND</td>
                        </tr>";
                    }
                }
            
                // Construire le corps de l'e-mail
                $mail->isHTML(true);
                $mail->Subject = 'Confirmation de commande';
                $mail->Body = "
                    <h2>Merci pour votre commande !</h2>
                    <p>Votre commande a ete bien enregistree avec l'ID : <strong>$commande_id</strong>.</p>
                    <p><strong>Adresse de livraison :</strong> $adress_cmd</p>
                    <p><strong>Description :</strong> $desc_cmd</p>
                    <p><strong>Total :</strong> $total_cmd TND</p>
                    <h3>Details des produits :</h3>
                    <table border='1' cellpadding='10' cellspacing='0'>
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantite</th>
                                <th>Prix Unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            $produits_details
                        </tbody>
                    </table>
                ";
            
                $mail->send();
                $successMessage = "Commande ajoutée avec succès et email envoyé!";
                header('Location: ../../view/backoffice/bs-simple-admin/liste_commande.php');
                exit;
            } catch (Exception $e) {
                $errors[] = "Erreur lors de l'envoi de l'email: " . $mail->ErrorInfo;
            }
            
        } else {
            $errors[] = "Erreur lors de l'insertion de la commande.";
        }
    }

    if (!empty($errors)) {
        $error = implode("<br>", $errors);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css"
        media="screen">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

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
                        <nav class="navigation navbar navbar-expand-md navbar-dark">
                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarsExample04">
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                                    <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                                    <li class="nav-item"><a class="nav-link" href="products.html">Products</a></li>
                                    <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                                    <li class="nav-item active"><a class="nav-link" href="commande.html">Commande</a>
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
                    <h2>Ajouter commande</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="" method="POST" class="main_form">
                    <div class="form-group">
                        <label for="calendarToggle" style="cursor: pointer;">Sélectionner une date de commande</label>
                        <button id="calendarToggle" type="button" class="btn btn-primary">Afficher/Masquer le
                            Calendrier</button>

                        <div id="calendarContainer" style="display: none; margin-top: 10px;">
                            <div id="calendar"></div>
                        </div>

                        <input type="hidden" id="date_cmd" name="date_cmd" class="form-control contactus">
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var calendarEl = document.getElementById('calendar');
                            var calendarContainer = document.getElementById('calendarContainer');
                            var toggleButton = document.getElementById('calendarToggle');
                            var selectedEvent = null;
                            var calendar = new FullCalendar.Calendar(calendarEl, {
                                initialView: 'dayGridMonth',
                                height: 'auto',
                                selectable: true,
                                dateClick: function (info) {
                                    if (selectedEvent) {
                                        calendar.getEventById(selectedEvent.id).remove();
                                    }
                                    selectedEvent = calendar.addEvent({
                                        id: 'selected-date',
                                        start: info.dateStr,
                                        allDay: true,
                                        backgroundColor: '#FF0000',
                                        borderColor: '#FF0000',
                                    });
                                    document.getElementById('date_cmd').value = info.dateStr;
                                    saveDateToDatabase(info.dateStr);
                                }
                            });
                            calendar.render();
                            toggleButton.addEventListener('click', function () {
                                if (calendarContainer.style.display === 'none') {
                                    calendarContainer.style.display = 'block';
                                    calendar.updateSize();
                                } else {
                                    calendarContainer.style.display = 'none';
                                }
                            });
                        });
                    </script>

                    <div class="form-group">
                        <label for="stat_cmd">Statut de commande</label>
                        <select id="stat_cmd" name="stat_cmd" class="form-control contactus">
                            <option value="En attente" selected>En attente</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="adress_cmd">Adresse de livraison</label>
                        <input type="text" id="adress_cmd" name="adress_cmd" class="form-control contactus"
                            placeholder="Entrez l'adresse de livraison">
                    </div>

                    <div class="form-group">
                        <label for="desc_cmd">Information supplémentaire</label>
                        <input type="text" id="desc_cmd" name="desc_cmd" class="form-control contactus"
                            placeholder="Entrez une information supplémentaire">
                    </div>

                    <div class="form-group">
                        <label for="paniers">Sélectionner les paniers</label><br>
                        <?php
                        $sql = "SELECT p.id_pannier, pr.nom, pr.images, pr.prix, p.qt_prod 
FROM pannier p
JOIN produit pr ON p.id_prod = pr.id_produit";
                        $stmt = $pdo->query($sql);

                        if ($stmt->rowCount() > 0) {
                            echo '<div class="product-list">';

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $id_pannier = $row['id_pannier'];
                                $nom = $row['nom'];
                                $image = $row['images'];
                                $prix = $row['prix'];
                                $qt_prod = $row['qt_prod'];

                                echo '<div class="product-item">';
                                echo '<label>';
                                echo "<input type='checkbox' name='paniers[]' value='$id_pannier' data-price='$prix' data-quantity='$qt_prod'>";
                                echo "<br/>";
                                echo '<img src="' . $row["images"] . '" alt="' . $row["nom"] . '" class="product-image">';
                                echo "<h5>$nom</h5>";
                                echo "<p>Prix : $prix DT</p>";
                                echo "<p>Quantité : $qt_prod</p>";
                                echo '</label>';
                                echo '</div>';
                            }

                            echo '</div>';
                        } else {
                            echo '<p>Aucun produit trouvé.</p>';
                        }
                        ?>
                        <style>
                            .product-list {
                                display: flex;
                                flex-wrap: wrap;
                                gap: 20px;
                                justify-content: space-around;
                                margin: 20px 0;
                            }

                            .product-item {
                                background-color: #f9f9f9;
                                border: 1px solid #ddd;
                                padding: 15px;
                                border-radius: 8px;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                max-width: 200px;
                                text-align: center;
                                transition: transform 0.3s ease, box-shadow 0.3s ease;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                            }

                            .product-item:hover {
                                transform: scale(1.05);
                                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
                            }

                            .product-image {
                                width: 100px;
                                height: 100px;
                                object-fit: cover;
                                border-radius: 5px;
                                margin-bottom: 10px;
                            }

                            .product-name {
                                font-weight: bold;
                                font-size: 1.2rem;
                                margin-bottom: 10px;
                            }

                            .product-price {
                                font-size: 1rem;
                                color: #555;
                                margin-bottom: 5px;
                            }

                            .product-quantity {
                                font-size: 0.9rem;
                                color: #777;
                            }

                            .panier-checkbox {
                                margin-bottom: 15px;
                            }

                            #calendar {
                                width: 80%;
                                height: auto;
                                margin: 0 auto;
                                font-size: 0.8em;
                            }
                        </style>
                    </div>

                    <div class="form-group">
                        <p id="totalPrice">Total: 0 DT</p>
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
                        <button type="submit" class="send_btn">Confirmer la commande</button>
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
    <script>
        document.querySelectorAll("input[name='paniers[]']").forEach(function (checkbox) {
            checkbox.addEventListener("change", updateTotalPrice);
        });

        function updateTotalPrice() {
            let total = 0;

            document.querySelectorAll("input[name='paniers[]']:checked").forEach(function (checkbox) {
                let price = parseFloat(checkbox.dataset.price);
                let quantity = parseInt(checkbox.dataset.quantity);
                total += price * quantity;
            });

            document.getElementById("totalPrice").innerText = "Total: " + total.toFixed(2) + " DT";
        }
    </script>

</body>

</html>
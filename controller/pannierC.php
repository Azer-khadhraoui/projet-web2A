<?php
require_once('D:\apache xampp\htdocs\projet-web2A\config.php');

class PannierC
{
    // Ajouter un panier
    function addPannier($pannier)
    {
        $sql = "INSERT INTO pannier (id_prod, qt_prod, mode_paiement) 
                VALUES (:id_prod, :qt_prod, :mode_paiement)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_prod' => $pannier->getIdProd(),
                'qt_prod' => $pannier->getQtProd(),
                'mode_paiement' => $pannier->getModePaiement(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Ajouter un produit au panier
    function addProductToPannier($product, $mode_paiement, $total) {
        $sql = "INSERT INTO pannier (id_prod, qt_prod, mode_paiement, total) 
                VALUES (:id_prod, :qt_prod, :mode_paiement, :total)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_prod' => $product->getId(),
                'qt_prod' => $product->getQte(),
                'mode_paiement' => $mode_paiement,
                'total' => $total,
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Retrieve products from the pannier
    function getPannierProducts() {
        $sql = "SELECT p.id_prod, p.nom_prod, p.description, p.prix, pa.qt_prod 
                FROM pannier pa 
                JOIN produits p ON pa.id_prod = p.id_prod";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Liste des paniers
    public function listePanniers()
    {
        $sql = "SELECT * FROM pannier";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Supprimer un panier
    function deletePannier($id_pannier)
    {
        $sql = "DELETE FROM pannier WHERE id_pannier = :id_pannier";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id_pannier', $id_pannier);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Afficher un panier spécifique
    function showPannier($id_pannier)
    {
        $sql = "SELECT * FROM pannier WHERE id_pannier = :id_pannier";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_pannier', $id_pannier);
            $query->execute();
            $pannier = $query->fetch();
            return $pannier;
        } catch (Exception $e) {
            throw new Exception('Error showing pannier: ' . $e->getMessage());
        }
    }

    // Mettre à jour un panier
    function updatePannier($pannier, $id_pannier)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare(
                'UPDATE pannier SET
                id_prod = :id_prod,
                qt_prod = :qt_prod,
                mode_paiement = :mode_paiement,
                statut_pannier = :statut_pannier
                WHERE id_pannier = :id_pannier'
            );
            $query->execute([
                'id_pannier' => $id_pannier,
                'id_prod' => $pannier->getIdProd(),
                'qt_prod' => $pannier->getQtProd(),
                'mode_paiement' => $pannier->getModePaiement(),
                'statut_pannier' => $pannier->getStatutPannier(),
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>

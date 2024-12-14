<?php
require_once('../../config.php');

class PannierC
{
    // Add a pannier
    public function addPannier($pannier)
    {
        $sql = "INSERT INTO pannier1 (id_prod, qt_prod, mode_paiement, statut_pannier) 
                VALUES (:id_prod, :qt_prod, :mode_paiement, :statut_pannier)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_prod' => $pannier->getIdProd(),
                'qt_prod' => $pannier->getQtProd(),
                'mode_paiement' => $pannier->getModePaiement(),
                'statut_pannier' => $pannier->getStatutPannier(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
// Ensure this method is defined inside the PannierC class
public function getProductById($id_prod) {

    $conn = config::getConnexion();
    $query = "SELECT * FROM produits WHERE id_prod = :id_prod";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    // List all panniers
    public function getAllPanniers()
    {
        $sql = "SELECT 
                    pannier.id_pannier, 
                    pannier.id_prod, 
                    pannier.qt_prod, 
                    pannier.mode_paiement, 
                    pannier.statut_pannier,
                    produits.nom_prod AS nom_prod,
                    produits.prix AS prix_prod,
                    produits.url_img AS image_prod
                FROM pannier1 as pannier , produits 
                where produits.id_prod= pannier.id_prod";

        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error fetching panniers: ' . $e->getMessage());
        }
    }

    // Delete a pannier
    public function deletePannier($id_pannier)
    {
        $sql = "DELETE FROM pannier1 WHERE id_prod = :id_prod";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id_pannier', $id_pannier);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}
?>

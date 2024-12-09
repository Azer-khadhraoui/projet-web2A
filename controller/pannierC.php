<?php
require_once('config.php');

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

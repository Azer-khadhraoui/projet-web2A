<?php
require_once('D:\apache xampp\htdocs\projet-web2A\config.php');

class CommandeC
{
    // Ajouter une commande
    function addCommande($commande)
    {
        $sql = "INSERT INTO commande (date_cmd, stat_cmd, adress_cmd, desc_cmd) 
                VALUES (:date_cmd, :stat_cmd, :adress_cmd, :desc_cmd)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'date_cmd' => $commande->getDateCmd(),
                'stat_cmd' => $commande->getStatCmd(),
                'adress_cmd' => $commande->getAdressCmd(),
                'desc_cmd' => $commande->getDescCmd(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Liste des commandes
    public function listeCommandes()
    {
        $sql = "SELECT * FROM commande";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Supprimer une commande
    function deleteCommande($id_cmd)
    {
        $sql = "DELETE FROM commande WHERE id_cmd = :id_cmd";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id_cmd', $id_cmd);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Afficher une commande spécifique
    function showCommande($id_cmd)
    {
        $sql = "SELECT * FROM commande WHERE id_cmd = :id_cmd";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_cmd', $id_cmd);
            $query->execute();
            $commande = $query->fetch();
            return $commande;
        } catch (Exception $e) {
            throw new Exception('Error showing commande: ' . $e->getMessage());
        }
    }

    // Mettre à jour une commande
    function updateCommande($commande, $id_cmd)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare(
                'UPDATE commande SET
                date_cmd = :date_cmd,
                stat_cmd = :stat_cmd,
                adress_cmd = :adress_cmd,
                desc_cmd = :desc_cmd
                WHERE id_cmd = :id_cmd'
            );
            $query->execute([
                'id_cmd' => $id_cmd,
                'date_cmd' => $commande->getDateCmd(),
                'stat_cmd' => $commande->getStatCmd(),
                'adress_cmd' => $commande->getAdressCmd(),
                'desc_cmd' => $commande->getDescCmd(),
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>

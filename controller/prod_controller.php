<?php
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../model/products_mod.php';


class TravelOfferController {
    // Retrieve all products
    public function getAllProducts() {
        $sql = "SELECT * FROM produits";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list->fetchAll(PDO::FETCH_ASSOC); // Fetch data as associative array
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Create a new product
    public function createProduct($nom_prod, $description, $prix, $qte, $url_img, $cat) {
        $sql = "INSERT INTO produits (nom_prod, description, prix, qte, url_img, categorie) 
                VALUES (:nom_prod, :description, :prix, :qte, :url_img, :cat)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(compact('nom_prod', 'description', 'prix', 'qte', 'url_img', 'cat'));
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Update an existing product
    public function updateProduct($id, $nom_prod, $description, $prix, $qte, $url_img, $cat) {
        $sql = "UPDATE produits SET 
                    nom_prod = :nom_prod, 
                    description = :description, 
                    prix = :prix, 
                    qte = :qte, 
                    url_img = :url_img, 
                    categorie = :cat 
                WHERE id_prod = :id_prod";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(compact('id', 'nom_prod', 'description', 'prix', 'qte', 'url_img', 'categorie'));
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Delete a product
    public function deleteProduct($id) {
        $sql = "DELETE FROM produits WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
?>
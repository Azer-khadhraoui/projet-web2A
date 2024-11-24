<?php
include_once __DIR__ . '/../config.php'; 
include_once __DIR__ . '/../model/catg_mod.php';

class CategoriesController {
    // Create a new category
    public function createCategory($nom_categorie) {
        $sql = "INSERT INTO categorie (nom_categorie) VALUES (:nom_categorie)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':nom_categorie', $nom_categorie, PDO::PARAM_STR);
            $query->execute();
            echo "Category added successfully.";
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Retrieve all categories
    public function getAllCategories() {
        $sql = "SELECT * FROM categorie";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Retrieve a category by its ID
    public function getCategoryById($id_categorie) {
        $sql = "SELECT * FROM categorie WHERE id_categorie = :id_categorie";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_categorie', $id_categorie, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Update a category
    public function updateCategory($id_categorie, $nom_categorie) {
        $sql = "UPDATE categorie SET nom_categorie = :nom_categorie WHERE id_categorie = :id_categorie";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_categorie', $id_categorie, PDO::PARAM_INT);
            $query->bindValue(':nom_categorie', $nom_categorie, PDO::PARAM_STR);
            $query->execute();
            echo "Category updated successfully.";
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Delete a category
    public function deleteCategory($id_categorie) {
        $sql = "DELETE FROM categorie WHERE id_categorie = :id_categorie";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_categorie', $id_categorie, PDO::PARAM_INT);
            $query->execute();
            echo "Category deleted successfully.";
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
?>

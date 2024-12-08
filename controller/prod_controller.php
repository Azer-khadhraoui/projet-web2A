<?php 
include_once __DIR__ .'../../config.php';
include_once __DIR__ . '/../model/products_mod.php';

class TravelOfferController {
    // Retrieve all products
    public function getAllProducts() {
        $sql = "SELECT * FROM produits";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list->fetchAll(PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Retrieve a product by its ID
    public function getProductById($id) {
        $sql = "SELECT * FROM produits WHERE id_prod = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);  
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);  
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
//create new product 
public function createProduct($nom_prod, $description, $prix, $qte, $file, $cat) {
    $uploadDir = '../../frontoffice/images/'; 
    $url_img = ""; 

    if (!empty($file['name']) && $file['error'] === 0) {
        $fileName = basename($file['name']); 
        $targetFile = $uploadDir . $fileName; 
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!getimagesize($file['tmp_name'])) {
            die("Error: The file is not a valid image.");
        }
        if (!in_array($imageFileType, $allowedTypes)) {
            die("Error: Only JPG, JPEG, PNG, and GIF files are allowed.");
        }

       
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $url_img = $fileName; 
        } else {
            die("Error: File upload failed.");
        }
    } else {
        die("Error: No file selected or file upload failed.");
    }

    if (empty($url_img)) {
        die("Error: Image file name is empty. Check the file upload process.");
    }

    $sql = "INSERT INTO produits (nom_prod, description, prix, qte, url_img, categorie) 
            VALUES (:nom_prod, :description, :prix, :qte, :url_img, :cat)";
    
    try {
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([
            ':nom_prod' => $nom_prod,
            ':description' => $description,
            ':prix' => $prix,
            ':qte' => $qte,
            ':url_img' => $url_img, 
            ':cat' => $cat
        ]);
        echo "Product added successfully!";
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}


    //update product s
    public function updateProduct($id, $nom_prod, $description, $prix, $qte, $file, $cat) {
        $uploadDir = '../images/';
        $url_img = null;
    
        // Check if a file is uploaded
        if (is_array($file) && !empty($file['tmp_name'])) {
            $fileName = basename($file['name']);
            $targetFile = $uploadDir . $fileName;
    
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                $url_img = '../images/' . $fileName; // Set the new file path
            } else {
                die("Error: Unable to upload file.");
            }
        } elseif (is_string($file)) {
            $url_img = $file; // Use the existing file path if no new upload
        }
    
        // Update query
        $sql = "UPDATE produits SET 
                    nom_prod = :nom_prod, 
                    description = :description, 
                    prix = :prix, 
                    qte = :qte, 
                    categorie = :cat";
        if ($url_img) {
            $sql .= ", url_img = :url_img";
        }
        $sql .= " WHERE id_prod = :id";
    
        $db = config::getConnexion();
        try {
            $params = compact('id', 'nom_prod', 'description', 'prix', 'qte', 'cat');
            if ($url_img) {
                $params['url_img'] = $url_img;
            }
            $query = $db->prepare($sql);
            $query->execute($params);
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
    

    // Delete a product
    public function deleteProduct($id) {
        $sql = "DELETE FROM produits WHERE id_prod = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            echo "Product deleted successfully.";
        } catch (PDOException $e) {
            echo "PDO Error: " . $e->getMessage();
            die("Error: " . $e->getMessage());
        } catch (Exception $e) {
            echo "General Error: " . $e->getMessage();
            die("Error: " . $e->getMessage());
        }
    }
}
?>

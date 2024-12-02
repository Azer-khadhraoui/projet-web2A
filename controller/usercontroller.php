<?php
include '../../config.php';
include '../../Model/usermodel.php';

class usercontroller {
    // Méthode pour ajouter un utilisateur
    public function adduser($user) {
        $sql = "INSERT INTO utilisateur (cin, nom, prenom, pwd, numero, role, mail, statut) VALUES (:cin, :nom, :prenom, :pwd, :numero, :role, :mail, :statut)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'cin' => $user->getCin(),
                'nom' => $user->getFname(),
                'prenom' => $user->getLname(),
                'pwd' => $user->getPassword(),
                'numero' => $user->getNum(),
                'role' => $user->getRole(),
                'mail' => $user->getMail(),
                'statut' => $user->getStatut()
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Méthode pour mettre à jour un utilisateur
    public function updateUser($user) {
        $sql = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, pwd = :pwd, numero = :numero, role = :role, mail = :mail, statut = :statut WHERE cin = :cin";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'cin' => $user->getCin(),
                'nom' => $user->getFname(),
                'prenom' => $user->getLname(),
                'pwd' => $user->getPassword(),
                'numero' => $user->getNum(),
                'role' => $user->getRole(),
                'mail' => $user->getMail(),
                'statut' => $user->getStatut()
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Méthode pour récupérer un utilisateur par son CIN
    public function getUserByCin($cin) {
        $sql = "SELECT * FROM utilisateur WHERE cin = :cin";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['cin' => $cin]);
            return $query->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Méthode pour lister les utilisateurs
    public function listUsers() {
        $sql = "SELECT * FROM utilisateur";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Méthode pour supprimer un utilisateur
    public function deleteUser($cin) {
        $sql = "DELETE FROM utilisateur WHERE cin = :cin";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['cin' => $cin]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }


        // Méthode pour obtenir tous les utilisateurs
        public function getAllUsers() {
            $sql = "SELECT statut, role FROM utilisateur";
            $db = config::getConnexion();
            try {
                $query = $db->query($sql);
                return $query->fetchAll();
            } catch (Exception $e) {
                die('Erreur: ' . $e->getMessage());
            }
        }
}
?>
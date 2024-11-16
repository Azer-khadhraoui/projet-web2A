<?php
include '../../Model/usermodel.php';
include '../../config.php';

class usercontroller
{
    
    public function listUsers()
    {
        $sql = "SELECT * FROM utilisateur";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    
    public function adduser($user)
    {
        $sql = "INSERT INTO utilisateur (nom,prenom,cin,pwd,numero,role) VALUES (:nom, :prenom, :cin, :pwd, :numero, :role)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                
                'nom' => $user->getFname(),
                'prenom' => $user->getLname(),
                'cin' => $user->getCin(),
                'pwd' => $user->getPassword(),
                'numero' => $user->getNum(),
                'role' => $user->getRole()
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

   public function deleteUser($cin)
    {
        $sql = "DELETE FROM utilisateur WHERE cin = :cin";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['cin' => $cin]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    
    public function updateUser($user)
    {
        $sql = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, pwd = :pwd, numero = :numero, role = :role WHERE cin = :cin";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'cin' => $user->getCin(),
                'nom' => $user->getFname(),
                'prenom' => $user->getLname(),
                'pwd' => $user->getPassword(),
                'numero' => $user->getNum(),
                'role' => $user->getRole()
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    
    public function getUserByCin($cin)
    {
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
}
?>
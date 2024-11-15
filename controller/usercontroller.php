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

    // Méthode pour mettre à jour un utilisateur
    /*public function updateUser($user)
    {
        $sql = "UPDATE users SET fname = :fname, lname = :lname, password = :password, num = :num, role = :role WHERE cin = :cin";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'cin' => $user->getCin(),
                'fname' => $user->getFname(),
                'lname' => $user->getLname(),
                'password' => $user->getPassword(),
                'num' => $user->getNum(),
                'role' => $user->getRole()
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }*/
}
?>
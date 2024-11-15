<?php
include '../../Controller/usercontroller.php';


$controller = new usercontroller();


$list = $controller->listUsers();


echo "<table border='1'>
        <tr>
            <th>CIN</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Number</th>
            <th>password</th>
            <th>Role</th>
            
        </tr>";

foreach ($list as $row) {
    $cin = $row['cin'];
    $fname = $row['nom'];
    $lname = $row['prenom'];
    $num = $row['numero'];
    $pwd = $row['pwd'];
    $role = $row['role'];

    echo "<tr>
            <td>{$cin}</td>
            <td>{$fname}</td>
            <td>{$lname}</td>
            <td>{$num}</td>
             <td>{$pwd}</td>
            <td>{$role}</td>
            <td>
                <a href='delete.php?cin={$cin}' onclick=\"return confirm('Êtes-vous sûr de supprimer cet utilisateur ?');\">Supprimer</a>
                <a href='edit.php?cin={$cin}'>Modifier</a>
            </td>
          </tr>";
}
echo "</table>";
?>
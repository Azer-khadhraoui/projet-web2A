<?php

include '../../Controller/usercontroller.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $cin = $_POST['cin'];
    $number = $_POST['number'];
    $password = $_POST['password'];
    $retype_password = $_POST['retype-password'];

   
    $usr = new User($cin, $fname, $lname, $password, $number,0); 

    
    $controller = new usercontroller();
    
    
    $controller->adduser($usr);

    
    echo "User added successfully.";
} else {
    echo "No data received.";
}
?>
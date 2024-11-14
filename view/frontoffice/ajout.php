<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $cin = $_POST['cin'];
    $number = $_POST['number'];
    $password = $_POST['password'];
    $retype_password = $_POST['retype-password'];

    echo "<h1>Form Data</h1>";
    echo "<p><strong>First Name:</strong> $fname</p>";
    echo "<p><strong>Last Name:</strong> $lname</p>";
    echo "<p><strong>CIN:</strong> $cin</p>";
    echo "<p><strong>Number:</strong> $number</p>";
    echo "<p><strong>Password:</strong> $password</p>";
    echo "<p><strong>Retype Password:</strong> $retype_password</p>";
} else {
    echo "No data received.";
}
?>
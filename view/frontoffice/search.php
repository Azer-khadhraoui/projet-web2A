<?php
include '../../controller/usercontroller.php';

$controller = new usercontroller();
$role = isset($_GET['role']) ? $_GET['role'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

$users = $controller->getFilteredUsers($role, $status, $keyword);

header('Content-Type: application/json');
echo json_encode($users);
?>
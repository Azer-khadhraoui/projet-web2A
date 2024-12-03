<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../controller/usercontroller.php';

$controller = new usercontroller();
$users = $controller->getAllUsers();

$roleFilter = isset($_GET['role']) ? $_GET['role'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

$statusCounts = ['active' => 0, 'blocked' => 0];
$roleCounts = ['admin' => 0, 'user' => 0];

foreach ($users as $user) {
    if (($roleFilter === '' || $user['role'] == $roleFilter) &&
        ($statusFilter === '' || $user['statut'] == $statusFilter)) {
        if ($user['statut'] == 0) {
            $statusCounts['active']++;
        } else {
            $statusCounts['blocked']++;
        }

        if ($user['role'] == 1) {
            $roleCounts['admin']++;
        } else {
            $roleCounts['user']++;
        }
    }
}

header('Content-Type: application/json');
echo json_encode(['statusCounts' => $statusCounts, 'roleCounts' => $roleCounts]);
?>
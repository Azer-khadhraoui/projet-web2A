<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../controller/usercontroller.php';

$controller = new usercontroller();
$users = $controller->getAllUsers();

if ($users === false) {
    die('Error fetching users');
}

$statusCounts = ['active' => 0, 'blocked' => 0];
$roleCounts = ['admin' => 0, 'user' => 0];

foreach ($users as $user) {
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

// Debugging: Log the counts
error_log("Status Counts: " . print_r($statusCounts, true));
error_log("Role Counts: " . print_r($roleCounts, true));

header('Content-Type: application/json');
echo json_encode(['statusCounts' => $statusCounts, 'roleCounts' => $roleCounts]);
?>
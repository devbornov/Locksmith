<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$offering_id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if ($offering_id && in_array($action, ['approve', 'reject'])) {
    $status = $action === 'approve' ? 'approved' : 'rejected';

    $stmt = $pdo->prepare("UPDATE service_offerings SET approval_status = ? WHERE id = ?");
    $stmt->execute([$status, $offering_id]);

    header("Location: approve_services.php?status=$status");
    exit();
} else {
    echo "Invalid request.";
}
?>

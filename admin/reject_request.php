<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$request_id = $_GET['id'] ?? null;

if ($request_id) {
    $stmt = $pdo->prepare("UPDATE service_requests SET bid_status = 'rejected', locksmith_response = 'declined' WHERE id = ?");
    $stmt->execute([$request_id]);
}

header("Location: service_requests.php");
exit();
?>

<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Get the service offering ID from the URL
$offering_id = $_GET['id'] ?? null;

if ($offering_id) {
    // Delete the service offering from the database
    $stmt = $pdo->prepare("DELETE FROM service_offerings WHERE id = ?");
    $stmt->execute([$offering_id]);
}

// Redirect to service offerings page
header("Location: service_offer.php");
exit();
?>

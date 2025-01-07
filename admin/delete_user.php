<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Get the user ID from the URL
$user_id = $_GET['id'] ?? null;

if ($user_id) {
    // Delete the user from the database
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
}

// Redirect to manage users page
header("Location: http://localhost/locksmith2/admin/dashboard.php?page=manage_users");
exit();
?>

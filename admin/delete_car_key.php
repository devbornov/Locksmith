<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Get the car key ID from the URL
$car_key_id = $_GET['id'] ?? null;

if ($car_key_id) {
    // Delete the car key from the database
    $stmt = $pdo->prepare("DELETE FROM car_key_details WHERE id = ?");
    $success = $stmt->execute([$car_key_id]);

    if ($success) {
        // Redirect to manage car keys page with success message
        header("Location: http://localhost/locksmith2/admin/dashboard.php?page=update_car_key");
    } else {
        // Redirect with error message
        header("Location: http://localhost/locksmith2/admin/dashboard.php?page=update_car_key");
    }
    exit();
}

// If no ID is provided, redirect with error
header("Location: http://localhost/locksmith2/admin/dashboard.php?page=update_car_key");
exit();
?>

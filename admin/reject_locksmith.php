<?php
require '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $locksmith_id = $_GET['id'];

    // Update the registration status to 'rejected'
    $stmt = $pdo->prepare("UPDATE locksmith_registrations SET status = 'rejected' WHERE id = ?");
    $stmt->execute([$locksmith_id]);

    // Redirect back to manage_locksmiths.php with a rejection message
    header("Location: http://localhost/locksmith2/admin/dashboard.php?page=manage_users&status=rejected");
    exit();
}
?>

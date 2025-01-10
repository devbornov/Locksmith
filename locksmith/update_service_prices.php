<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'locksmith') {
    header("Location: ../auth/login.php");
    exit();
}

$locksmith_id = $_SESSION['user_id'];
$prices = $_POST['prices'] ?? [];

foreach ($prices as $service_id => $price) {
    if ($price !== '') {
        $stmt = $pdo->prepare("
            INSERT INTO service_offerings (locksmith_id, service_id, price, approval_status)
            VALUES (?, ?, ?, 'pending')
            ON DUPLICATE KEY UPDATE price = VALUES(price), approval_status = 'pending'
        ");
        $stmt->execute([$locksmith_id, $service_id, $price]);
    }
}

header("Location: locksmith_services.php");
exit();
?>

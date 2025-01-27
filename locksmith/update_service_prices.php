<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'locksmith') {
    header("Location: ../auth/login.php");
    exit();
}

// Retrieve locksmith_id from the locksmith_details table based on user_id from the session
$stmt = $pdo->prepare("SELECT id FROM locksmith_details WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$locksmith = $stmt->fetch();

// If the locksmith ID does not exist, redirect or handle the error
if (!$locksmith) {
    header("Location: ../auth/login.php");
    exit();
}

$locksmith_id = $locksmith['id']; // Use the correct locksmith_id from locksmith_details table
$prices = $_POST['prices'] ?? [];

// Use a prepared statement to handle insertion and updating
try {
    $pdo->beginTransaction(); // Start a transaction for better performance

    foreach ($prices as $service_id => $price) {
        if ($price !== '') { // Only process services with a valid price
            $stmt = $pdo->prepare("
                INSERT INTO service_offerings (locksmith_id, service_id, price, status)
                VALUES (?, ?, ?, 'pending')
                ON DUPLICATE KEY UPDATE 
                    price = VALUES(price),
                    status = 'pending'
            ");
            $stmt->execute([$locksmith_id, $service_id, $price]);
        }
    }

    $pdo->commit(); // Commit the transaction
    header("Location: http://localhost/locksmith2/locksmith/dashboard.php?page=locksmith_services");
} catch (PDOException $e) {
    $pdo->rollBack(); // Rollback the transaction on error
    error_log("Database Error: " . $e->getMessage());
    header("Location: locksmith_services.php?error=1");
}
exit();
?>

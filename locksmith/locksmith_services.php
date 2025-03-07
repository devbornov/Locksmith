<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'locksmith') {
    header("Location: ../auth/login.php");
    exit();
}

// Retrieve locksmith_id from the locksmith_details table based on the user_id from the session
$stmt = $pdo->prepare("SELECT id FROM locksmith_details WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$locksmith = $stmt->fetch();

// If the locksmith ID does not exist, redirect or handle the error
if (!$locksmith) {
    header("Location: ../auth/login.php");
    exit();
}

$locksmith_id = $locksmith['id'];

// Fetch services and their offerings
$stmt = $pdo->prepare("
    SELECT 
        s.id AS service_id, 
        s.name AS service_name, 
        IFNULL(so.price, '') AS price, 
        IFNULL(so.status, 'not set') AS status
    FROM services AS s
    LEFT JOIN service_offerings AS so 
        ON s.id = so.service_id AND so.locksmith_id = ?
");
$stmt->execute([$locksmith_id]);
$services = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Service Prices</title>
    <link rel="stylesheet" href="../assets/css/manage_users.css">
</head>
<body>
    <div class="container">
        <h1>Set Your Prices</h1>
        <p>Locksmith ID: <?= htmlspecialchars($locksmith_id) ?></p>
        <?php if (isset($_GET['success'])): ?>
            <p class="success-message">Prices updated successfully!</p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error-message">An error occurred while updating prices. Please try again.</p>
        <?php endif; ?>
        <form action="update_service_prices.php" method="post">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Price</th>
                        <th>Approval Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?= htmlspecialchars($service['service_name']) ?></td>
                        <td>
                            <input 
                                type="number" 
                                name="prices[<?= $service['service_id'] ?>]" 
                                value="<?= htmlspecialchars($service['price']) ?>" 
                                step="0.01">
                        </td>
                        <td><?= htmlspecialchars($service['status']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table><br>
            <button class="action-button approve" type="submit">Save Prices</button>
        </form>
    </div>
</body>
</html>

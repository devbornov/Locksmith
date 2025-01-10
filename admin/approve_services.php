<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all service offerings
$stmt = $pdo->query("
    SELECT 
        so.id AS offering_id, 
        so.price, 
        so.approval_status, 
        l.name AS locksmith_name, 
        s.name AS service_name
    FROM service_offerings AS so
    JOIN locksmiths AS l ON so.locksmith_id = l.id
    JOIN services AS s ON so.service_id = s.id
");
$offerings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Services</title>
    <link rel="stylesheet" href="../assets/css/servicess.css">
</head>
<body>
    <div class="container">
        <h1>Service Offerings</h1>
        <table class="user-table">
            <thead>
                <tr>
                    <th>Locksmith Name</th>
                    <th>Service Name</th>
                    <th>Price</th>
                    <th>Approval Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($offerings as $offering): ?>
                <tr>
                    <td><?= htmlspecialchars($offering['locksmith_name']) ?></td>
                    <td><?= htmlspecialchars($offering['service_name']) ?></td>
                    <td><?= htmlspecialchars($offering['price']) ?></td>
                    <td><?= htmlspecialchars($offering['approval_status']) ?></td>
                    <td>
                        <?php if ($offering['approval_status'] === 'pending'): ?>
                            <a href="approve_offering.php?id=<?= $offering['offering_id'] ?>&action=approve" class="action-button approve">Approve</a>
                            <a href="approve_offering.php?id=<?= $offering['offering_id'] ?>&action=reject" class="action-button reject">Reject</a>
                        <?php else: ?>
                            <span>No Actions</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

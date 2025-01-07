<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all service requests
$stmt = $pdo->query("SELECT * FROM service_requests");
$requests = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Requests</title>
    <link rel="stylesheet" href="../assets/css/servicess.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Service Requests</h1>
        <table class="user-table">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Locksmith ID</th>
                    <th>Service ID</th>
                    <th>Bid Amount</th>
                    <th>Bid Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?= htmlspecialchars($request['customer_id']) ?></td>
                    <td><?= htmlspecialchars($request['locksmith_id']) ?></td>
                    <td><?= htmlspecialchars($request['service_id']) ?></td>
                    <td><?= htmlspecialchars($request['bid_amount']) ?></td>
                    <td><?= htmlspecialchars($request['bid_status']) ?></td>
                    <td>
                        <a href="approve_request.php?id=<?= $request['id'] ?>" class="action-button approve">Approve</a>
                        <a href="reject_request.php?id=<?= $request['id'] ?>" class="action-button reject">Reject</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

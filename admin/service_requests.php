<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all service requests with locksmith name and service name
$stmt = $pdo->query("
    SELECT sr.*, 
           ld.name AS locksmith_name, 
           s.name AS service_name ,
           cs.name As customer_name
    FROM service_requests sr
    LEFT JOIN locksmith_details ld ON sr.locksmith_id = ld.id
    LEFT JOIN services s ON sr.service_id = s.id
    LEFT JOIN customer_details cs ON sr.customer_id=cs.id
");
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
                    <th>Locksmith Name</th>
                    <th>Service Name</th>
                    <th>Bid Amount</th>
                    <th>Bid Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?= htmlspecialchars($request['customer_name']) ?></td>
                    <td><?= htmlspecialchars($request['locksmith_name'] ?: 'Unknown') ?></td>
                    <td><?= htmlspecialchars($request['service_name'] ?: 'Unknown') ?></td>
                    <td><?= htmlspecialchars($request['bid_amount']) ?></td>
                    <td><?= htmlspecialchars($request['locksmith_response'] ?: 'rejected') ?></td>
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

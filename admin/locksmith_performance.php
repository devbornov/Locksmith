<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch locksmith performance
$stmt = $pdo->query("
    SELECT 
        sr.locksmith_id, 
        sr.service_id, 
        COUNT(sr.id) AS total_requests, 
        SUM(sr.bid_amount) AS total_revenue, 
        AVG(r.rating) AS average_rating
    FROM 
        service_requests sr
    JOIN 
        commission_settings cs ON 1=1
    LEFT JOIN 
        ratings r ON sr.locksmith_id = r.locksmith_id
    WHERE 
        sr.bid_status = 'approved'
    GROUP BY 
        sr.locksmith_id, sr.service_id
");

$performances = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locksmith Performance</title>
    <link rel="stylesheet" href="../assets/css/perfomance.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Locksmith Performance</h1>
        <table class="user-table">
            <thead>
                <tr>
                    <th>Locksmith ID</th>
                    <th>Locksmith Name</th>
                    <th>Total Jobs</th>
                    <th>Average Rating</th>
                    <th>Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($performances as $performance): ?>
                <tr>
                    <td><?= htmlspecialchars($performance['locksmith_id']) ?></td>
                    <td><?= htmlspecialchars($performance['locksmith_name']) ?></td>
                    <td><?= htmlspecialchars($performance['total_requests']) ?></td>
                    <td><?= number_format($performance['average_rating'], 2) ?></td>
                    <td><?= htmlspecialchars($performance['total_revenue']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

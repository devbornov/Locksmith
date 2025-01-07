<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch revenue details
$stmt = $pdo->query("
    SELECT 
        sr.locksmith_id, 
        SUM(sr.bid_amount) AS total_revenue, 
        (SUM(sr.bid_amount) * (cs.percentage / 100)) AS commission 
    FROM 
        service_requests sr
    JOIN 
        commission_settings cs ON 1=1
    WHERE 
        sr.bid_status = 'approved'
    GROUP BY 
        sr.locksmith_id
");
$revenues = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Tracking</title>
</head>
<body>
    <div class="container">
        <h1>Revenue Tracking</h1>
        <table>
            <thead>
                <tr>
                    <th>Locksmith ID</th>
                    <th>Total Revenue</th>
                    <th>Commission</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($revenues as $revenue): ?>
                <tr>
                    <td><?= htmlspecialchars($revenue['locksmith_id']) ?></td>
                    <td><?= htmlspecialchars($revenue['total_revenue']) ?></td>
                    <td><?= htmlspecialchars($revenue['commission']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

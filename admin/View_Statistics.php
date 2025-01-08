<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch total transactions
$stmt = $pdo->query("SELECT COUNT(*) AS total_transactions FROM transactions");
$total_transactions = $stmt->fetch();

// Fetch top locksmiths by services completed
$stmt_locksmiths = $pdo->query("SELECT locksmith_id, COUNT(*) AS total_services 
                                FROM service_requests WHERE bid_status = 'approved'
                                GROUP BY locksmith_id ORDER BY total_services DESC LIMIT 5");
$top_locksmiths = $stmt_locksmiths->fetchAll();

// Fetch popular services
$stmt_services = $pdo->query("SELECT service_id, COUNT(*) AS total_requests 
                              FROM service_requests WHERE bid_status = 'approved' 
                              GROUP BY service_id ORDER BY total_requests DESC LIMIT 5");
$popular_services = $stmt_services->fetchAll();

// Fetch recent transactions
$stmt_transactions = $pdo->query("SELECT * FROM transactions ORDER BY created_at DESC LIMIT 5");
$recent_transactions = $stmt_transactions->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Statistics</title>
    <link rel="stylesheet" href="../assets/css/servicess.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>View Statistics</h1>
        
        <section>
            <h2>Total Transactions</h2>
            <p>Total number of transactions: <?= $total_transactions['total_transactions'] ?></p>
        </section>

        <section>
            <h2>Top Locksmiths</h2>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Locksmith ID</th>
                        <th>Services Completed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_locksmiths as $locksmith): ?>
                    <tr>
                        <td><?= htmlspecialchars($locksmith['locksmith_id']) ?></td>
                        <td><?= htmlspecialchars($locksmith['total_services']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Popular Services</h2>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Service ID</th>
                        <th>Requests</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($popular_services as $service): ?>
                    <tr>
                        <td><?= htmlspecialchars($service['service_id']) ?></td>
                        <td><?= htmlspecialchars($service['total_requests']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Recent Transactions</h2>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_transactions as $transaction): ?>
                    <tr>
                        <td><?= htmlspecialchars($transaction['transaction_id']) ?></td>
                        <td><?= htmlspecialchars($transaction['amount']) ?></td>
                        <td><?= htmlspecialchars($transaction['transaction_date']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

    </div>
</body>
</html>

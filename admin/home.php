<?php
require '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch data for graphs
$stmt_users = $pdo->query("SELECT COUNT(*) as user_count, DATE(created_at) as date FROM users GROUP BY DATE(created_at) ORDER BY DATE(created_at)");
$users_data = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

$stmt_payments = $pdo->query("SELECT SUM(amount) as total_payment, DATE(payment_date) as date FROM payments GROUP BY DATE(payment_date) ORDER BY DATE(payment_date)");
$payments_data = $stmt_payments->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../assets/css/home.css">
</head>
<body>
        
        <div class="content">
            <h1>Admin Dashboard</h1>

            <div class="graph-container">
                <!-- Users Graph -->
                <div class="graph-box">
                    <h2>Users Over Time</h2>
                    <canvas id="usersChart"></canvas>
                </div>
                
                <!-- Payments Graph -->
                <div class="graph-box">
                    <h2>Total Payments Over Time</h2>
                    <canvas id="paymentsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Users Graph
        const usersData = <?php echo json_encode($users_data); ?>;
        const usersLabels = usersData.map(item => item.date);
        const usersCounts = usersData.map(item => item.user_count);

        const usersCtx = document.getElementById('usersChart').getContext('2d');
        const usersChart = new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: usersLabels,
                datasets: [{
                    label: 'Number of Users',
                    data: usersCounts,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { 
                        title: { display: true, text: 'Date' } 
                    },
                    y: { 
                        title: { display: true, text: 'Users Count' },
                        beginAtZero: true 
                    }
                }
            }
        });

        // Payments Graph
        const paymentsData = <?php echo json_encode($payments_data); ?>;
        const paymentsLabels = paymentsData.map(item => item.date);
        const paymentsTotal = paymentsData.map(item => item.total_payment);

        const paymentsCtx = document.getElementById('paymentsChart').getContext('2d');
        const paymentsChart = new Chart(paymentsCtx, {
            type: 'line',
            data: {
                labels: paymentsLabels,
                datasets: [{
                    label: 'Total Payments',
                    data: paymentsTotal,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { 
                        title: { display: true, text: 'Date' } 
                    },
                    y: { 
                        title: { display: true, text: 'Total Payments' },
                        beginAtZero: true 
                    }
                }
            }
        });
    </script>
</body>
</html>

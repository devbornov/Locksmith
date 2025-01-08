<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Get the page to load dynamically
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LockSmith Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>LockSmith Admin Dashboard</h2>
            <ul>
                <li><a href="?page=home">Dashboard Home</a></li>
                <li><a href="?page=manage_users">Manage Users</a></li>
                <li><a href="?page=manage_locksmiths">Approve/Reject Locksmiths</a></li>
                <li><a href="?page=update_car_key">Car Key Database</a></li>
                <li><a href="?page=service_requests">Monitor Services</a></li>
                <li><a href="?page=commission_settings">Set Commission</a></li>
                <li><a href="?page=locksmith_performance">Locksmith Performance</a></li>
                <li><a href="?page=payment_management">Payment Management</a></li>
                <li><a href="?page=View_Statistics">View Statistics</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <?php
            // Dynamically include the selected page
            $allowed_pages = [
                'home', 
                'manage_users', 
                'manage_locksmiths', 
                'update_car_key', 
                'service_requests', 
                'commission_settings',
                'locksmith_performance' ,
                'payment_management', 
                'View_Statistics'
            ];

            if (in_array($page, $allowed_pages)) {
                include $page . '.php';
            } else {
                echo "<h1>404 Page Not Found</h1>";
            }
            ?>
        </main>
    </div>
</body>
</html>

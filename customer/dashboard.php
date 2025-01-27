<?php
session_start();

// Check if the user is logged in and is a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
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
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Customer Dashboard</h2>
            <ul>
                <li><a href="?page=home">Dashboard Home</a></li>
                <li><a href="?page=view_locksmiths">View Available Locksmiths</a></li>
                <li><a href="?page=bidding">Bidding for Services</a></li>
                <li><a href="?page=service_booking">Service Booking</a></li>
                <li><a href="?page=payments">Payments</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <?php
            // Dynamically include the selected page
            $allowed_pages = [
                'home', 
                'view_locksmiths', 
                'bidding', 
                'service_booking', 
                'payments',
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

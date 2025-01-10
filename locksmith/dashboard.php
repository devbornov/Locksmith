<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'locksmith') {
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
    <title>LockSmith  Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>LockSmith  Dashboard</h2>
            <ul>
                <li><a href="?page=home">Dashboard Home</a></li>
                <li><a href="?page=locksmith_services">Set Price</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <?php
            // Dynamically include the selected page
            $allowed_pages = [
                'home', 
                'locksmith_services', 
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

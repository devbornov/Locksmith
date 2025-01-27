<?php
require '../config.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Logged-in customer ID

// Fetch available locksmiths based on their status (approved)
$stmt = $pdo->prepare("SELECT * FROM locksmith_details WHERE status = 'approved'"); 
$stmt->execute();
$locksmiths = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Locksmiths</title>
    <link rel="stylesheet" href="../assets/css/viewlocksmith.css">
</head>
<body>
    <div class="container">
        <h2 class="title">Available Locksmiths</h2>

        <?php
        if (empty($locksmiths)) {
            echo "<h3>No locksmiths available in your area currently.</h3>";
        } else {
            echo "<table class='locksmith-table'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Service Area</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($locksmiths as $locksmith) {
                echo "<tr>
                        <td>" . htmlspecialchars($locksmith['name']) . "</td>
                        <td>" . htmlspecialchars($locksmith['service_area']) . "</td>
                        <td>" . htmlspecialchars($locksmith['phone']) . "</td>
                        <td>" . htmlspecialchars($locksmith['address']) . "</td>
                        <td>" . htmlspecialchars($locksmith['service_area']) . "</td>
                        <td>" . htmlspecialchars($locksmith['status']) . "</td>
                        <td>" . htmlspecialchars($locksmith['created_at']) . "</td>
                    </tr>";
            }

            echo "</tbody></table>";
        }
        ?>
    </div>
</body>
</html>

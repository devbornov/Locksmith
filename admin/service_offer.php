<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Get all available services
$stmt = $pdo->query("SELECT * FROM services");
$services = $stmt->fetchAll();

// Handle adding a new service offering
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locksmith_id = $_POST['locksmith_id'];
    $service_id = $_POST['service_id'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("INSERT INTO service_offerings (locksmith_id, service_id, price, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$locksmith_id, $service_id, $price]);

    header("Location: service_offer.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Offerings</title>
</head>
<body>
    <div class="container">
        <h1>Manage Service Offerings</h1>
        <form action="service_offer.php" method="POST">
            <label for="locksmith_id">Locksmith:</label>
            <select name="locksmith_id" id="locksmith_id">
                <!-- Populate with locksmiths -->
                <?php
                $stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'locksmith'");
                $locksmiths = $stmt->fetchAll();
                foreach ($locksmiths as $locksmith) {
                    echo "<option value='{$locksmith['id']}'>{$locksmith['username']}</option>";
                }
                ?>
            </select>

            <label for="service_id">Service:</label>
            <select name="service_id" id="service_id">
                <!-- Populate with services -->
                <?php
                $stmt = $pdo->query("SELECT id, name FROM services");
                $services = $stmt->fetchAll();
                foreach ($services as $service) {
                    echo "<option value='{$service['id']}'>{$service['name']}</option>";
                }
                ?>
            </select>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" required>

            <button type="submit">Add Service Offering</button>
        </form>

        <h2>Existing Service Offerings</h2>
        <table>
            <thead>
                <tr>
                    <th>Locksmith</th>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Get all service offerings
                $stmt = $pdo->query("SELECT * FROM service_offerings");
                $offerings = $stmt->fetchAll();

                foreach ($offerings as $offering) {
                    // Get locksmith and service details
                    $stmt_locksmith = $pdo->prepare("SELECT username FROM users WHERE id = ?");
                    $stmt_locksmith->execute([$offering['locksmith_id']]);
                    $locksmith = $stmt_locksmith->fetch();

                    $stmt_service = $pdo->prepare("SELECT name FROM services WHERE id = ?");
                    $stmt_service->execute([$offering['service_id']]);
                    $service = $stmt_service->fetch();

                    echo "<tr>
                            <td>{$locksmith['username']}</td>
                            <td>{$service['name']}</td>
                            <td>{$offering['price']}</td>
                            <td>
                                <a href='delete_service.php?id={$offering['id']}'>Delete</a>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

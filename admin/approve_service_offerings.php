<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Handle Approve or Reject Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['offering_id'])) {
    $action = $_POST['action'];
    $offering_id = $_POST['offering_id'];

    if (in_array($action, ['approve', 'reject'])) {
        try {
            // Determine the status based on the action
            $status = ($action === 'approve') ? 'approved' : 'rejected';

            // Update the status in the database
            $stmt = $pdo->prepare("UPDATE service_offerings SET status = ? WHERE id = ?");
            $stmt->execute([$status, $offering_id]);

            // Provide feedback based on the result
            if ($stmt->rowCount() > 0) {
                $message = "Service offering successfully marked as $status.";
            } else {
                $message = "Failed to update status.";
            }
        } catch (PDOException $e) {
            // Handle any potential errors
            $message = "Error: " . $e->getMessage();
        }
    }
}

// Fetch all service offerings by status (pending, approved, rejected)
$stmtPending = $pdo->query("
    SELECT so.id, so.price, so.status, ld.name AS locksmith_name, s.name AS service_name
    FROM service_offerings so
    JOIN locksmith_details ld ON so.locksmith_id = ld.id
    JOIN services s ON so.service_id = s.id
    WHERE so.status = 'pending'
");

$stmtApproved = $pdo->query("
    SELECT so.id, so.price, so.status, ld.name AS locksmith_name, s.name AS service_name
    FROM service_offerings so
    JOIN locksmith_details ld ON so.locksmith_id = ld.id
    JOIN services s ON so.service_id = s.id
    WHERE so.status = 'approved'
");

$stmtRejected = $pdo->query("
    SELECT so.id, so.price, so.status, ld.name AS locksmith_name, s.name AS service_name
    FROM service_offerings so
    JOIN locksmith_details ld ON so.locksmith_id = ld.id
    JOIN services s ON so.service_id = s.id
    WHERE so.status = 'rejected'
");

$offeringsPending = $stmtPending->fetchAll();
$offeringsApproved = $stmtApproved->fetchAll();
$offeringsRejected = $stmtRejected->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Service Offerings</title>
    <link rel="stylesheet" href="../assets/css/manage_users.css">
</head>
<body>
    <div class="container">
        <h1>Approve Service Offerings</h1>

        <!-- Display Success or Error Messages -->
        <?php if (isset($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <!-- Pending Offerings Table -->
        <h2>Pending Service Offerings</h2>
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Locksmith</th>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($offeringsPending as $offering): ?>
                    <tr>
                        <td><?= htmlspecialchars($offering['id']) ?></td>
                        <td><?= htmlspecialchars($offering['locksmith_name']) ?></td>
                        <td><?= htmlspecialchars($offering['service_name']) ?></td>
                        <td><?= htmlspecialchars($offering['price']) ?></td>
                        <td><?= htmlspecialchars($offering['status']) ?></td>
                        <td>
                            <!-- Approve Button -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="offering_id" value="<?= $offering['id'] ?>">
                                <button type="submit" name="action" value="approve" class="action-button approve">Approve</button>
                            </form>

                            <!-- Reject Button -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="offering_id" value="<?= $offering['id'] ?>">
                                <button type="submit" name="action" value="reject" class="action-button reject">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Approved Offerings Table -->
        <h2>Approved Service Offerings</h2>
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Locksmith</th>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($offeringsApproved as $offering): ?>
                    <tr>
                        <td><?= htmlspecialchars($offering['id']) ?></td>
                        <td><?= htmlspecialchars($offering['locksmith_name']) ?></td>
                        <td><?= htmlspecialchars($offering['service_name']) ?></td>
                        <td><?= htmlspecialchars($offering['price']) ?></td>
                        <td><?= htmlspecialchars($offering['status']) ?></td>
                        <td>
                            <!-- Approve Button (to approve again if rejected) -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="offering_id" value="<?= $offering['id'] ?>">
                                <button type="submit" name="action" value="approve" class="action-button approve">Approve</button>
                            </form>

                            <!-- Reject Button (to reject if needed) -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="offering_id" value="<?= $offering['id'] ?>">
                                <button type="submit" name="action" value="reject" class="action-button reject">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Rejected Offerings Table -->
        <h2>Rejected Service Offerings</h2>
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Locksmith</th>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($offeringsRejected as $offering): ?>
                    <tr>
                        <td><?= htmlspecialchars($offering['id']) ?></td>
                        <td><?= htmlspecialchars($offering['locksmith_name']) ?></td>
                        <td><?= htmlspecialchars($offering['service_name']) ?></td>
                        <td><?= htmlspecialchars($offering['price']) ?></td>
                        <td><?= htmlspecialchars($offering['status']) ?></td>
                        <td>
                            <!-- Approve Button (to approve again if needed) -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="offering_id" value="<?= $offering['id'] ?>">
                                <button type="submit" name="action" value="approve" class="action-button approve">Approve</button>
                            </form>

                            <!-- Reject Button (to reject again if needed) -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="offering_id" value="<?= $offering['id'] ?>">
                                <button type="submit" name="action" value="reject" class="action-button reject">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

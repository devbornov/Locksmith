<?php
require '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch pending locksmith registrations
$stmt = $pdo->query("SELECT * FROM locksmith_registrations WHERE status = 'pending'");
$registrations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Locksmith Registrations</title>
    <link rel="stylesheet" href="../assets/css/manage_locksmiths.css">
</head>
<body>
    <div class="container">
        <h1>Manage Locksmith Registrations</h1>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'approved'): ?>
            <p class="success">Locksmith approved successfully!</p>
        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'rejected'): ?>
            <p class="error">Locksmith rejected successfully!</p>
        <?php endif; ?>

        <table class="locksmith-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Registration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $registration): ?>
                <tr>
                    <td><?= htmlspecialchars($registration['username']) ?></td>
                    <td><?= htmlspecialchars($registration['email']) ?></td>
                    <td><?= htmlspecialchars($registration['registration_date']) ?></td>
                    <td>
                        <a class="action-button approve" href="approve_locksmith.php?id=<?= $registration['id'] ?>">Approve</a>
                        <br> <br> 
                        <a class="action-button reject" href="reject_locksmith.php?id=<?= $registration['id'] ?>">Reject</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

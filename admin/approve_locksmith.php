<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $locksmith_id = $_GET['id'];

    // Fetch the locksmith registration details
    $stmt = $pdo->prepare("SELECT * FROM locksmith_registrations WHERE id = ?");
    $stmt->execute([$locksmith_id]);
    $registration = $stmt->fetch();

    if ($registration) {
        // Hash the password before inserting it into the users table
        $hashed_password = password_hash($registration['password'], PASSWORD_DEFAULT);

        // Insert into the users table
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$registration['username'], $registration['email'], $hashed_password, 'locksmith']);

        // Delete the registration record from the locksmith_registrations table
        $stmt = $pdo->prepare("DELETE FROM locksmith_registrations WHERE id = ?");
        $stmt->execute([$locksmith_id]);

        // Redirect to the manage users page with a success message
        header("Location: http://localhost/locksmith2/admin/dashboard.php?page=manage_users&status=approved");
        exit();
    } else {
        // Handle the case where the registration does not exist
        header("Location: http://localhost/locksmith2/admin/dashboard.php?page=manage_users&status=error");
        exit();
    }
}
?>

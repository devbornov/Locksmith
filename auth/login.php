<?php
require '../config.php';

// Start a session to manage user state
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect user credentials from the login form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute SQL to fetch user by username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Check if the user exists and password is correct
    if ($user && password_verify($password, $user['password_hash'])) {
        // User authentication is successful, start the session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; // Store the user role in the session
        
        // Check if the user has the 'customer' role
        if ($user['role'] == 'customer') {
            // Check if customer details are filled out
            $stmt = $pdo->prepare("SELECT * FROM customers_details WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $address = $stmt->fetch();

            if ($address) {
                // If address details exist, redirect to the customer dashboard
                header("Location: ../customer/dashboard.php");
                exit;
            } else {
                // If no address details are found, redirect to the address filling form
                header("Location: ../customer/fill_address.php");
                exit;
            }
        } else {
            // If the user is not a customer, redirect to their appropriate dashboard
            header("Location: ../" . $user['role'] . "/dashboard.php");
            exit;
        }
    } else {
        // Invalid login credentials
        echo "<p class='error'>Invalid credentials!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>

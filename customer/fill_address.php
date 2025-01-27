<?php
require '../config.php';
session_start();

// Check if the user is logged in and has a customer role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect customer details from the form
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];
    $city = $_POST['city'];
    $nearby = $_POST['nearby'];

    // Validate input to ensure no null values
    if (!empty($name) && !empty($phone) && !empty($address) && !empty($pincode) && !empty($city) && !empty($nearby)) {
        // Insert customer details into the database
        $stmt = $pdo->prepare("INSERT INTO customers_details (user_id, name, phone, address, pincode, city, nearby) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $phone, $address, $pincode, $city, $nearby]);

        // Redirect to the customer dashboard after filling details
        header("Location: ../customer/dashboard.php");
        exit;
    } else {
        // Error message if any field is left empty
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fill Address Details</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Fill Your Address Details</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" required></textarea>
            </div>
            <div class="form-group">
                <label for="pincode">Pincode:</label>
                <input type="text" id="pincode" name="pincode" required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="nearby">Nearby Landmark:</label>
                <input type="text" id="nearby" name="nearby" required>
            </div>
            <button type="submit" class="btn">Submit</button>
        </form>
    </div>
</body>
</html>

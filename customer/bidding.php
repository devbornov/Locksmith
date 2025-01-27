<?php
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_type = $_POST['service_type'];
    $car_key_details = $_POST['car_key_details'];
    $price_bid = $_POST['price_bid'];
    $user_id = $_SESSION['user_id']; // Logged-in customer ID

    // Insert the bid into the database
    $stmt = $pdo->prepare("INSERT INTO service_bids (user_id, service_type, car_key_details, price_bid) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $service_type, $car_key_details, $price_bid]);

    echo "<p>Your bid has been placed successfully!</p>";
}
?>

<h2>Place Your Bid</h2>
<form method="POST">
    <label for="service_type">Service Type:</label>
    <select name="service_type" id="service_type" required>
        <option value="locksmith">Locksmith Service</option>
        <option value="car_key">Car Key Duplication</option>
    </select>
    <br><br>
    <label for="car_key_details">Car Key Details (if applicable):</label>
    <textarea name="car_key_details" id="car_key_details"></textarea>
    <br><br>
    <label for="price_bid">Bid Price:</label>
    <input type="number" name="price_bid" id="price_bid" required>
    <br><br>
    <button type="submit">Place Bid</button>
</form>

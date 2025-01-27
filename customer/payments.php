<?php
require '../config.php';

$user_id = $_SESSION['user_id']; // Logged-in customer ID

// Fetch payment details (assume there's a service booking with a price and commission rate)
$stmt = $pdo->prepare("SELECT s.price, l.commission_rate FROM service_bookings sb JOIN locksmiths l ON sb.locksmith_id = l.id WHERE sb.user_id = ?");
$stmt->execute([$user_id]);
$payment_details = $stmt->fetch();

if (!$payment_details) {
    echo "<p>No services booked yet.</p>";
} else {
    $final_price = $payment_details['price'] - ($payment_details['price'] * $payment_details['commission_rate'] / 100);
    echo "<h2>Payment Details</h2>";
    echo "<p>Service Price: $" . htmlspecialchars($payment_details['price']) . "</p>";
    echo "<p>Commission Deduction: " . htmlspecialchars($payment_details['commission_rate']) . "%</p>";
    echo "<p>Amount to Pay: $" . number_format($final_price, 2) . "</p>";
    echo "<form method='POST' action='process_payment.php'>
            <button type='submit'>Pay Now</button>
          </form>";
}
?>

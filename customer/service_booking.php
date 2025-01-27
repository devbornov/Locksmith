<?php
require '../config.php';

$user_id = $_SESSION['user_id']; // Logged-in customer ID

// Fetch the available locksmiths in the customer's area
$stmt = $pdo->prepare("SELECT * FROM locksmiths WHERE available = 1");
$stmt->execute();
$locksmiths = $stmt->fetchAll();

echo "<h2>Book Your Service</h2>";

if (empty($locksmiths)) {
    echo "<p>No locksmiths available in your area right now.</p>";
} else {
    echo "<form method='POST'>";
    echo "<label for='locksmith_id'>Select a Locksmith:</label>";
    echo "<select name='locksmith_id' id='locksmith_id' required>";
    
    foreach ($locksmiths as $locksmith) {
        echo "<option value='" . $locksmith['id'] . "'>" . htmlspecialchars($locksmith['name']) . " - $" . htmlspecialchars($locksmith['price']) . "</option>";
    }

    echo "</select><br><br>";
    echo "<button type='submit'>Book Service</button>";
    echo "</form>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $locksmith_id = $_POST['locksmith_id'];
        
        // Insert the booking into the database
        $stmt = $pdo->prepare("INSERT INTO service_bookings (user_id, locksmith_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $locksmith_id]);

        echo "<p>Your service has been booked successfully!</p>";
    }
}
?>

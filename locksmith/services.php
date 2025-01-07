<?php
require '../config.php';
session_start();

if ($_SESSION['role'] !== 'locksmith') {
    header("Location: ../auth/login.php");
    exit();
}

$locksmith_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_id = $_POST['service_id'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("INSERT INTO service_offerings (locksmith_id, service_id, price) VALUES (?, ?, ?)");
    $stmt->execute([$locksmith_id, $service_id, $price]);
}

$services = $pdo->query("SELECT * FROM services")->fetchAll();
?>

<h1>Manage Services</h1>
<form method="POST">
    <select name="service_id">
        <?php foreach ($services as $service): ?>
        <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <input type="number" name="price" placeholder="Price" required>
    <button type="submit">Add Service</button>
</form>

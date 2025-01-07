<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch current commission settings
$stmt = $pdo->query("SELECT * FROM commission_settings LIMIT 1");
$commission = $stmt->fetch();

// Handle commission update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $percentage = $_POST['percentage'];
    // Ensure the percentage is between 0 and 100
    if ($percentage >= 0 && $percentage <= 100) {
        $stmt = $pdo->prepare("UPDATE commission_settings SET percentage = ?, created_at = NOW() WHERE id = 1");
        $stmt->execute([$percentage]);
        header("Location: http://localhost/locksmith2/admin/dashboard.php?page=commission_settings");
        exit();
    } else {
        $error_message = "Please enter a valid percentage between 0 and 100.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission Settings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        label {
            font-size: 18px;
        }
        input[type="number"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 16px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Commission Settings</h1>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form action="commission_settings.php" method="POST">
            <label for="percentage">Commission Percentage:</label>
            <input 
                type="number" 
                id="percentage" 
                name="percentage" 
                value="<?= htmlspecialchars($commission['percentage']) ?>" 
                step="0.01" 
                min="0" 
                max="100" 
                required
            >
            <button type="submit">Update</button>
        </form>
    </div>

    <script>
        document.getElementById('percentage').addEventListener('input', function (event) {
            const value = parseFloat(event.target.value);
            if (value < 0 || value > 100) {
                alert('Please enter a value between 0 and 100.');
                event.target.value = '';
            }
        });
    </script>
</body>
</html>

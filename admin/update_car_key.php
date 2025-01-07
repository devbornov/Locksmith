<?php
require '../config.php';

$message = "";

// Handle form submissions for add and edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $manufacturer = trim($_POST['manufacturer']);
        $model = trim($_POST['model']);
        $number_of_buttons = $_POST['number_of_buttons'];
        $year_from = $_POST['year_from'];
        $year_to = $_POST['year_to'];

        // Validate input
        if (empty($manufacturer) || empty($model) || empty($number_of_buttons) || empty($year_from) || empty($year_to)) {
            $message = "All fields are required!";
        } elseif (!is_numeric($year_from) || !is_numeric($year_to) || $year_from > $year_to) {
            $message = "Invalid year range!";
        } else {
            if ($action === 'add') {
                // Add a new record
                $stmt = $pdo->prepare("INSERT INTO car_key_details (manufacturer, model, number_of_buttons, year_from, year_to) VALUES (?, ?, ?, ?, ?)");
                $success = $stmt->execute([$manufacturer, $model, $number_of_buttons, $year_from, $year_to]);
                if ($success) {
                    header("Location: http://localhost/locksmith2/admin/dashboard.php?page=update_car_key");
                    exit();
                } else {
                    $message = "Failed to add car key.";
                }
            } elseif ($action === 'edit') {
                // Edit an existing record
                $id = $_POST['id'];
                $stmt = $pdo->prepare("UPDATE car_key_details SET manufacturer = ?, model = ?, number_of_buttons = ?, year_from = ?, year_to = ? WHERE id = ?");
                $success = $stmt->execute([$manufacturer, $model, $number_of_buttons, $year_from, $year_to, $id]);
                if ($success) {
                    header("Location: http://localhost/locksmith2/admin/dashboard.php?page=update_car_key");
                    exit();
                } else {
                    $message = "Failed to update car key.";
                }
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM car_key_details WHERE id = ?");
    $success = $stmt->execute([$id]);
    if ($success) {
        header("Location: http://localhost/locksmith2/admin/dashboard.php?page=update_car_key");
    } else {
        header("Location: http://localhost/locksmith2/admin/dashboard.php?page=update_car_key");
    }
    exit();
}

// Fetch all car key details
$stmt = $pdo->query("SELECT * FROM car_key_details ORDER BY id DESC");
$car_keys = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Car Key Details</title>
    <link rel="stylesheet" href="../assets/css/manage_car_key_details.css">
</head>
<body>
    <div class="container">
        <h1>Manage Car Key Details</h1>

        <?php if (isset($_GET['status'])): ?>
            <?php
                switch ($_GET['status']) {
                    case 'added':
                        echo '<p class="success">Car key added successfully!</p>';
                        break;
                    case 'updated':
                        echo '<p class="success">Car key updated successfully!</p>';
                        break;
                    case 'deleted':
                        echo '<p class="success">Car key deleted successfully!</p>';
                        break;
                    case 'error':
                        echo '<p class="error">An error occurred while processing your request.</p>';
                        break;
                }
            ?>
        <?php endif; ?>

        <!-- Add/Edit Form -->
        <form method="POST">
            <h2 id="form-title">Add Car Key</h2>
            <input type="hidden" name="id" id="car-key-id">
            <input type="hidden" name="action" id="form-action" value="add">

            <div class="form-group">
                <label for="manufacturer">Manufacturer:</label>
                <input type="text" id="manufacturer" name="manufacturer" required>
            </div>
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" required>
            </div>
            <div class="form-group">
                <label for="number_of_buttons">Number of Buttons:</label>
                <input type="number" id="number_of_buttons" name="number_of_buttons" required>
            </div>
            <div class="form-group">
                <label for="year_from">Year From:</label>
                <input type="number" id="year_from" name="year_from" required>
            </div>
            <div class="form-group">
                <label for="year_to">Year To:</label>
                <input type="number" id="year_to" name="year_to" required>
            </div>
            <button type="submit" class="btn">Submit</button>
        </form>

        <!-- Table for listing car keys -->
        <table class="car-key-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Manufacturer</th>
                    <th>Model</th>
                    <th>Number of Buttons</th>
                    <th>Year From</th>
                    <th>Year To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($car_keys) > 0): ?>
                    <?php foreach ($car_keys as $key): ?>
                        <tr>
                            <td><?= htmlspecialchars($key['id']) ?></td>
                            <td><?= htmlspecialchars($key['manufacturer']) ?></td>
                            <td><?= htmlspecialchars($key['model']) ?></td>
                            <td><?= htmlspecialchars($key['number_of_buttons']) ?></td>
                            <td><?= htmlspecialchars($key['year_from']) ?></td>
                            <td><?= htmlspecialchars($key['year_to']) ?></td>
                            <td>
                                <button 
                                    class="btn edit-btn"
                                    data-id="<?= $key['id'] ?>"
                                    data-manufacturer="<?= htmlspecialchars($key['manufacturer']) ?>"
                                    data-model="<?= htmlspecialchars($key['model']) ?>"
                                    data-number-of-buttons="<?= $key['number_of_buttons'] ?>"
                                    data-year-from="<?= $key['year_from'] ?>"
                                    data-year-to="<?= $key['year_to'] ?>"
                                >Edit</button>
                                <a href="delete_car_key.php?id=<?= $key['id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this car key?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No car keys found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Handle edit button clicks
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('form-title').textContent = 'Edit Car Key';
                document.getElementById('form-action').value = 'edit';
                document.getElementById('car-key-id').value = this.dataset.id;
                document.getElementById('manufacturer').value = this.dataset.manufacturer;
                document.getElementById('model').value = this.dataset.model;
                document.getElementById('number_of_buttons').value = this.dataset.numberOfButtons;
                document.getElementById('year_from').value = this.dataset.yearFrom;
                document.getElementById('year_to').value = this.dataset.yearTo;
            });
        });
    </script>
</body>
</html>

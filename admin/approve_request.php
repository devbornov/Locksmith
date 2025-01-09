<?php
require '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$request_id = $_GET['id'] ?? null;

if ($request_id) {
    try {
        // Check if the request exists
        $stmt_check = $pdo->prepare("SELECT * FROM service_requests WHERE id = ?");
        $stmt_check->execute([$request_id]);
        $request = $stmt_check->fetch();

        if ($request) {
            // Proceed with updating the request
            $stmt = $pdo->prepare("UPDATE service_requests SET bid_status = 'approved', locksmith_response = 'accepted' WHERE id = ?");
            $stmt->execute([$request_id]);

            // Check if the update was successful
            if ($stmt->rowCount() > 0) {
                // If successful, proceed to the next step or redirect
                // You can add additional actions here like logging or notifications
                
                // Redirect to the service requests page with a success message
                header("Location: http://localhost/locksmith2/admin/dashboard.php?page=service_requests&status=approved");
                exit();
            } else {
                // If no rows were updated (e.g., if the request was already approved)
                echo header("Location: http://localhost/locksmith2/admin/dashboard.php?page=service_requests&status=approved");
            }
        } else {
            // Handle the case where the request id does not exist
            echo "Error: The requested service does not exist.";
        }
    } catch (Exception $e) {
        // Catch any exceptions and show the error
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle case where id is not provided
    echo "Error: Request ID is missing.";
}
?>

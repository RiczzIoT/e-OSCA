<?php
// Simula ng file
session_start();

// Dapat naka-login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

include '../../includes/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    // Kunin ang user ID mula sa query string
    $user_id = $_GET['id'];
    
    // Disable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");

    // I-delete ang user mula sa database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();

    // Redirect after deletion
    header("Location: ../?id=290384756120"); // Adjust the redirect as needed

    // Enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
} else {
    echo "Invalid request.";
}
?>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

include '../../includes/connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Ensure only authorized roles can delete logs
if ($role !== 'admin') {
    header("Location: ../../403.php");
    exit();
}

// Get the image path before deleting logs
$sql = "SELECT image_path FROM user_logs";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $image_path = "../../" . $row['image_path'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
}

// SQL to delete all records from user_logs
$sql = "DELETE FROM user_logs";

if ($conn->query($sql) === TRUE) {
    // Redirect to index.php after successful deletion
    header("Location: ./?id=518736420591");
    exit();
} else {
    echo "Error deleting logs: " . $conn->error;
}

$conn->close();
?>

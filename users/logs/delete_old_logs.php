<?php
include '../../includes/connect.php';

// SQL query to delete logs older than 24 hours
$sql = "DELETE FROM user_logs WHERE log_time < NOW() - INTERVAL 1 SECOND";

if ($conn->query($sql) === TRUE) {
    echo "Old logs deleted successfully.";
} else {
    echo "Error deleting old logs: " . $conn->error;
}

$conn->close();
?>

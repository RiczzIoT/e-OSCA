<?php
include '../includes/connect.php';

$senior_id = $_POST['id'];

$stmt = $conn->prepare("DELETE FROM senior WHERE id = ?");
$stmt->bind_param("i", $senior_id);

if ($stmt->execute()) {
    echo "Senior record deleted successfully.";
} else {
    echo "Failed to delete senior record.";
}
$stmt->close();
$conn->close();
?>

<?php
include '../includes/connect.php';

$senior_id = $_POST['id'];

$stmt = $conn->prepare("UPDATE senior SET status = 'active', delete_requested_by = NULL WHERE id = ?");
$stmt->bind_param("i", $senior_id);

if ($stmt->execute()) {
    echo "Delete request denied successfully.";
} else {
    echo "Failed to deny delete request.";
}
$stmt->close();
$conn->close();
?>

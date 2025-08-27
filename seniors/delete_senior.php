<?php
// Start session to access $_SESSION variables
session_start();

// Include database connection
include '../includes/connect.php';

// Check if ID is set and valid
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Ensure the user is logged in and has a user ID
    if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
        $requested_by = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        // Insert delete request, update `status` to pending instead of deleting the record
        $sql = "UPDATE senior SET status = 'pending', requested_by = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $requested_by, $id);

        if ($stmt->execute()) {
            // Delete request submitted successfully
            echo "Delete request submitted successfully.";
        } else {
            // Error in updating status
            echo "Error submitting delete request: " . $conn->error;
        }

        $stmt->close();
    } else {
        // User is not logged in or session data missing
        echo "Error: Unauthorized access.";
    }
} else {
    // ID not set or invalid
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>

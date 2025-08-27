<?php
// Include database connection
include '../includes/connect.php';

// Check if ID is set and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to fetch resident information
    $sql = "SELECT * FROM senior WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Generate resident information content
        echo "<div class='container'>
                <div class='id-content'>
                    <div class='profile-picture'><img src='{$row['profile_picture']}' alt='Profile Picture'></div>
                    <h2>{$row['first_name']} {$row['middle_name']} {$row['last_name']} {$row['suffix']}</h2>
                    <p><strong>Address:</strong> {$row['unit_room_floor']} {$row['building_name']} {$row['lot']} {$row['block']} {$row['phase']} {$row['house_number']} {$row['street']} {$row['subdivision']}, {$row['zone_no']}</p>
                    <p><strong>Birthdate:</strong> {$row['birthdate']}</p>
                    <p><strong>Citizenship:</strong> {$row['citizenship']}</p>
                    <p><strong>Sex:</strong> {$row['sex']}</p>
                    <p><strong>Civil Status:</strong> {$row['civil_status']}</p>
                    <p><strong>Contact:</strong> {$row['contact_type']} - {$row['contact']}</p>
                    <p><strong>Height:</strong> {$row['height']}</p>
                    <p><strong>Weight:</strong> {$row['weight']}</p>
                    <p><strong>Religion:</strong> {$row['religion']}</p>
                    <div class='qr-code'><img src='{$row['qr_code']}' alt='QR Code'></div>
                </div>
              </div>";
    } else {
        echo "<p>No resident found with ID: $id</p>";
    }
} else {
    echo "<p>Invalid ID</p>";
}

// Close the database connection
$conn->close();
?>

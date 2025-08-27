<?php
include '../includes/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $suffix = $_POST['suffix'];
    $birthdate = $_POST['birthdate'];

    // Insert into get_id table
    $sql = "INSERT INTO get_id (first_name, middle_name, last_name, suffix, birthdate) 
            VALUES ('$first_name', '$middle_name', '$last_name', '$suffix', '$birthdate')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        echo "New record created successfully. Last inserted ID is: " . $last_id;
        header("Location: generate_pdf.php?id=$last_id");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

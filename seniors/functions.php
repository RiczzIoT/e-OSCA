<?php
include '../includes/connect.php';
include '../qr/qrlib.php'; // Check mo 'tong path na to kung tama

if (isset($_POST['insert'])) {
    // Collect form data
    $id_number = $_POST['id_number'];
    $email = $_POST['email'];
    $province = $_POST['province'];
    $municipal = $_POST['municipal'];
    $barangay = $_POST['barangay'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $suffix = $_POST['suffix'];
    $sex = $_POST['sex'];
    $birthplace = $_POST['birthplace'];
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'];
    $civil_status = $_POST['civil_status'];
    $contact_type = $_POST['contact_type'];
    $contact = $_POST['contact'];
    $blood_type = $_POST['blood_type'];
    $religion = $_POST['religion'];
    $educ = $_POST['educ'];
    $gsis = $_POST['gsis'];
    $sss = $_POST['sss'];
    $philhealth = $_POST['philhealth'];
    $emp_status = $_POST['emp_status'];
    $classification = $_POST['classification'];
    $pension = $_POST['pension'];
    $citizenship = $_POST['citizenship'];
    $emergency = $_POST['emergency'];

    // Get the next queue number
    $queueQuery = "SELECT COALESCE(MAX(queue_number), 0) + 1 AS next_queue_number FROM senior";
    $result = $conn->query($queueQuery);
    $row = $result->fetch_assoc();
    $queue_number = $row['next_queue_number'];

    if ($queue_number > 50000) {
        session_start();
        $_SESSION['error'] = 'Queue number limit reached. Cannot register more seniors.';
        header("Location: ./add/");
        exit();
    }

    // Check for duplicate
    $checkQuery = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM senior 
        WHERE id_number = ? 
           OR email = ? 
           OR gsis = ? 
           OR sss = ? 
           OR philhealth = ?
    ");
    $checkQuery->bind_param("sssss", $id_number, $email, $gsis, $sss, $philhealth);
    $checkQuery->execute();
    $result = $checkQuery->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        session_start();
        $_SESSION['error'] = 'A senior account with the same Info already exists.';
        header("Location: ./add/");
        exit();
    }

    // Handle profile picture upload
    $profile_picture = '';
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "../uploads/";
        $profile_picture = $target_dir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture)) {
            // File uploaded successfully
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    }

    // Generating QR code
    $qrData = "ID Number: $id_number\n" .
        "Province: $province\n" .
        "Municipal: $municipal\n" .
        "Barangay: $barangay\n" .
        "First Name: $first_name\n" .
        "Middle Name: $middle_name\n" .
        "Last Name: $last_name\n" .
        "Suffix: $suffix\n" .
        "Sex: $sex\n" .
        "Birthplace: $birthplace\n" .
        "Birthdate: $birthdate\n" .
        "Age: $age\n" .
        "Civil Status: $civil_status\n" .
        "Contact Type: $contact_type\n" .
        "Contact: $contact\n" .
        "Email: $email\n" .
        "Blood Type: $blood_type\n" .
        "Religion: $religion\n" .
        "Highest Educational Attainment: $educ\n" .
        "Employment Status: $emp_status\n" .
        "Classification: $classification\n" .
        "Citizenship: $citizenship\n" .
        "Emergency: $emergency\n" .
        "Queue Number: $queue_number";

    $qrFileName = '../qr-codes/' . uniqid() . '.png';
    QRcode::png($qrData, $qrFileName);

    // Insert query
    $sql = "INSERT INTO senior (
        id_number, first_name, middle_name, last_name, suffix, sex, birthplace, birthdate, age, civil_status, 
        contact_type, contact, email, barangay, municipal, province, blood_type, religion, highest_educ_attainment, 
        gsis, sss, philhealth, employment_status, classification, monthly_pension, citizenship, emergency, 
        profile_picture, qr_code, queue_number
    ) 
    VALUES (
        '$id_number', '$first_name', '$middle_name', '$last_name', '$suffix', '$sex', '$birthplace', '$birthdate', 
        '$age', '$civil_status', '$contact_type', '$contact', '$email', '$barangay', '$municipal', '$province', 
        '$blood_type', '$religion', '$educ', '$gsis', '$sss', '$philhealth', '$emp_status', '$classification', 
        '$pension', '$citizenship', '$emergency', '$profile_picture', '$qrFileName', '$queue_number'
    )";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully. <br>";
        echo "<img src='$qrFileName'>";
        echo "<br><img src='$profile_picture'>";
        
        // Redirect to index.php
        header("Location: ./index.php?id=783254690132");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    $checkQuery->close();
    mysqli_close($conn);
}
?>

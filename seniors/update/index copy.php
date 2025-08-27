<?php
include '../../includes/connect.php';
include '../../qr/qrlib.php'; // Ensure path to QR code library is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $profile_picture = '';

    // Handle profile picture upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "../uploads/";
        $profile_picture = $target_dir . basename($_FILES["profile_picture"]["name"]);
        if (!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture)) {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    // Sanitize user inputs
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $suffix = mysqli_real_escape_string($conn, $_POST['suffix']);
    $sex = mysqli_real_escape_string($conn, $_POST['sex']);
    $birthplace = mysqli_real_escape_string($conn, $_POST['birthplace']);
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'];
    $civil_status = mysqli_real_escape_string($conn, $_POST['civil_status']);
    $contact_type = mysqli_real_escape_string($conn, $_POST['contact_type']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $emergency = mysqli_real_escape_string($conn, $_POST['emergency']);
    $sss = mysqli_real_escape_string($conn, $_POST['sss']);
    $gsis = mysqli_real_escape_string($conn, $_POST['gsis']);
    $philhealth = mysqli_real_escape_string($conn, $_POST['philhealth']);
    $classification = mysqli_real_escape_string($conn, $_POST['classification']);
    $blood_type = mysqli_real_escape_string($conn, $_POST['blood_type']);
    $highest_educ_attainment = mysqli_real_escape_string($conn, $_POST['highest_educ_attainment']);
    $monthly_pension = mysqli_real_escape_string($conn, $_POST['monthly_pension']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $municipal = mysqli_real_escape_string($conn, $_POST['municipal']);
    $province = mysqli_real_escape_string($conn, $_POST['province']);
    $citizenship = mysqli_real_escape_string($conn, $_POST['citizenship']);
    $religion = mysqli_real_escape_string($conn, $_POST['religion']);
    $employment_status = mysqli_real_escape_string($conn, $_POST['employment_status']);

    // Define the QR code path and filename for consistent updating
    $qrFileName = "../../qr-codes/senior_$id.png";

    // Generate new QR code based on updated information
    $qrData = "ID: $id\n" .
              "First Name: $first_name\n" .
              "Middle Name: $middle_name\n" .
              "Last Name: $last_name\n" .
              "Suffix: $suffix\n" .
              "Sex: $sex\n" .
              "Birthplace: $birthplace\n" .
              "Birthdate: $birthdate\n" .
              "Age: $age\n" .
              "Civil Status: $civil_status\n" .
              "Contact: $contact\n" .
              "Blood Type: $blood_type\n" .
              "Religion: $religion\n" .
              "Citizenship: $citizenship\n" .
              "Emergency: $emergency";
              
    // Generate the QR code and replace the existing file if it exists
    QRcode::png($qrData, $qrFileName);

    // Build SQL update query
    $sql = "UPDATE senior SET 
            first_name = '$first_name', 
            middle_name = '$middle_name', 
            last_name = '$last_name', 
            suffix = '$suffix', 
            sex = '$sex', 
            birthplace = '$birthplace', 
            birthdate = '$birthdate', 
            age = $age, 
            civil_status = '$civil_status', 
            contact_type = '$contact_type', 
            contact = '$contact',
            emergency = '$emergency',
            sss = '$sss',
            gsis = '$gsis',
            philhealth = '$philhealth',
            classification = '$classification',
            blood_type = '$blood_type',
            highest_educ_attainment = '$highest_educ_attainment',
            monthly_pension = '$monthly_pension',
            barangay = '$barangay',
            municipal = '$municipal',
            province = '$province',
            citizenship = '$citizenship', 
            employment_status = '$employment_status', 
            religion = '$religion',
            qr_code = '$qrFileName'";

    // Append profile picture if uploaded
    if (!empty($profile_picture)) {
        $sql .= ", profile_picture = '$profile_picture'";
    }
    
    $sql .= " WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect after successful update
        header("Location: ../?id=964587213670");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Close connection
    $conn->close();
} else {
    // Redirect if accessed without POST request
    header("Location: edit_profiling.php");
    exit();
}
?>
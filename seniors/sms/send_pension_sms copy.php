<?php 
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../index.php");
    exit();
}

include "../../includes/connect.php";

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];

// Only allow specific roles
if (!in_array($role, ["admin", "mayor", "staff"])) {
    header("Location: ../../admin/?id=177985647998");
    exit();
}

// Fetch SMS URL and other settings for the current user
$result = $conn->query(
    "SELECT capture_image, background_image, favicon, logo, security_settings, sms_url, demo FROM settings WHERE user_id=$user_id"
);

$settings = $result->fetch_assoc();
$sms_url = $settings ? $settings["sms_url"] : ""; 

if (empty($sms_url)) {
    $_SESSION['error'] = "SMS URL not configured in settings.";
    header("Location: ./index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST["date"];
    $time = $_POST["time"];
    $custom_message = $_POST["message"];

    // Fetch all seniors with active status
    $sql = "SELECT queue_number, first_name, middle_name, last_name, suffix, contact, monthly_pension 
            FROM senior WHERE status = 'active' AND contact_type = 'Mobile' AND contact IS NOT NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $all_sent = true;
        while ($senior = $result->fetch_assoc()) {
            $queue_number = $senior["queue_number"];
            $full_name = $senior["first_name"] . " " . ($senior["middle_name"] ?? "") . " " . $senior["last_name"] . " " . ($senior["suffix"] ?? "");
            $contact_number = $senior["contact"];
            $monthly_pension = $senior["monthly_pension"];

            // Format the time to 12-hour format with AM/PM
$formatted_time = date("h:i A", strtotime($time));

// Format the date to "Nov, 12 2024"
$formatted_date = date("M, d Y", strtotime($date));

// Then compose the SMS message
$sms_message = "Hello, $full_name. This is a reminder that your monthly pension of PHP $monthly_pension is ready for pickup on $formatted_date at $formatted_time. Your Queue Number: $queue_number. $custom_message";


            // Send SMS and check the result
            $sms_result = sendSMS($contact_number, $sms_message, $sms_url);
            if ($sms_result !== true) {
                $all_sent = false;
                // Set a general error message and stop further SMS sending
                $_SESSION['error'] = "Failed to send SMS to all seniors.";
                header("Location: ./?id=405981726349");
                exit();
            }
        }

        if ($all_sent) {
            $_SESSION['success'] = "SMS sent successfully to all seniors!";
        }
    } else {
        $_SESSION['info'] = "No seniors found with valid contact information.";
    }

    header("Location: ./?id=824607193052");
    exit();
}

// Send SMS function
function sendSMS($phone, $message, $sms_url) {
    $ch = curl_init($sms_url . "?phone=" . urlencode($phone) . "&message=" . urlencode($message));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Return true if the request was successful; otherwise, return false
    return ($http_code == 200) ? true : false;
}
?>

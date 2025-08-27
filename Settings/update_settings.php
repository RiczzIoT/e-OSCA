<?php
session_start();

$allowed_roles = ['mayor', 'admin', 'staff'];
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../index.php");
    exit();
}

include '../includes/connect.php';

$id = null;
if (isset($_GET['id']) && preg_match('/^\d{12}$/', $_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: ../404/");
    exit();
}

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
$capture_image = isset($_POST['capture_image']) ? 1 : 0;
$security_settings = isset($_POST['security_settings']) ? 1 : 0;
$sms_url = isset($_POST['sms_url']) ? $_POST['sms_url'] : "";

if ($user_id === null) {
    error_log("Invalid user ID");
    header("Location: ../error.php?message=Invalid user ID");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT * FROM settings WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE settings SET capture_image = ?, security_settings = ?, sms_url = ? WHERE user_id = ?");
        $stmt->bind_param("iisi", $capture_image, $security_settings, $sms_url, $user_id);
        $stmt->execute();
        echo "Settings updated successfully!";
    } else {
        $stmt = $conn->prepare("INSERT INTO settings (user_id, capture_image, security_settings, sms_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $user_id, $capture_image, $security_settings, $sms_url);
        $stmt->execute();
        echo "New settings created successfully!";
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    echo "Error updating settings. Please try again later.";
}

// Redirect sa role-specific settings page
header("Location: ./?id=401289736548" . $_SESSION['role_id']); // Redirect based on role-specific settings page
exit();
?>
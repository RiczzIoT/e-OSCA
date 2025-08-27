<?php
include '../includes/connect.php';

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id > 0) {
    $stmt = $conn->prepare("SELECT capture_image, security_settings FROM settings WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Convert string '1' or '0' to boolean true/false
        $capture_image = $row['capture_image'] === '1';
        $security_settings = $row['security_settings'] === '1';
        echo json_encode([
            'capture_image' => $capture_image,
            'security_settings' => $security_settings
        ]);
    } else {
        echo json_encode(['capture_image' => false, 'security_settings' => false]);
    }
    
    $stmt->close();
}

$conn->close();
?>

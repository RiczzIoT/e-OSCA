<?php
session_start();
include './includes/connect.php';

// Check if POST data exists
if (isset($_POST['email'], $_POST['password'], $_POST['capture_image'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $capture_image = $_POST['capture_image'];

    // Hash password for comparison
    $password_hash = hash('sha256', $password);

    // Prepare SQL statement with a parameterized query
    $sql = "SELECT * FROM users WHERE email=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password_hash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Check if image capture is enabled
        $settings_result = $conn->query("SELECT capture_image FROM settings WHERE user_id=" . $user['id']);
        $settings = $settings_result->fetch_assoc();

        if ($settings['capture_image']) {
            // Save image
            $image_data = str_replace('data:image/png;base64,', '', $capture_image);
            $image_data = base64_decode($image_data);
            $image_path = './logins/' . time() . '.png';
            file_put_contents($image_path, $image_data);

            // Update user's last login image and time using a prepared statement
            $update_stmt = $conn->prepare("UPDATE users SET image_path=?, login_time=NOW() WHERE id=?");
            $update_stmt->bind_param("si", $image_path, $user['id']);
            $update_stmt->execute();
        } else {
            // Update user's last login time only
            $conn->query("UPDATE users SET login_time=NOW() WHERE id=" . $user['id']);
        }

        // Get user data for logging
        $user_id = $user['id'];
        $user_email = $user['email'];
        $user_name = $user['name'];
        $user_role = $user['role'];
        $log_image_path = isset($image_path) ? $image_path : $user['image_path'];

        // Log user action
        $log_sql = "INSERT INTO user_logs (user_id, email, name, role, action, log_time, image_path) VALUES (?, ?, ?, ?, 'Logged in', NOW(), ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("issss", $user_id, $user_email, $user_name, $user_role, $log_image_path);
        $log_stmt->execute();

        // Set session variable for success message
        $_SESSION['login_success'] = "Login successfully";

        // Redirect to appropriate dashboard
        if ($user['role'] == 'admin') {
            header("Location: ./admin/?id=634829175920");
        } else if ($user['role'] == 'mayor') {
            header("Location: ./admin/?id=405671293814");
        } else if ($user['role'] == 'staff') {
            header("Location: ./admin/?id=823746519032");
        }
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password.";
    }
} else {
    $_SESSION['error'] = "Incomplete form submission.";
}
header("Location: index.php");
exit();
?>

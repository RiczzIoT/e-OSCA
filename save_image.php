<?php
header('Content-Type: application/json');

// Create 'warning' folder if it doesn't exist
if (!file_exists('warning')) {
    mkdir('warning', 0777, true);
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['image'])) {
    $imageData = $data['image'];

    // Remove "data:image/png;base64," part
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $decodedData = base64_decode($imageData);

    // Create a unique filename
    $filename = './backend/warning/' . uniqid() . '.png';

    // Save the image file
    if (file_put_contents($filename, $decodedData)) {
        echo json_encode(['status' => 'success', 'filename' => $filename]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unable to save the file.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data.']);
}
?>

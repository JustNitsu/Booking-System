<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

if (!isset($_FILES['paymentProof']) || !isset($_POST['bookingReference'])) {
    echo json_encode(['success' => false, 'message' => 'Missing file or booking reference.']);
    exit;
}

$bookingReference = $_POST['bookingReference'];
$file = $_FILES['paymentProof'];

// File validation
$allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
$max_size = 5 * 1024 * 1024; // 5MB

if (!in_array($file['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and PDF are allowed.']);
    exit;
}

if ($file['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'File size exceeds 5MB limit.']);
    exit;
}

// Create a unique filename
$upload_dir = '../assets/payment_proofs/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
$file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$new_filename = $bookingReference . '_' . time() . '.' . $file_extension;
$upload_path = $upload_dir . $new_filename;

// Move the file
if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
    echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
    exit;
}

// Update the database
$sql = "UPDATE bookings SET payment_status = 'pending_verification', payment_proof_path = ? WHERE booking_reference = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ss", $new_filename, $bookingReference);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Payment proof uploaded successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
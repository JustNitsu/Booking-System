<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once 'db.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['bookingReference']) || empty($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data or missing parameters.']);
    exit;
}

$bookingReference = $data['bookingReference'];
$status = $data['status'];

// Validate status
$allowed_statuses = ['cancelled', 'archived'];
if (!in_array($status, $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status provided.']);
    exit;
}

$sql = "UPDATE bookings SET status = ? WHERE booking_reference = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ss", $status, $bookingReference);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => "Booking status updated to $status."]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking not found or status unchanged.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Database update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
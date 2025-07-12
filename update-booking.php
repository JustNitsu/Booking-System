<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once 'db.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['bookingReference'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data or missing booking reference.']);
    exit;
}

$bookingReference = $data['bookingReference'];

// Fields that can be updated
$set_clauses = [];
$params = [];
$types = "";

if (isset($data['firstName'])) {
    $set_clauses[] = 'first_name = ?';
    $params[] = $data['firstName'];
    $types .= 's';
}
if (isset($data['lastName'])) {
    $set_clauses[] = 'last_name = ?';
    $params[] = $data['lastName'];
    $types .= 's';
}
if (isset($data['email'])) {
    $set_clauses[] = 'email = ?';
    $params[] = $data['email'];
    $types .= 's';
}
if (isset($data['phone'])) {
    $set_clauses[] = 'phone = ?';
    $params[] = $data['phone'];
    $types .= 's';
}
if (isset($data['checkin'])) {
    $set_clauses[] = 'checkin_date = ?';
    $params[] = $data['checkin'];
    $types .= 's';
}
if (isset($data['checkout'])) {
    $set_clauses[] = 'checkout_date = ?';
    $params[] = $data['checkout'];
    $types .= 's';
}
if (isset($data['specialRequests'])) {
    $set_clauses[] = 'special_requests = ?';
    $params[] = $data['specialRequests'];
    $types .= 's';
}
if (isset($data['status'])) {
    $set_clauses[] = 'status = ?';
    $params[] = $data['status'];
    $types .= 's';
}
if (isset($data['paymentStatus'])) {
    $set_clauses[] = 'payment_status = ?';
    $params[] = $data['paymentStatus'];
    $types .= 's';
}


if (count($set_clauses) === 0) {
    echo json_encode(['success' => false, 'message' => 'No fields to update.']);
    exit;
}

$sql = "UPDATE bookings SET " . implode(', ', $set_clauses) . " WHERE booking_reference = ?";
$types .= 's';
$params[] = $bookingReference;

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Booking updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
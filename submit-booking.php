<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

// Include the database connection
require_once 'db.php';

// Get the POSTed JSON data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Basic validation
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data.']);
    exit;
}

$required_fields = [
    'firstName', 'lastName', 'email', 'phone', 
    'checkin', 'checkout', 'guests', 'roomName', 'roomPrice', 'totalAmount'
];

foreach ($required_fields as $field) {
    if (empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
}

// Generate a unique booking reference
$bookingReference = 'BK-' . time() . rand(100, 999);

// Calculate number of nights
$checkinDate = new DateTime($data['checkin']);
$checkoutDate = new DateTime($data['checkout']);
$interval = $checkinDate->diff($checkoutDate);
$numberOfNights = $interval->days;


// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO bookings (
    booking_reference,
    first_name,
    last_name,
    email,
    phone,
    checkin_date,
    checkout_date,
    number_of_guests,
    number_of_nights,
    arrival_time,
    special_requests,
    room_name,
    room_price,
    total_amount,
    status,
    payment_status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

// Ensure numeric types are correct before binding
$guests = (int)$data['guests'];
$roomPrice = (float)$data['roomPrice'];
$totalAmount = (float)$data['totalAmount'];
$paymentStatus = 'pending'; // Default payment status

// Bind parameters
$stmt->bind_param("ssssssiiisssdsss",
    $bookingReference,
    $data['firstName'],
    $data['lastName'],
    $data['email'],
    $data['phone'],
    $data['checkin'],
    $data['checkout'],
    $guests,
    $numberOfNights,
    $data['arrivalTime'],
    $data['specialRequests'],
    $data['roomName'],
    $roomPrice,
    $totalAmount,
    $data['status'],
    $paymentStatus
);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'reference' => $bookingReference]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database insert failed: ' . $stmt->error]);
}

// Close connections
$stmt->close();
$conn->close();
?>

<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

   // Database connection
   $host = 'localhost'; // or your MySQL server address
   $dbname = 'oldrock_hotel'; // your actual database name
   $username = 'user'; // your actual database username
   $password = 'password'; // your actual database password

   try {
       $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch(PDOException $e) {
       http_response_code(500);
       echo json_encode(['error' => 'Database connection failed']);
       exit;
   }
   

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required_fields = ['firstName', 'lastName', 'email', 'phone', 'checkin', 'checkout', 'guests'];
foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

// Calculate nights and total
$checkin = new DateTime($input['checkin']);
$checkout = new DateTime($input['checkout']);
$nights = $checkin->diff($checkout)->days;
$total = $nights * $input['roomPrice'];

// Generate booking reference
$booking_ref = 'BK-' . time() . rand(100, 999);

// Insert booking
$sql = "INSERT INTO bookings (
    booking_reference, first_name, last_name, email, phone, 
    room_name, room_price, checkin_date, checkout_date, 
    number_of_guests, number_of_nights, total_amount, 
    arrival_time, special_requests, status, payment_status
) VALUES (
    :booking_ref, :first_name, :last_name, :email, :phone,
    :room_name, :room_price, :checkin, :checkout,
    :guests, :nights, :total,
    :arrival_time, :special_requests, 'pending', 'pending'
)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':booking_ref' => $booking_ref,
        ':first_name' => $input['firstName'],
        ':last_name' => $input['lastName'],
        ':email' => $input['email'],
        ':phone' => $input['phone'],
        ':room_name' => $input['roomName'],
        ':room_price' => $input['roomPrice'],
        ':checkin' => $input['checkin'],
        ':checkout' => $input['checkout'],
        ':guests' => $input['guests'],
        ':nights' => $nights,
        ':total' => $total,
        ':arrival_time' => $input['arrivalTime'] ?? null,
        ':special_requests' => $input['specialRequests'] ?? null
    ]);
    
    echo json_encode([
        'success' => true,
        'booking_reference' => $booking_ref,
        'message' => 'Booking submitted successfully'
    ]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create booking']);
}
?>
<?php
// GET method to retrieve booking by reference
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ref'])) {
    $booking_ref = $_GET['ref'];
    $sql = "SELECT * FROM bookings WHERE booking_reference = :ref";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ref' => $booking_ref]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($booking) {
        echo json_encode(['success' => true, 'booking' => $booking]);
    } else {
        echo json_encode(['error' => 'Booking not found']);
    }
}   else {
        // Handle other request methods or return an error
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Method not allowed']);
    }
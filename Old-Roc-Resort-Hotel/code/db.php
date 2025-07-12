<?php
$host = "localhost";
$user = "root";         // your XAMPP default
$pass = "";             // empty password in XAMPP
$dbname = "oldrock_hotel";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]);
    exit();
}
?>
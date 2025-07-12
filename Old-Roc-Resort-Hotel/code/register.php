<?php
include '../code/db.php'; // MySQL connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = filter_var(trim($_POST["firstName"]), FILTER_SANITIZE_STRING);
    $lastName = filter_var(trim($_POST["lastName"]), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = filter_var(trim($_POST["phone"]), FILTER_SANITIZE_STRING);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.history.back();</script>";
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: login.html");
        exit();
    } else {
        echo "<script>alert('Error: Email already exists or database error.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo "Method Not Allowed";
}
?>

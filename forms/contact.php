<?php
// Set database connection details
$host = '127.0.0.1';
$db   = 'messages'; // Make sure this database exists
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Define DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Try connecting to the database
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    die("Database connection failed: " . $e->getMessage());
}

// Get data from the form
$name    = $_POST['name'] ?? '';
$email   = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

// Debug: Print form data (optional)
// echo "<pre>"; print_r($_POST); echo "</pre>";

// Simple validation
if ($name && $email && $subject && $message) {
    $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $subject, $message]);

    echo "Your message has been sent. Thank you!";
} else {
    echo "Please fill in all fields.";
}
?>

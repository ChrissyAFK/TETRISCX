<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servername = $_ENV['DB_SERVERNAME'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['lines_cleared']) && isset($_SESSION['level']) && isset($_SESSION['username'])) {
    $levelIncrement = 0.01;
    $linesCleared = (int)$_POST['lines_cleared'];  // Ensure lines_cleared is an integer
    $newLevel = $_SESSION['level'] + ($levelIncrement * $linesCleared);
    $userId = $_SESSION['username'];

    // Update the level value in the database using prepared statements
    $stmt = $conn->prepare("UPDATE accounts SET level = ? WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("ds", $newLevel, $userId);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            // Handle the case where no rows were updated, if necessary
        }
        $stmt->close();
    } else {
        die("Prepared statement failed: " . $conn->error);
    }
} else {
    die("Required data not set.");
}

$conn->close();
?>

<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['lines_cleared']) && isset($_SESSION['level']) && isset($_SESSION['username'])) {
    $levelIncrement = 1;
    $linesCleared = (int)$_POST['lines_cleared'];  // Ensure lines_cleared is an integer

    // Debugging output
    error_log("Lines cleared: $linesCleared");
    error_log("Current level: " . $_SESSION['level']);
    
    $newLevel = $_SESSION['level'] + ($levelIncrement * $linesCleared);
    $userId = $_SESSION['username'];

    // Debugging output
    error_log("New level: $newLevel");
    error_log("User ID: $userId");

    // Update the level value in the database using prepared statements
    $stmt = $conn->prepare("UPDATE accounts SET level = ? WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("ds", $newLevel, $userId);
        $stmt->execute();
        
        // Debugging output
        if ($stmt->affected_rows > 0) {
            error_log("Level updated successfully for user $userId");
        } else {
            error_log("No rows affected. Update might have failed or no change was made.");
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

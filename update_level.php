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

header('Content-Type: application/json');

// Check if POST data and session variables are set
if (isset($_POST['linesCleared']) && isset($_SESSION['level']) && isset($_SESSION['username'])) {
    $levelIncrement = 0.01;
    $linesCleared = (int)$_POST['linesCleared'];

    error_log("Lines cleared: $linesCleared");
    error_log("Current level: " . $_SESSION['level']);
    
    $newLevel = $_SESSION['level'] + ($levelIncrement * $linesCleared);
    $userId = $_SESSION['username'];

    error_log("New level: $newLevel");
    error_log("User ID: $userId");

    $stmt = $conn->prepare("UPDATE accounts SET level = ? WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("ds", $newLevel, $userId);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'newLevel' => $newLevel]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No rows affected. Update might have failed or no change was made.']);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Prepared statement failed: ' . $conn->error]);
    }
} else {
    error_log('Required data not set. linesCleared: ' . isset($_POST['linesCleared']) . ', level: ' . isset($_SESSION['level']) . ', username: ' . isset($_SESSION['username']));
    echo json_encode(['status' => 'error', 'message' => 'Required data not set.']);
}

$conn->close();
?>

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

if (isset($_POST['linesCleared']) && isset($_SESSION['level']) && isset($_SESSION['username'])) {
    $levelIncrement = 0.05;
    $linesCleared = (int)$_POST['linesCleared'];
    $currentLevel = (float)$_SESSION['level'];
    $username = $_SESSION['username'];

    error_log("Lines cleared: $linesCleared");
    error_log("Current level: $currentLevel");
    error_log("Username: $username");
    // Fetch the current level from the database
    $stmt = $conn->prepare("SELECT level FROM accounts WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $currentLevel = (float)$row['level'];
    // Calculate the new level
    $newLevel = round($currentLevel + ($levelIncrement * $linesCleared), 2);

    error_log("New level: $newLevel");
}
    $updateStmt = $conn->prepare("UPDATE accounts SET level = ? WHERE username = ?");
if ($updateStmt) {
    $updateStmt->bind_param("ds", $newLevel, $username);
    $updateStmt->execute();
    
    if ($updateStmt->affected_rows > 0) {
        // Update the session variable
        $_SESSION['level'] = $newLevel;
        echo json_encode(['status' => 'success', 'newLevel' => $newLevel]);
    } else {
        error_log("No rows affected. Update might have failed or no change was made.");
        echo json_encode(['status' => 'error', 'message' => 'No rows affected. Update might have failed or no change was made.']);
    }
    
    $updateStmt->close();
} else {
    error_log('Prepared statement failed: ' . $conn->error);
    echo json_encode(['status' => 'error', 'message' => 'Prepared statement failed: ' . $conn->error]);
}
?>

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

$levelIncrement = 0.01;
$linesCleared = $_POST['lines_cleared'];
$newLevel = $_SESSION['level'] + ($levelIncrement * $linesCleared);
$userId = $_SESSION['username'];

// Update the level value in the database using prepared statements
$stmt = $conn->prepare("UPDATE accounts SET level = ? WHERE id = ?");
$stmt->bind_param("ds", $newLevel, $userId);
$stmt->execute();
$stmt->close();
?>
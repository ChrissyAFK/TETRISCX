<?php
session_start();
// Connect to the database
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

// Retrieve user information from the database
$loggedInUser = $_SESSION['username']; // replace this with how you get the logged-in user
$sql = "SELECT * FROM accounts WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInUser);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $username = $row['username'];
        $gamesPlayed = $row['games_played'];
        $level = $row['level'];
        // Add more fields as needed

        // Display user information
        echo "Username: " . $username . "<br>";
        echo "Games Played: " . $gamesPlayed . "<br>";
        echo "Level: " . $level . "<br>";
        // Add more fields as needed
        echo "<br>";
    }
} else {
    echo "No user found.";
}

$stmt->close();
// Close the database connection
$conn->close();

?>
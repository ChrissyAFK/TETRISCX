<?php
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

// Retrieve the leaderboard data
$sql = "SELECT username, level FROM accounts ORDER BY level DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output the leaderboard
    echo "<h1>Leaderboard</h1>";
    echo "<table>";
    echo "<tr><th>Username</th><th>level</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["username"] . "</td><td>" . $row["level"] . "</td></tr>";
    }
    
    echo "</table>";
} else {
    echo "No data found.";
}

$conn->close();

?>
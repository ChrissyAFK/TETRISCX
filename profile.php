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
        $username = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8');
        $gamesPlayed = htmlspecialchars($row['games_played'], ENT_QUOTES, 'UTF-8');
        $level = htmlspecialchars($row['level'], ENT_QUOTES, 'UTF-8');
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jersey+10&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Your Profile</title>
</head>
<div class="top-bar">
        <div class="button-container">
            <button onclick="window.location.href='index.php'">Home</button>
            <button onclick="window.location.href='login.php'">Login</button>
            <button onclick="window.location.href='register.php'">Register</button>
            <button onclick="window.location.href='leaderboard.php'">Leaderboard</button>
            <button onclick="window.location.href='game.php'">Play</button>
            <button onclick="window.location.href='profile.php'">Profile</button>
            <button onclick="window.location.href='logout.php'">Logout</button>
            <?php
            session_start();
            // Check if the user is already logged in
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
                if (isset($_SESSION['username'])) {
                    echo '<div class="login-status" style="background-color: green;">Logged in</div>';
                } else {
                    echo '<div class="login-status" style="background-color: red;">Not logged in</div>';
                }
            ?>
        </div>
</div>
<body>
    
</body>
</html>
<?php

// Connect to the database
$servername = "server0800";
$username = "your_username";
$password = "your_password";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user information from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

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
    echo "No users found.";
}

// Close the database connection
$conn->close();

?>
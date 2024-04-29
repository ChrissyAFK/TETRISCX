<?php
session_start();
// Assuming you have a database connection established
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

// Create a connection
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted username and password
    $username1 = $_POST['username'];
    $password1 = $_POST['password'];

    // Prepare the SQL statement
    $stmt = $connection->prepare("SELECT * FROM accounts WHERE username = ?");
    $stmt->bind_param("s", $username1);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get the hashed password from the result
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        // Verify the provided password with the hashed password
        if (password_verify($password1, $hashedPassword)) {
            // Password is correct, redirect to the home page
            $_SESSION['username'] = $username1;
            header('Location: index.php');
            exit;
        } else {
            // Password is incorrect, display an error message
            echo "Invalid username or password";
        }
    } else {
        // Username not found, display an error message
        echo "Invalid username or password";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jersey+10&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="styles.css">
<head>
    <title>Login Page</title>
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
                    echo '<div style="background-color: green;">Logged in</div>';
                } else {
                    echo '<div style="background-color: red;">Not logged in</div>';
                }
            ?>
        </div>
</div>
<body>
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
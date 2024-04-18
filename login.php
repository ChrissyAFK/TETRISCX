<?php
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
    // Query the database to check if the username and password match
    $query = "SELECT * FROM accounts WHERE username = '$username1'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        // Get the hashed password from the result
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row['password'];
    
        // Verify the provided password with the hashed password
        if (password_verify($password1, $hashedPassword)) {
            // Password is correct, redirect to the home page
            header('Location: home.php');
            exit;
        } else {
            // Password is incorrect, display an error message
            echo "Invalid username or password";
        }
    } else {
        // Username not found, display an error message
        echo "Invalid username or password";
    }
// Close the connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
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
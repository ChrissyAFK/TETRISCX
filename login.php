<?php
// Assuming you have a database connection established
$servername = "server0800";
$username = "your_username";
$password = "your_password";
$dbname = "users";

// Create a connection
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database to check if the username and password match
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($connection, $query);

    // Check if the query returned any rows
    if (mysqli_num_rows($result) > 0) {
        // Login successful, redirect to the home page
        header('Location: home.php');
        exit;
    } else {
        // Login failed, display an error message
        echo "Invalid username or password";
    }
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
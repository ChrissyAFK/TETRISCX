<?php
// register.php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Validate the form data (e.g., check for empty fields, validate email format, etc.)

    // Connect to the database
    $dbHost = 'your_database_host';
    $dbUser = 'your_database_username';
    $dbPass = 'your_database_password';
    $dbName = 'your_database_name';

    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Insert the user data into the database
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Account</title>
</head>
<body>
    <h2>Register</h2>
    <form method="POST" action="register.php">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>
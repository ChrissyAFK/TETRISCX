<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the home page or any other authenticated page
    header("Location: profile.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Validate the form data (e.g., check for empty fields, validate email format, etc.)
    // This is a placeholder - you should add your own validation here
    if (empty($username) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid form data');
    }

    // Connect to the database
    $dbHost = 'localhost';
    $dbUser = 'your_database_username';
    $dbPass = 'your_database_password';
    $dbName = 'users';

    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Error preparing statement: ' . $conn->error);
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Bind the parameters to the SQL statement
    $stmt->bind_param('sss', $username, $hashedPassword, $email);

    // Execute the SQL statement
    if ($stmt->execute() === false) {
        die('Error executing statement: ' . $stmt->error);
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();

    // Redirect the user to the login page
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <form action="register.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>
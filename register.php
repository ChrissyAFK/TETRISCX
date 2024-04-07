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

    // Connect to the database
    $dbHost = 'server0800';
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

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Bind the parameters and execute the statement
    $stmt->bind_param("sss", $username, $hashedPassword, $email);
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and the database connection
    $stmt->close();
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
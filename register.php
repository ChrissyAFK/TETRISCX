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
    $username1 = $_POST['username'];
    $password1 = $_POST['password'];
    $email = $_POST['email'];

    // Validate the form data (e.g., check for empty fields, validate email format, etc.)
    // This is a placeholder - you should add your own validation here
    if (empty($username1) || empty($password1) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid form data');
    }

    // Connect to the database
    require_once __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $servername = $_ENV['DB_SERVER'];
    $username = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];
    $dbname = $_ENV['DB_NAME'];
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check if the connection was successful
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO accounts (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Error preparing statement: ' . $conn->error);
    }

    // Hash the password
    $hashedPassword = password_hash($password1, PASSWORD_DEFAULT);

    // Bind the parameters to the SQL statement
    $stmt->bind_param("sss", $username1, $hashedPassword, $email);

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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jersey+10&display=swap" rel="stylesheet">
<link rel = "stylesheet" type = "text/css" href = "styles.css" /> 
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

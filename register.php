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

    // Validate the form data
    if (empty($username1) || empty($password1) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid form data.";
        header('Location: register.php');
        exit;
    }
    if (!preg_match("/^[a-zA-Z0-9_]*$/", $username1)) {
        $_SESSION['error'] = "Invalid username. Only alphanumeric characters and underscores are allowed.";
        header('Location: register.php');
        exit;
    }
    if (strlen($username1) > 15) {
        $_SESSION['error'] = "Username too long. It should be 15 characters or less.";
        header('Location: register.php');
        exit;
    }
    if (strlen($email) > 50) {
        $_SESSION['error'] = "Email too long. It should be 50 characters or less.";
        header('Location: register.php');
        exit;
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

    // Prepare a SQL statement to check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM accounts WHERE username = ?");
    $stmt->bind_param("s", $username1);
    $stmt->execute();

    // Get the result of the query
    $result = $stmt->get_result();

    // If the username already exists, redirect back to the registration page with an error message
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username already exists.";
        header('Location: register.php');
        exit;
    }

    // Close the statement
    $stmt->close();

    // Prepare the SQL statement
    $sql = "INSERT INTO accounts (username, password, email, level, rating, games_played, lines_cleared) VALUES (?, ?, ?, 0, 0, 0, 0)";
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

// Display any error message
if (isset($_SESSION['error'])) {
    echo '<p class="error">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);  // clear the error message
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
            // Check if the user is already logged in
            if (isset($_SESSION['username'])) {
                echo '<div class="login-status" style="background-color: green;">Logged in</div>';
            } else {
                echo '<div class="login-status" style="background-color: red;">Not logged in</div>';
            }
            ?>
        </div>
</div>
<body>
    <div class="register-form">
    <form action="register.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>
        <input type="submit" value="Register">
    </form>
    </div>
</body>
</html>

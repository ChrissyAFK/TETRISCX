// Purpose: Home page of the website, displays information about the game and allows users to navigate to other pages
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jersey+10&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>TETRISCX</title>
</head>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "url": "http://www.tetriscx.xyz/",
  "logo": "http://www.tetriscx.xyz/1200px-Tetris_logo.svg.png"
}
</script>
<body>
    <div class="top-bar">
        <h1>WELCOME TO TETRISCX</h1>
        <div class="button-container">
            <button onclick="window.location.href='login.php'">Login</button>
            <button onclick="window.location.href='register.php'">Register</button>
            <button onclick="window.location.href='leaderboard.php'">Leaderboard</button>
            <button onclick="window.location.href='game.php'">Play</button>
            <button onclick="window.location.href='profile.php'">Profile</button>
            <button onclick="window.location.href='logout.php'">Logout</button>
            <?php
session_start();
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

// Check if the user is already logged in
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
if (isset($_SESSION['username'])) {
    $userId = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT level FROM accounts WHERE id = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $level = $user['level'];
    $progress = ($level - floor($level)) * 100;

    echo '<div class="login-status" style="background-color: green;">Logged in</div>';
    echo '<div class="level-indicator">Level: ' . floor($level) . '</div>';
    echo '<div class="progress-bar"><div class="progress" style="width: ' . $progress . '%;"></div></div>';
} else {
    echo '<div class="login-status" style="background-color: red;">Not logged in</div>';
}
?>
        </div>
    </div>
    <div class="content">
        <p>Play the classic game of Tetris with a twist! Compete against other players to see who can get the highest score!</p>
        <p>Register an account to start playing and see where you rank on the leaderboard!</p>
        <p>Good luck and have fun!</p>
    </div>
    <div class="footer">
        <p>Created by: Christopher Shen</p>
        <a href = 'https://github.com/ChrissyAFK/TETRISCX'><i class="fa fa-github" style="font-size:36px;color:#1b145c;"></i></a>
    </div>
</body>
</html>
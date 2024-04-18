<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>TETRISCX</title>
</head>
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
            // Check if the user is already logged in
                if (isset($_SESSION['username'])) {
                    echo '<div style="background-color: green;">Logged in</div>';
                } else {
                    echo '<div style="background-color: red;">Not logged in</div>';
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
    </div>
</body>
</html>
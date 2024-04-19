// Purpose: Home page of the website, displays information about the game and allows users to navigate to other pages
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jersey+10&display=swap" rel="stylesheet">
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
    <div class="content">
        <p>Play the classic game of Tetris with a twist! Compete against other players to see who can get the highest score!</p>
        <p>Register an account to start playing and see where you rank on the leaderboard!</p>
        <p>Good luck and have fun!</p>
    </div>
    <div class="footer">
        <p>Created by: Christopher Shen</p>
        <a href = 'https://github.com/ChrissyAFK/TETRISCX'><i class="fa fa-github" style="font-size:36px">&#xf09b;</i></a>
    </div>
</body>
</html>
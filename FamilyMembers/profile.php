<?php
session_start(); 
include '../config.php';
if (!isset($_SESSION['personID'])) {
    header("Location: ../SessionManager/login.html");
    exit();
}

$start = date("Y/m/d");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/main.css">
    <title>Profile</title>

<script src="https://kit.fontawesome.com/1c4627bec6.js" crossorigin="anonymous"></script>
</head>

<body>


    <div class="form">
        <h2>Profile</h2>
        <p>Logged in as <?php echo $_SESSION['firstName']?> <?php echo $_SESSION['lastName']?></p>
        <button style="margin-bottom: 10px"><a href="test.php">Check Information</a></button>
        <button style="margin-bottom: 10px"> <a href="../ClubMembers/clubMembers.php">Check related members</a></button>
        <button style="margin-bottom: 10px"><a href="../Games/checkGames.php">Check Games</a></button>
        <button style="margin-bottom: 10px"><a href="../Mails/checkMails.php">Check mails</a></button>
        <button id="logout" class="logout" style="margin-bottom: 10px"><a href="../SessionManager/logout.php">Logout</a></button>
    </div>
    
    
</body>
</html>

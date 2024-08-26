<?php
session_start();

if (!isset($_SESSION['personID'])) {
    header("Location: login.html");
    exit();
}

$id = $_SESSION['personID'];
?>

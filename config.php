<?php
$servername = "inc353.encs.concordia.ca";
$username = "inc353_1";
$password = "Databas3";
$dbname = "inc353_1";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

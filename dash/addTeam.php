<?php
include '../config.php';

$teamName = $_POST['teamName'];
$headCoachID = $_POST['headCoachID'];
$locationID = $_POST['locationID'];
$teamGender = $_POST['teamGender'];

$sql = "INSERT INTO Team (teamName, headCoachID, locationID, teamGender) VALUES ('$teamName', '$headCoachID', '$locationID', '$teamGender')";

if ($conn->query($sql) === TRUE) {
    header("Location: ./teams.php?status=success");
} else {
    header("Location: ./teams.php?status=failed");
}
exit();

$conn->close();
header("Location: teams.php");
exit();
?>



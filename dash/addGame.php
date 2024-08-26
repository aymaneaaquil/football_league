<?php
include '../config.php';


$id1 = isset($_POST['teamOne']) ? intval($_POST['teamOne']) : null;
$id2 = isset($_POST['teamTwo']) ? intval($_POST['teamTwo']) : null;
$date = isset($_POST['date']) ? $_POST['date'] : null;
$startTime = isset($_POST['startTime']) ? $_POST['startTime'] : null;
$location = isset($_POST['location']) ? intval($_POST['location']) : null;
$type = isset($_POST['type']) ? $_POST['type'] : null;
$won = "tba";

if ($id1 && $id2 && $date && $startTime && $location && $type) {

    $sql = "INSERT INTO TeamFormation (teamOneID, teamTwoID, date, location, startTime, type, won) VALUES ('$id1', '$id2', '$date', '$location','$startTime' ,'$type', '$won')";

    if ($conn->query($sql) === TRUE) {

        header("Location: teamformation.php?status=success");
    } else {

        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "All fields are required.";
}

$conn->close();
?>

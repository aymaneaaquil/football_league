<?php
include '../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teamOneName = $_POST['teamOneName'];
    $teamTwoName = $_POST['teamTwoName'];
    $scoreTeamOne = $_POST['scoreTeamOne'];
    $scoreTeamTwo = $_POST['scoreTeamTwo'];
    $date = $_POST['date'];
    $location = $_POST['location'];


    if (is_numeric($scoreTeamOne) && is_numeric($scoreTeamTwo)) {

        $stmt = $conn->prepare("SELECT teamID FROM Team WHERE teamName = ?");
        $stmt->bind_param("s", $teamOneName);
        $stmt->execute();
        $result = $stmt->get_result();
        $teamOneID = $result->fetch_assoc()['teamID'];
        
        $stmt->bind_param("s", $teamTwoName);
        $stmt->execute();
        $result = $stmt->get_result();
        $teamTwoID = $result->fetch_assoc()['teamID'];
        

        $stmt = $conn->prepare("UPDATE TeamFormation SET scoreTeamOne = ?, scoreTeamTwo = ? WHERE teamOneID = ? AND teamTwoID = ? AND date = ? AND location = ?");
        $stmt->bind_param("iiiiss", $scoreTeamOne, $scoreTeamTwo, $teamOneID, $teamTwoID, $date, $location);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Scores updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update scores.";
        }
    } else {
        $_SESSION['error'] = "Invalid scores provided.";
    }
}

header("Location: teamformation.php");
exit;
?>

<?php
include '../config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scoreTeamOne = isset($_POST['scoreTeamOne']) ? (int)$_POST['scoreTeamOne'] : 0;
    $scoreTeamTwo = isset($_POST['scoreTeamTwo']) ? (int)$_POST['scoreTeamTwo'] : 0;
    $date = isset($_POST['date']) ? $_POST['date'] : '';

    $id = isset($_POST['teamFormationID']) ? $_POST['teamFormationID'] : '';



    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Failed to update score']);
        exit;
    }


    $sqlUpdate = "UPDATE TeamFormation
                  SET scoreTeamOne = ?, scoreTeamTwo = ?, won = ?
                  WHERE teamFormationID = ?";

    //won
    $type = isset($_POST['type']) ? $_POST['type'] : 'Game'; 
    $won = 'practice'; 

    if ($type === 'Game') {
        if ($scoreTeamOne > $scoreTeamTwo) {
            $won = 'teamOne';
        } elseif ($scoreTeamTwo > $scoreTeamOne) {
            $won = 'teamTwo';
        } else {
            $won = 'tie';
        }
    }


    $stmt = $conn->prepare($sqlUpdate);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
        exit;
    }


    $stmt->bind_param('iisi', $scoreTeamOne, $scoreTeamTwo, $won, $id);
    $result = $stmt->execute();

    // Success ?
    if ($result) {
        header("Location: ./teamformation.php?status=success");
    } else {
        header("Location: ./teamformation.php?status=failed");
        
    }

    // Close the statement
    $stmt->close();
}
?>

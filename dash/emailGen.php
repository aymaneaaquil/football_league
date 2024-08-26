<?php

include('../config.php');


$currentDate = date('Y-m-d');
$nextTestDate = date('Y-m-d', strtotime('+7 days'));


$query = "SELECT tf.teamFormationID, tf.date, tf.location, tf.startTime, tf.type,
                 t1.teamName AS teamOneName, t2.teamName AS teamTwoName,
                 p1.firstName AS TeamOneCoachFirstName, p1.lastName AS TeamOneCoachLastName, pe1.email AS TeamOneEmail,
                 p2.firstName AS TeamTwoCoachFirstName, p2.lastName AS TeamTwoCoachLastName, pe2.email AS TeamTwoEmail,
                 l.locName AS locationName
          FROM TeamFormation tf
          JOIN Team t1 ON tf.teamOneID = t1.teamID
          JOIN Team t2 ON tf.teamTwoID = t2.teamID
          JOIN Person p1 ON t1.headCoachID = p1.personID
          JOIN Person p2 ON t2.headCoachID = p2.personID
          JOIN Location l ON tf.location = l.locationID
          JOIN Personnel pe1 ON t1.headCoachID = pe1.personnelID
          JOIN Personnel pe2 ON t2.headCoachID = pe2.personnelID
          WHERE tf.date BETWEEN '$currentDate' AND '$nextTestDate'";

$result = mysqli_query($conn, $query);


if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$sessions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $teamFormationID = $row['teamFormationID'];


    $memberQuery = "SELECT Relative.firstName AS RelativeName, player.firstName AS PlayerName, f.email, pl.role, t.teamName, Relative.PersonID AS receiverID
                    FROM Play pl
                    JOIN TeamFormation tf ON pl.teamFormationID = tf.teamFormationID
                    JOIN Team t ON pl.teamID = t.teamID
                    JOIN Person player ON pl.teamMemberID = player.personID
                    JOIN Related r ON pl.teamMemberID = r.clubMemberID
                    JOIN Person Relative ON Relative.personID = r.familyMemberID
                    JOIN FamilyMember f ON f.familyMemberID = r.familyMemberID
                    WHERE pl.teamFormationID = $teamFormationID";

    $memberResult = mysqli_query($conn, $memberQuery);


    if (!$memberResult) {
        die("Member query failed: " . mysqli_error($conn));
    }


    $sessions[] = [
        'session' => $row,
        'members' => mysqli_fetch_all($memberResult, MYSQLI_ASSOC)
    ];
}

foreach ($sessions as $sessionData) {
    $session = $sessionData['session'];
    $members = $sessionData['members'];

    foreach ($members as $member) {
        $teamName = ($session['type'] === 'training') ? $session['teamOneName'] : $session['teamOneName'] . " vs " . $session['teamTwoName'];
        $subject = "$teamName on {$session['date']} at {$session['startTime']} - {$session['type']} session";
        
        $body = "Dear {$member['RelativeName']} (Relative of {$member['PlayerName']}),\n\n";
        $body .= "You have a {$session['type']} session scheduled on {$session['date']} at {$session['startTime']}.\n";
        $body .= "Location: {$session['locationName']}\n";
        $body .= "Role: {$member['role']}\n";
        $body .= "Head Coach: " . (($session['type'] === 'training') ? "{$session['TeamOneCoachFirstName']} {$session['TeamOneCoachLastName']} (Email: {$session['TeamOneEmail']})" : "{$session['TeamOneCoachFirstName']} {$session['TeamOneCoachLastName']} (Email: {$session['TeamOneEmail']}) and {$session['TeamTwoCoachFirstName']} {$session['TeamTwoCoachLastName']} (Email: {$session['TeamTwoEmail']})") . "\n\n";
        $body .= "Best regards,\nYour Team";

        //uncomment to send 
        mail($member['email'], $subject, $body);

        //inserting mail in
        $bodyPreview = substr($body, 0, 100);
        $logQuery = "INSERT INTO Emails (dateSent, locationSentFrom, receiver, subjectOfEmail, body)
                     VALUES (NOW(), '{$session['location']}', '{$member['receiverID']}', '$subject', '$bodyPreview')";
        
        if (!mysqli_query($conn, $logQuery)) {
            //if error
            echo "Failed to log email for {$member['email']}: " . mysqli_error($conn) . "\n";
        } else {
            echo "Logged email for {$member['email']} successfully.\n";
        }
    }
}
?>

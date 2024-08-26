<?php
include '../config.php'; 

session_start();
if (!isset($_SESSION['personID'])) {
    header("Location: login.html");
    exit();
}

//Future games
$sqlUpcoming = "SELECT tf.date, tf.location, tf.startTime, 
                       t1.teamName AS teamOneName, 
                       t2.teamName AS teamTwoName
                FROM TeamFormation tf
                JOIN Team t1 ON tf.teamOneID = t1.teamID
                JOIN Team t2 ON tf.teamTwoID = t2.teamID
                WHERE tf.scoreTeamOne IS NULL 
                  AND tf.scoreTeamTwo IS NULL
                ORDER BY tf.date, tf.startTime";

$resultUpcoming = $conn->query($sqlUpcoming);

$upcomingGames = [];
if ($resultUpcoming) {
    while ($row = $resultUpcoming->fetch_assoc()) {
        $upcomingGames[] = $row;
    }
}

//Past games
$sqlPast = "SELECT tf.date, tf.location, tf.startTime,
            t1.teamName AS teamOneName,
            t2.teamName AS teamTwoName,
            tf.scoreTeamOne,
            tf.scoreTeamTwo
            FROM TeamFormation tf
            JOIN Team t1 ON tf.teamOneID = t1.teamID
            JOIN Team t2 ON tf.teamTwoID = t2.teamID
            WHERE tf.scoreTeamOne IS NOT NULL
            ORDER BY tf.date DESC, tf.startTime DESC;";

$resultPast = $conn->query($sqlPast);

$pastGames = [];
if ($resultPast) {
    while ($row = $resultPast->fetch_assoc()) {
        $pastGames[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/main.css">
    <title>Games</title>
    <style>
        .card-container {
            padding: 20px;
            width: 100%:
        }
        .game-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 20px; 

            width: 100%; 
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .team {
            flex: 1;
            display: flex;
            align-items: center;
        }
        .team-name-score {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .team-one .team-name-score {
            margin-left: 25px;
            
        }
        .team-name {
            font-size: 16px;
            font-weight: bold;
            margin: 0 10px; 
        }
        .score {
            font-size: 14px;
            color: #555;
        }
        .date-time {
            flex: 2;
            text-align: center;
        }
        .section-heading {
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form" style=" max-width: 600px; ">
        <p class="section-heading">Upcoming Games</p>
        <div class="card-container">
            <?php if (empty($upcomingGames)): ?>
                <div>No upcoming games</div>
            <?php else: ?>
                <?php foreach ($upcomingGames as $game): ?>
                    <div class="game-card">
                        <div class="team team-one">
                            <div class="team-name-score">
                                <div class="team-name"><?php echo htmlspecialchars($game['teamOneName']); ?></div>
                                <div class="score">TBD</div>
                            </div>
                        </div>
                        <div class="date-time">
                            <div><?php echo htmlspecialchars($game['date']); ?></div>
                            <div><?php echo htmlspecialchars($game['startTime']); ?></div>
                        </div>
                        <div class="team team-two">
                            <div class="team-name-score">
                                <div class="score">TBD</div>
                                <div class="team-name"><?php echo htmlspecialchars($game['teamTwoName']); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <p class="section-heading">Past Games</p>
        <div class="card-container">
            <?php if (empty($pastGames)): ?>
                <div>No past games found</div>
            <?php else: ?>
                <?php foreach ($pastGames as $game): ?>
                    <div class="game-card">
                        <div class="team team-one">
                            <div class="team-name-score">
                                <div class="team-name"><?php echo htmlspecialchars($game['teamOneName']); ?></div>
                                <div class="score"><?php echo isset($game['scoreTeamOne']) ? htmlspecialchars($game['scoreTeamOne']) : 'TBA'; ?></div>
                            </div>
                        </div>
                        <div class="date-time">
                            <div><?php echo htmlspecialchars($game['date']); ?></div>
                            <div><?php echo htmlspecialchars($game['startTime']); ?></div>
                        </div>
                        <div class="team team-two">
                            <div class="team-name-score">
                                <div class="score"><?php echo isset($game['scoreTeamTwo']) ? htmlspecialchars($game['scoreTeamTwo']) : 'TBA'; ?></div>
                                <div class="team-name"><?php echo htmlspecialchars($game['teamTwoName']); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <button style="margin-bottom: 10px"><a href="../FamilyMembers/profile.php">Back to profile</a></button>
    </div>
</body>
</html>

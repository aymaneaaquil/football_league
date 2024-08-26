<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./pitch.css">
    <title>Home</title>
    <script>
        function switchLeaderboard(gender) {
            window.location.href = `?gender=${gender}`;
        }
    </script>
</head>
<body>
    <div class="form" style="width: 1000px;">
    <button><a href="/SessionManager/login.html">Login</a></button>
        <table>
            <caption>
                <?php
                $gender = isset($_GET['gender']) ? $_GET['gender'] : 'M';
                echo $gender === 'M' ? "Boys Standing" : "Girls Standing";
                ?>
            </caption>
            <thead>
                <tr>
                    <th scope="col">Standing</th>
                    <th scope="col">Team Name</th>
                    <th scope="col">Game Played</th>
                    <th scope="col">Game Won</th>
                    <th scope="col">Game Lost</th>
                    <th scope="col">Game Tied</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'config.php';
                $gender = isset($_GET['gender']) ? $_GET['gender'] : 'M';

                $sql = "SELECT t.teamName, 
                               COUNT(tf.teamFormationID) AS gamesPlayed, 
                               SUM(CASE WHEN tf.won = 'teamOne' AND tf.teamOneID = t.teamID THEN 1 
                                        WHEN tf.won = 'teamTwo' AND tf.teamTwoID = t.teamID THEN 1 
                                        ELSE 0 
                                   END) AS gamesWon,
                               SUM(CASE WHEN tf.won = 'teamOne' AND tf.teamTwoID = t.teamID THEN 1 
                                        WHEN tf.won = 'teamTwo' AND tf.teamOneID = t.teamID THEN 1 
                                        ELSE 0 
                                   END) AS gamesLost,
                               SUM(CASE WHEN tf.won = 'tie' THEN 1 ELSE 0 END) AS gamesTied
                        FROM Team t
                        LEFT JOIN TeamFormation tf ON t.teamID = tf.teamOneID OR t.teamID = tf.teamTwoID
                        WHERE t.teamGender = '$gender'
                        GROUP BY t.teamID
                        ORDER BY gamesWon DESC, gamesLost ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $standing = 1;
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $standing++ . ".</td>";
                        echo "<th scope='row'>" . $row['teamName'] . "</th>";
                        echo "<td>" . $row['gamesPlayed'] . "</td>";
                        echo "<td>" . $row['gamesWon'] . "</td>";
                        echo "<td>" . $row['gamesLost'] . "</td>";
                        echo "<td>" . $row['gamesTied'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No data available</td></tr>";
                }
                
                $conn->close();
                ?>
            </tbody>
        </table>
        <button style="margin-bottom: 10px; margin-top: 10px; background-color: #7CB9E8;
" onclick="switchLeaderboard('M')">Boys Leaderboard</button>
        <button style="margin-bottom: 10px; background-color: palevioletred;
" onclick="switchLeaderboard('F')">Girls Leaderboard</button>
    
    </div>
</body>
</html>

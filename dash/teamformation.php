<?php
include '../config.php';
session_start();

// Future games
$sqlUpcoming = "SELECT tf.date, tf.location, tf.startTime,
                t1.teamName AS teamOneName,
                t2.teamName AS teamTwoName,
                tf.scoreTeamOne,
                tf.scoreTeamTwo,
                tf.type,
                tf.teamFormationID
                FROM TeamFormation tf
                JOIN Team t1 ON tf.teamOneID = t1.teamID
                JOIN Team t2 ON tf.teamTwoID = t2.teamID
                WHERE tf.date > curdate()
                ORDER BY tf.date, tf.startTime";

$resultUpcoming = $conn->query($sqlUpcoming);

$upcomingGames = [];
if ($resultUpcoming) {
    while ($row = $resultUpcoming->fetch_assoc()) {
        $upcomingGames[] = $row;
    }
}

// Past games
$sqlPast = "SELECT tf.date, tf.location, tf.startTime,
            t1.teamName AS teamOneName,
            t2.teamName AS teamTwoName,
            tf.scoreTeamOne,
            tf.scoreTeamTwo,
            tf.type,
            tf.teamFormationID
            FROM TeamFormation tf
            JOIN Team t1 ON tf.teamOneID = t1.teamID
            JOIN Team t2 ON tf.teamTwoID = t2.teamID
            WHERE tf.date < curdate()
            ORDER BY tf.date, tf.startTime";

$resultPast = $conn->query($sqlPast);

$pastGames = [];
if ($resultPast) {
    while ($row = $resultPast->fetch_assoc()) {
        $pastGames[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Games</title>
    <link rel="stylesheet" href="/css/main.css">
    <style>
        .card-container {
            padding: 20px;
            width: 100%;
        }
        .game-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .game-card:hover, .game-card.selected {
            background: #eee;
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
        .btn-container {
            display: none;
            margin-top: 20px;
        }
        .btn-container button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-container button:hover {
            background-color: #0056b3;
        }
        .toggle-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .toggle-buttons button {
            background-color: #ddd;
            color: black;
            border: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .toggle-buttons button:hover {
            background-color: #ccc;
        }
        .active {
            background-color: #007bff;
            color: white;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .modal input {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .teams-section {
    display: flex;
    justify-content: space-between;
    width: 100%;
}

.team-section {
    width: 45%;
}

.position-section {
    margin-bottom: 15px;
}
    </style>
</head>
<body>
    <div class="form" style="max-width: 600px;">
        <div class="toggle-buttons">
            <button id="upcomingBtn" class="active">Upcoming</button>
            <button id="pastBtn">Past</button>
        </div>
        <div id="upcomingSection">
            <p class="section-heading">Upcoming Games</p>
            <div class="card-container">
                <?php if (empty($upcomingGames)): ?>
                    <div>No upcoming games</div>
                <?php else: ?>
                    <?php foreach ($upcomingGames as $game): ?>
                        <div class="game-card" data-game-id="<?php echo htmlspecialchars($game['teamFormationID']); ?>" data-type="upcoming">
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
        </div>
        <div id="pastSection" style="display: none;">
            <p class="section-heading">Past Games</p>
            <div class="card-container">
                <?php if (empty($pastGames)): ?>
                    <div>No past games found</div>
                <?php else: ?>
                    <?php foreach ($pastGames as $game): ?>
                        <div class="game-card" data-game-id="<?php echo htmlspecialchars($game['teamFormationID']); ?>" data-type="past">
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
        </div>
        <div id="btnContainer" class="btn-container">
            <button id="editButton">Edit</button>
        </div>
        <div>
        <button id="addGame"style="margin-top: 30px" >Add game</button>
        <button style="margin-top: 10px"><a href="./dashboard.php">Back</a></button>

            
        </div>
    </div>

<!-- add game Modal -->
<div id="addGameModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeAddGameModal">&times;</span>
        <h2>Add Game</h2>
        <form id="addGameForm" action="addGame.php" method="post">
            <label for="date">Team One ID:</label>
            <input type="text" name="teamOne" required>
            <label for="date">Team Two ID:</label>
            <input type="text" name="teamTwo" required>
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            <label for="startTime">Start Time:</label>
            <input type="text" id="startTime" name="startTime" required>
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>
            <label for="type">Type:</label>
            <select name="type" id="">
                <option value="Training">Training</option>
                <option value="Game">Game</option>
            </select>
            <input type="submit" value="Add Game">
        </form>
    </div>
</div>

    <!-- Edit Score Modal -->
<div id="editScoreModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditScoreModal">&times;</span>
        <h2>Edit Score - ID: <span id="editScoreID"></span></h2>
        <form id="editScoreForm" action="editscore.php" method="post">
            <input type="hidden" name="teamFormationID" id="editScoreTeamFormationID">
            <label for="scoreTeamOne">Score Team One:</label>
            <input type="number" id="scoreTeamOne" name="scoreTeamOne" required>
            <label for="scoreTeamTwo">Score Team Two:</label>
            <input type="number" id="scoreTeamTwo" name="scoreTeamTwo" required>
            <input type="submit" value="Save">
        </form>
    </div>
</div>


    <script>
       document.getElementById('upcomingBtn').addEventListener('click', function() {
    document.getElementById('upcomingSection').style.display = 'block';
    document.getElementById('pastSection').style.display = 'none';
    document.getElementById('btnContainer').style.display = 'none';
    document.getElementById('upcomingBtn').classList.add('active');
    document.getElementById('pastBtn').classList.remove('active');
    document.getElementById('editButton').textContent = 'Edit Game';
});

document.getElementById('pastBtn').addEventListener('click', function() {
    document.getElementById('upcomingSection').style.display = 'none';
    document.getElementById('pastSection').style.display = 'block';
    document.getElementById('btnContainer').style.display = 'none';
    document.getElementById('pastBtn').classList.add('active');
    document.getElementById('upcomingBtn').classList.remove('active');
    document.getElementById('editButton').textContent = 'Edit Score';
});

const cards = document.querySelectorAll('.game-card');
cards.forEach(card => {
    card.addEventListener('click', () => {
        cards.forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        document.getElementById('btnContainer').style.display = 'block';
    });
});

document.getElementById('editButton').addEventListener('click', function() {
    const selectedCard = document.querySelector('.game-card.selected');
    if (selectedCard) {
        const gameId = selectedCard.getAttribute('data-game-id');
        if (document.getElementById('upcomingSection').style.display === 'block') {
            document.getElementById('editGameID').textContent = gameId;
            document.getElementById('editGameTeamFormationID').value = gameId;
            document.getElementById('editGameModal').style.display = 'block';
        } else {
            document.getElementById('editScoreID').textContent = gameId;
            document.getElementById('editScoreTeamFormationID').value = gameId;
            document.getElementById('editScoreModal').style.display = 'block';
        }
    } else {
        alert('Please select a game to edit.');
    }
});

document.getElementById('closeEditScoreModal').addEventListener('click', function() {
    document.getElementById('editScoreModal').style.display = 'none';
});

document.getElementById('closeEditGameModal').addEventListener('click', function() {
    document.getElementById('editGameModal').style.display = 'none';
});

window.onclick = function(event) {
    if (event.target == document.getElementById('editScoreModal')) {
        document.getElementById('editScoreModal').style.display = 'none';
    }
    if (event.target == document.getElementById('editGameModal')) {
        document.getElementById('editGameModal').style.display = 'none';
    }
}

document.getElementById('addGame').addEventListener('click', function() {
    document.getElementById('addGameModal').style.display = 'block';
});

document.getElementById('closeAddGameModal').addEventListener('click', function() {
    document.getElementById('addGameModal').style.display = 'none';
});

window.onclick = function(event) {
    if (event.target == document.getElementById('addGameModal')) {
        document.getElementById('addGameModal').style.display = 'none';
    }
}

// window.onload = function() {
//     const urlParams = new URLSearchParams(window.location.search);
//     const status = urlParams.get('status');
//     if (status === 'success') {
//         alert('Score updated');
//     }
//     if (status === 'failed') {
//         alert('Failed to update the score.');
//     }
// }

    </script>
</body>
</html>

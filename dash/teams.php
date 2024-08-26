<?php
include '../config.php';
session_start();

//Team fetch
$sql = "SELECT * FROM Team";
$result = $conn->query($sql);
$teams = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teams[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #d3f8d3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
        }
        h1 {
            text-align: center;
        }
        .tab-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .tab-buttons button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 0 5px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        .tab-buttons button:hover {
            background-color: #45a049;
        }
        .tab {
            display: none;
        }
        .tab.active {
            display: block;
        }
        .team-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .team-buttons button {
            display: block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            max-width: 200px;
        }
        .team-buttons button:hover {
            background-color: #45a049;
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
            max-width: 500px;
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
        .back-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        a {
            text-decoration: none;
            color: white;
        }

        a:visited {
            text-decoration: none;
            color: white;
        }

        .back-button button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        .back-button button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Teams</h1>
        <div class="tab-buttons">
            <button onclick="openModal('addTeamModal')">Add Team</button>
            <button onclick="showTab('girls')">Girls</button>
            <button onclick="showTab('boys')">Boys</button>
        </div>

        <div id="girls" class="tab">
            <h2 style="text-align: center">Girls Teams</h2>
            <div class="team-buttons">
                <?php foreach ($teams as $team): ?>
                    <?php if ($team['teamGender'] == 'F'): ?>
                        <button class="teamBtn" data-team='<?php echo json_encode($team); ?>' onclick='openEditModal(<?php echo json_encode($team); ?>)'>
                            <?php echo $team['teamName']; ?>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="boys" class="tab">
            <h2 style="text-align: center">Boys Teams</h2>
            <div class="team-buttons">
                <?php foreach ($teams as $team): ?>
                    <?php if ($team['teamGender'] == 'M'): ?>
                        <button class="teamBtn" data-team='<?php echo json_encode($team); ?>' onclick='openEditModal(<?php echo json_encode($team); ?>)'>
                            <?php echo $team['teamName']; ?>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="back-button">
        <button style="margin-top: 10px"><a href="./dashboard.php">Back</a></button>
                    
        </div>
    </div>

    <!-- Add Team Modal -->
    <div id="addTeamModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addTeamModal')">&times;</span>
            <form id="addTeamForm" action="addTeam.php" method="post">
                <h2>Add Team</h2>
                <label for="teamName">Team Name:</label>
                <input type="text" name="teamName" required><br>
                <label for="headCoachID">Head Coach ID:</label>
                <input type="number" name="headCoachID" required><br>
                <label for="locationID">Location ID:</label>
                <input type="number" name="locationID" required><br>
                <label for="teamGender">Gender:</label>
                <select name="teamGender" required>
                    <option value="M">Boys</option>
                    <option value="F">Girls</option>
                </select><br>
                <button type="submit">Add Team</button>
                <button type="button" onclick="closeModal('addTeamModal')">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Edit Team Modal -->
    <div id="editTeamModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editTeamModal')">&times;</span>
            <form id="editTeamForm" action="editTeam.php" method="post">
                <h2>Edit Team</h2>
                <input type="hidden" name="teamID" id="editTeamID">
                <label for="editTeamName">Team Name:</label>
                <input type="text" name="teamName" id="editTeamName" required><br>
                <label for="editHeadCoachID">Head Coach ID:</label>
                <input type="number" name="headCoachID" id="editHeadCoachID" required><br>
                <label for="editLocationID">Location ID:</label>
                <input type="number" name="locationID" id="editLocationID" required><br>
                <label for="editTeamGender">Gender:</label>
                <select name="teamGender" id="editTeamGender" required>
                    <option value="M">Boys</option>
                    <option value="F">Girls</option>
                </select><br>
                <button type="submit">Update Team</button>
                <button type="button" onclick="closeModal('editTeamModal')">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function showTab(gender) {
            document.getElementById('girls').classList.remove('active');
            document.getElementById('boys').classList.remove('active');
            document.getElementById(gender).classList.add('active');
        }

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function openEditModal(team) {
            document.getElementById('editTeamID').value = team.teamID;
            document.getElementById('editTeamName').value = team.teamName;
            document.getElementById('editHeadCoachID').value = team.headCoachID;
            document.getElementById('editLocationID').value = team.locationID;
            document.getElementById('editTeamGender').value = team.teamGender;
            openModal('editTeamModal');
        }


        showTab('boys');

        //close outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal(event.target.id);
            }
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            if (status === 'failed') {
                alert('Could not create this team.');
            }
        }
    </script>
</body>
</html>

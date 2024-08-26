<?php

include '../config.php';


$query = "SELECT p.personID, p.firstName, p.lastName, pr.mandate, pr.email, pr.phone, l.locName
          FROM Person p
          JOIN Personnel pr ON p.personID = pr.personnelID
          LEFT JOIN WorksAt w ON p.personID = w.personnelID AND w.endDate IS NULL
          LEFT JOIN Location l ON w.locationID = l.locationID";
$result = mysqli_query($conn, $query);

$personnel = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $personnel[] = $row;
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personnel List</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/modal.css">

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 70%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: slide-down 0.4s ease-out;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        @keyframes slide-down {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        #personDetails, #addPersonForm {
            text-align: center;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    
    <div class="form">
        <p>Registered Personnel</p>
        <ul class="personnel-list" style="list-style-type:none; padding: 0">
            <?php if (empty($personnel)): ?>
                <li>No personnel found.</li>
            <?php else: ?>
                <?php foreach ($personnel as $person): ?>
                    <li>
                        <button style="margin-bottom: 10px" onclick="showModal('<?php echo htmlspecialchars($person['personID']); ?>', '<?php echo htmlspecialchars($person['firstName']); ?>', '<?php echo htmlspecialchars($person['lastName']); ?>', '<?php echo htmlspecialchars($person['mandate']); ?>', '<?php echo htmlspecialchars($person['email']); ?>', '<?php echo htmlspecialchars($person['phone']); ?>', '<?php echo htmlspecialchars($person['locName']); ?>')" class="personnel-button">
                            <?php echo htmlspecialchars($person['firstName']) . ' ' . htmlspecialchars($person['lastName']); ?>
                        </button>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <button style="margin-top: 30px" onclick="showAddPersonModal()">Add personnel</button>
        <button style="margin-top: 10px"><a href="./dashboard.php">Back</a></button>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="personDetails"></p>
            <button onclick="showWorkHistory()">Show Work History</button>
            <div id="workHistory" style="display:none;"></div>
        </div>
    </div>

    <div id="addPersonModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddPersonModal()">&times;</span>
            <form id="addPersonForm" action="addPerson.php" method="post">
                <h2>Add New Personnel</h2>
                <input type="text" name="firstName" placeholder="First Name" required>
                <input type="text" name="lastName" placeholder="Last Name" required>
                <input type="date" name="dateOfBirth" placeholder="Date of Birth" required>
                <input type="text" name="SSN" placeholder="SSN" required>
                <input type="text" name="medicareNb" placeholder="Medicare Number" required>
                <select name="gender" required>
                    <option value="" disabled selected>Gender</option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                </select>
                <input type="text" name="province" placeholder="Province" required>
                <input type="text" name="city" placeholder="City" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="postalCode" placeholder="Postal Code" required>
                <input type="text" name="mandate" placeholder="Mandate" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <input type="text" name="locationID" placeholder="Location ID" required>
                <select name="role" required>
                    <option value="" disabled selected>Role</option>
                    <option value="Administrator">Administrator</option>
                    <option value="Trainer">Trainer</option>
                    <option value="Other">Other</option>
                </select>
                <input type="submit" value="Add Personnel">
            </form>
        </div>
    </div>

    <script>
        let currentPersonnelID;

        function showModal(personID, firstName, lastName, mandate, email, phone, locName) {
            currentPersonnelID = personID;
            var modal = document.getElementById("myModal");
            var personDetails = "Name: " + firstName + " " + lastName + "<br>" +
                                "Mandate: " + mandate + "<br>" +
                                "Email: " + email + "<br>" +
                                "Phone: " + phone + "<br>" +
                                "Current Location: " + locName;
            document.getElementById("personDetails").innerHTML = personDetails;
            modal.style.display = "block";
        }

        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
            document.getElementById("workHistory").style.display = "none";
        }

        function showAddPersonModal() {
            var modal = document.getElementById("addPersonModal");
            modal.style.display = "block";
        }

        function closeAddPersonModal() {
            var modal = document.getElementById("addPersonModal");
            modal.style.display = "none";
        }

        function showWorkHistory() {
            fetchWorkHistory(currentPersonnelID);
        }

        function fetchWorkHistory(personID) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "fetchWorkHistory.php?personnelID=" + personID, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var workHistoryDiv = document.getElementById("workHistory");
                    workHistoryDiv.innerHTML = xhr.responseText;
                    workHistoryDiv.style.display = "block";
                }
            };
            xhr.send();
        }

        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>


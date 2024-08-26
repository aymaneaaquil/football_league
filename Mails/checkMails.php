<?php
include '../config.php';
session_start();

$personID = $_SESSION['personID'];

$query = "SELECT dateSent, locationSentFrom, receiver, subjectOfEmail, body FROM Emails WHERE receiver = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $personID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/modal.css">
    
    <title>Emails</title>
    <style>
        .btntest {
        background-color: red
            
        }
    </style>
</head>

<body>
    <div class="form">
        Inbox
        
            <div>
            <ul style="list-style-type: none;" >
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div onclick="openModal('<?php echo htmlspecialchars($row['dateSent']); ?>', 
                        '<?php echo htmlspecialchars($row['locationSentFrom']); ?>',
                        '<?php echo htmlspecialchars($row['receiver']); ?>', '<?php echo htmlspecialchars($row['subjectOfEmail']); ?>', 
                        '<?php echo htmlspecialchars($row['body']); ?>')" class="emailBox">
                            <li><?php echo htmlspecialchars($row['subjectOfEmail']); ?></li>
                    </div>
                <?php endwhile; ?>
            </ul>
            </div>
        <button style="margin-bottom: 10px"><a href="../FamilyMembers/profile.php">Back to profile</a></button>
        
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <!-- content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="emailSubject"></h2>
            <p><strong>Date Sent:</strong> <span id="dateSent"></span></p>
            <p><strong>Location Sent From:</strong> <span id="locationSentFrom"></span></p>
            <p><strong>Receiver:</strong> <span id="receiver"></span></p>
            <p><strong>Body:</strong></p>
            <p id="emailBody"></p>
        </div>
    </div>

    <script>
        function openModal(dateSent, locationSentFrom, receiver, subject, body) {
            document.getElementById('dateSent').textContent = dateSent;
            document.getElementById('locationSentFrom').textContent = locationSentFrom;
            document.getElementById('receiver').textContent = receiver;
            document.getElementById('emailSubject').textContent = subject;
            document.getElementById('emailBody').textContent = body;

            var modal = document.getElementById('myModal');
            modal.style.display = "block";
        }


        var modal = document.getElementById('myModal');


        var span = document.getElementsByClassName('close')[0];


        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>

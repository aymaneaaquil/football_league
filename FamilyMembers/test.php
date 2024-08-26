<?php
session_start(); 
include '../config.php';
if (!isset($_SESSION['personID'])) {
    header("Location: ../SessionManager/login.html");
    exit();
}

$id = $_SESSION['personID'];
$sql = "SELECT FM.*, P.*, EPC.firstName AS ecFirstName, EPC.lastName AS ecLastName
FROM FamilyMember FM
JOIN Person P ON FM.familyMemberID = P.personID
LEFT JOIN FamilyMember EC ON FM.secondaryID = EC.familyMemberID
LEFT JOIN Person EPC ON EC.familyMemberID = EPC.personID
WHERE FM.familyMemberID = '$id'";

$result = $conn->query($sql);


if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/main.css">
    <title>Manage my info</title>
<script src="https://kit.fontawesome.com/1c4627bec6.js" crossorigin="anonymous"></script>

</head>
<body>
    <div class="form">
        <h2>Profile Information</h2>
        <p><strong>First Name:</strong> <?php echo ($user['firstName']); ?></p>
        <p><strong>Last Name:</strong> <?php echo ($user['lastName']); ?></p>
        <p><strong>Email:</strong> <?php echo ($user['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo ($user['phone']); ?></p>
        <p><strong>Social Security Number:</strong> <?php echo ($user['SSN']); ?></p>
        <p><strong>Medicare Number:</strong> <?php echo ($user['medicareNb']); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo ($user['dateOfBirth']); ?></p>
        <p><strong>City:</strong> <?php echo ($user['city']); ?></p>
        <p><strong>Address:</strong> <?php echo ($user['address']); ?></p>
        <p><strong>Postal Code:</strong> <?php echo ($user['postalCode']); ?></p>
        <p><strong>Province:</strong> <?php echo ($user['province']); ?></p>
        <p><strong>Emergency contact:</strong> 
        <?php 
            if (!empty($user['ecFirstName']) && !empty($user['ecLastName'])) {
                echo $user['ecFirstName'] . ' ' . $user['ecLastName'];
            } else {
                echo 'None';
            }
            ?>
    </p>
        <button style="margin-bottom: 10px"><a href="/FamilyMembers/edit/editmyinfo.php">Edit information</a></button>
        <button style="margin-bottom: 10px"><a href="profile.php">Back to profile</a></button>
    </div>
</body>
</html>

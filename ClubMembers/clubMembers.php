<?php

include '../config.php'; 

session_start();
if (!isset($_SESSION['personID'])) {
    header("Location: ../SessionManager/login.html");
    exit();
}

$id = $_SESSION['personID'];


$sql = "SELECT p.personID, p.firstName, p.lastName, c.clubMemberID
FROM ClubMember c
JOIN Related r ON c.clubMemberID = r.clubMemberID
JOIN Person p ON p.personID = r.clubMemberID
WHERE r.familyMemberID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

$children = [];
while ($row = $result->fetch_assoc()) {
    $children[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/main.css">
    <title>Club Members</title>
</head>
<body>
    <div class="form">
        <p>Registered Club Members</p>    
        <ul class="children-list" style="list-style-type:none; padding: 0">
        <?php if (empty($children)): ?>
            <li>No children found.</li>
        <?php else: ?>
            <?php foreach ($children as $child): ?>
                <li>
                    <a href="childInfo.php?clubMemberID=<?php echo urlencode($child['clubMemberID']); ?>" style="text-decoration: none;">
                        <button style="background-color: gray" class="member-button">
                            <?php echo htmlspecialchars($child['firstName']) . ' ' . htmlspecialchars($child['lastName']); ?>
                        </button>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
        <button style="margin-bottom: 10px"><a href="./add/addClubMember.php">Add child</a></button>
        <button style="margin-bottom: 10px"><a href="../FamilyMembers/profile.php">Back to profile</a></button>
    </div>
</body>
</html>

<?php

include '../config.php'; 

$sql = "SELECT capacity, locName, locationID FROM Location";
$result = $conn->query($sql);

$options = "";

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        $locationID = (int)$row['locationID'];
        $locName = $row['locName'];
        $capacity = $row['capacity'];
        //$options .= "<option value='{$locationID}'> {$locName}  (Capacity: {$capacity})</option>";
        //$selected = ($locationID == $childInfo['locationID']) ? "selected" : "false";
        $options .= "<option value='{$locationID}' selected> {$locName} (Capacity: {$capacity})</option>";
    }
} else {
    $options = "<option value=''>No locations available</option>";      
}

if (isset($_GET['clubMemberID'])) {

    $clubMemberID = $_GET['clubMemberID'];
}


if ($clubMemberID) {
    $sql = "SELECT p.firstName, p.lastName, p.city, p.address, p.postalCode, p.province, p.personID, po.locationID, l.locName
            FROM Person p
            JOIN ClubMember cm ON p.personID = cm.clubMemberID
            JOIN PartOf po ON p.personID = po.clubMemberID
            JOIN Location l ON po.locationID = l.locationID
            WHERE cm.clubMemberID = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("s", $clubMemberID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $childInfo = $result->fetch_assoc();
    } else {
        die('Query failed: ' . $conn->error);
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No clubMemberID provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/modal.css">
    <title>Child Information</title>
</head>
<body>
    <div class="form">
        <p><?php var_dump($locationID) ?></p>
        <p> <?php  var_dump($childInfo['locationID'])?> </p>
        <p> <?php echo "locationID: " . $locationID . "<br>";
echo "childInfo['locationID']: " . $childInfo['locationID'] . "<br>";
echo ($locationID == $childInfo['locationID']) ? "They are equal" : "They are not equal";
?> </p>

        <h2>Child Information</h2>
        <?php if ($childInfo): ?>
            <p><strong>First Name:</strong> <?php echo htmlspecialchars($childInfo['firstName']);?></p>
            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($childInfo['lastName']);?></p>
            <p><strong>City:</strong> <?php echo htmlspecialchars($childInfo['city']);?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($childInfo['address']);?></p>
            <p><strong>Postal Code:</strong> <?php echo htmlspecialchars($childInfo['postalCode']);?></p>
            <p><strong>Province:</strong> <?php echo htmlspecialchars($childInfo['province']);?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($childInfo['locName']);?></p>
        <?php else: ?>
            <p>No information found for this child.</p>
        <?php endif; ?>
        <button onclick="openModal()" style="margin-bottom: 10px">Edit child</a></button>
        <button style="margin-bottom: 10px"><a href="clubMembers.php">Back to Club Members</a></button>
    </div>

    <!-- Modal -->
    <div id="editModal" class="modal">
        <div class="form" style="margin-top: 10px;">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Child Information</h2>
            <form id="editForm" action="./edit/editchild.php" method="post">
            
        <p> <?php echo ($childInfo['locName']);?> </p>
                <input type="hidden" name="personID" value="<?php echo htmlspecialchars($childInfo['personID']);?>">

                <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($childInfo['firstName']);?>" required><br>

                <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($childInfo['lastName']);?>" required><br>

                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($childInfo['city']);?>" required><br>

                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($childInfo['address']);?>" required><br>

                <input type="text" id="postalCode" name="postalCode" value="<?php echo htmlspecialchars($childInfo['postalCode']);?>" required><br>

                <select name="province">
                    <option value="NL" <?php if ($childInfo['province'] == 'NL') echo 'selected'; ?> >Newfoundland & Labrador</option>
                    <option value="PE" <?php if ($childInfo['province'] == 'PE') echo 'selected'; ?>>Prince Edward Island</option>
                    <option value="NS" <?php if ($childInfo['province'] == 'NS') echo 'selected'; ?>>Nova Scotia</option>
                    <option value="NB" <?php if ($childInfo['province'] == 'NB') echo 'selected'; ?>>New Brunswick</option>
                    <option value="QC" <?php if ($childInfo['province'] == 'QC') echo 'selected'; ?>>Quebec</option>
                    <option value="ON" <?php if ($childInfo['province'] == 'ON') echo 'selected'; ?>>Ontario</option>
                    <option value="MB" <?php if ($childInfo['province'] == 'MB') echo 'selected'; ?>>Manitoba</option>
                    <option value="SK" <?php if ($childInfo['province'] == 'SK') echo 'selected'; ?>>Saskatchewan</option>
                    <option value="AB" <?php if ($childInfo['province'] == 'AB') echo 'selected'; ?>>Alberta</option>
                    <option value="BC" <?php if ($childInfo['province'] == 'BC') echo 'selected'; ?>>British Columbia</option>
                    <option value="YT" <?php if ($childInfo['province'] == 'YT') echo 'selected'; ?>>Yukon</option>
                    <option value="NT" <?php if ($childInfo['province'] == 'NT') echo 'selected'; ?>>Northwestern Territories</option>
                    <option value="NU" <?php if ($childInfo['province'] == 'NU') echo 'selected'; ?>>Nunavut</option>
                </select>


                <select name="location" id="location">
                    <?php echo $options; ?>
                </select>
                <button type="submit">Save</button>
            </form>
        </div>
    </div>


    <script src="../script.js"></script>
</body>
</html>



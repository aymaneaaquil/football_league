<?php

include '../config.php';

$personnelID = isset($_GET['personnelID']) ? $_GET['personnelID'] : 0;

$query = "SELECT l.locName, w.startDate, w.endDate, w.role
          FROM WorksAt w
          JOIN Location l ON w.locationID = l.locationID
          WHERE w.personnelID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $personnelID);
$stmt->execute();
$result = $stmt->get_result();

$workHistory = [];
while ($row = $result->fetch_assoc()) {
    $workHistory[] = $row;
}

$stmt->close();
$conn->close();

if (empty($workHistory)) {
    echo "No work history found.";
} else {
    echo "<ul>";
    foreach ($workHistory as $work) {
        echo "<li>" . htmlspecialchars($work['locName']) . " - " . htmlspecialchars($work['startDate']) . " to " . ($work['endDate'] ? htmlspecialchars($work['endDate']) : "Present") . " (" . htmlspecialchars($work['role']) . ")</li>";
    }
    echo "</ul>";
}
?>

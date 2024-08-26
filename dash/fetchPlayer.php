<?php
include 'config.php'; 

$teamFormationID = $_GET['teamFormationID']; 

function getLocationID($teamFormationID, $pdo) {
    $sql = "SELECT locationID FROM TeamFormation WHERE teamFormationID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$teamFormationID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['locationID'] : null;
}

$locationID = getLocationID($teamFormationID, $pdo);

$sql = "SELECT cm.clubMemberID, CONCAT (p.firstName,' ' ,p.lastName) as name, locationID
                FROM ClubMember cm
                JOIN Person p ON cm.clubMemberID = p.personID
                JOIN PartOf po ON cm.clubMemberID = po.clubMemberID
                WHERE cm.isActive = 1 AND po.locationID = ? AND po.endDate IS NULL";

$stmt = $pdo->prepare($sql);
$stmt->execute([$locationID]);
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($players);
?>
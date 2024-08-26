<?php
include '../../config.php';
session_start();
$id = $_SESSION['personID'];
$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];
$ssn = $_REQUEST['ssn'];
$med = $_REQUEST['mednb'];
$dob = $_REQUEST['dob'];
$province = $_REQUEST['province'];
$city = $_REQUEST['city'];
$address = $_REQUEST['address'];
$postal = $_REQUEST['postal'];
$location = $_REQUEST['location'];
$start = date("Y/m/d");
$relation = $_REQUEST['relation'];

$stmt_person = $conn->prepare("INSERT INTO Person (SSN, medicareNb, firstName, lastName, dateOfBirth, province, city, address, postalCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt_person->bind_param("sssssssss", $ssn, $med, $fname, $lname, $dob, $province, $city, $address, $postal);
$person_executed = $stmt_person->execute();
$personID = $conn->insert_id;

$stmt_member = $conn->prepare("INSERT INTO ClubMember (clubMemberID) VALUES (?)");
$stmt_member->bind_param("i", $personID);
$member_executed = $stmt_member->execute();

$stmt_related = $conn->prepare("INSERT INTO Related (familyMemberID, clubMemberID, relation) VALUES (?, ?, ?)");
$stmt_related->bind_param("iis",  $id, $personID, $relation);
$related_executed = $stmt_related->execute();

$stmt_partOf = $conn->prepare("INSERT INTO PartOf (clubMemberID, locationID, startDate) VALUES (?, ?, ?)");
$stmt_partOf->bind_param("iis", $personID,$location,$start);
$partOf_executed = $stmt_partOf->execute();

if ($person_executed && $member_executed && $related_executed && $partOf_executed){
   
    header("Location: ../clubMembers.php");
    exit(); 
} else {
   
    if (!$person_executed) {
        echo "Error inserting into Person table: " . $stmt_person->error;
    }
    if (!$member_executed) {
        echo "Error inserting into clubMember table: " . $stmt_member->error;
    }
    if (!$related_executed){
        echo "Error inserting into related table" . $stmt_related;
    }
    if (!$partOf_executed){
        echo "Error inserting into partOf table" . $stmt_partOf;
    }
}  

$stmt_person->close();
$stmt_member->close();
?>
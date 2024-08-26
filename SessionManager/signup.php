<?php
include '../config.php';

$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];
$email = $_REQUEST['email'];
$phone = $_REQUEST['phone'];
$ssn = $_REQUEST['ssn'];
$med = $_REQUEST['mednb'];
$dob = $_REQUEST['dob'];
$province = $_REQUEST['province'];
$city = $_REQUEST['city'];
$address = $_REQUEST['address'];
$postal = $_REQUEST['postal'];


$stmt_person = $conn->prepare("INSERT INTO Person (SSN, medicareNb, firstName, lastName, dateOfBirth, province, city, address, postalCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt_person->bind_param("sssssssss", $ssn, $med, $fname, $lname, $dob, $province, $city, $address, $postal);
$person_executed = $stmt_person->execute();

$personID = $conn->insert_id;

$stmt_family = $conn->prepare("INSERT INTO FamilyMember (familyMemberID, phone, email) VALUES (?, ?, ?)");
$stmt_family->bind_param("iss", $personID, $phone, $email);

$family_executed = $stmt_family->execute();


 if ($person_executed && $family_executed) {
   
    header("Location: login.html");
    exit(); 
} else {
   
    if (!$person_executed) {
        echo "Error inserting into Person table: " . $stmt_person->error;
    }
    if (!$family_executed) {
        echo "Error inserting into FamilyMember table: " . $stmt_family->error;
    }
}  

$stmt_person->close();
$stmt_family->close();
mysqli_close($conn);
?>
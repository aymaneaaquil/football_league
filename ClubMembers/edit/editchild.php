<?php
include '../../config.php'; 

session_start();


$id = $_POST['personID'];
$fname = $_POST['firstName'];
$lname = $_POST['lastName'];
$city = $_POST['city'];
$address = $_POST['address'];
$postal = $_POST['postalCode'];
$province = $_POST['province'];
$location = $_POST['location'];

$updatePersonSQL = "UPDATE Person SET firstName = ?, city = ?, address = ?, postalCode = ?, province = ? WHERE personID = ?";

if ($stmt = $conn->prepare($updatePersonSQL)) {
    $stmt->bind_param("ssssss", $fname, $city, $address, $postal, $province, $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: ../clubMembers.php");
    exit();
?>


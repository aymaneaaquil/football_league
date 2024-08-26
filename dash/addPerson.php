<?php

include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $SSN = $_POST['SSN'];
    $medicareNb = $_POST['medicareNb'];
    $gender = $_POST['gender'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $postalCode = $_POST['postalCode'];
    $mandate = $_POST['mandate'];
    $phone = $_POST['phone'];
    $locationID = $_POST['locationID'];
    $role = $_POST['role'];
    $startDate = date('Y-m-d');

    //person table
    $query = "INSERT INTO Person (firstName, lastName, dateOfBirth, SSN, medicareNb, gender, province, city, address, postalCode)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssss", $firstName, $lastName, $dateOfBirth, $SSN, $medicareNb, $gender, $province, $city, $address, $postalCode);
    if ($stmt->execute()) {
        $personID = $stmt->insert_id;

        //personnel table
        $query = "INSERT INTO Personnel (personnelID, mandate, email, phone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $personID, $mandate, $email, $phone);
        $stmt->execute();

        //worksAt table
        $query = "INSERT INTO WorksAt (personnelID, locationID, startDate, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiss", $personID, $locationID, $startDate, $role);
        $stmt->execute();

        header("Location: ./personnel.php");

    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

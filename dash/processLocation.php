<?php
include '../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $locationID = isset($_POST['locationID']) ? $_POST['locationID'] : null;
    $generalManagerID = $_POST['generalManagerID'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $postalCode = $_POST['postalCode'];
    $locName = $_POST['locName'];
    $phoneNumber = $_POST['phoneNumber'];
    $webAddress = $_POST['webAddress'];
    $type = $_POST['type'];
    $capacity = $_POST['capacity'];

    if ($locationID) {

        $sql = "UPDATE Location SET generalManagerID=?, province=?, city=?, address=?, postalCode=?, locName=?, phoneNumber=?, webAddress=?, type=?, capacity=? WHERE locationID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssssssi', $generalManagerID, $province, $city, $address, $postalCode, $locName, $phoneNumber, $webAddress, $type, $capacity, $locationID);
    } else {

        $sql = "INSERT INTO Location (generalManagerID, province, city, address, postalCode, locName, phoneNumber, webAddress, type, capacity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssssss', $generalManagerID, $province, $city, $address, $postalCode, $locName, $phoneNumber, $webAddress, $type, $capacity);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

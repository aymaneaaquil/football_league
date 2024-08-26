<?php
session_start(); 
require '../../config.php';
if (!isset($_SESSION['personID'])) {
    header("Location: ../../SessionManager/login.html");
    exit();
}

$id = $_SESSION['personID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $postal = $_POST['postal'];
    $province = $_POST['province'];
    $secondaryID = $_POST['secondaryID'];


    $updatePersonSQL = "UPDATE Person SET firstName = ?, lastName = ?, city = ?, address = ?, postalCode = ?, province = ? WHERE personID = ?";
    $updateFamilyMemberSQL = "UPDATE FamilyMember SET phone = ?, email = ?, secondaryID = ? WHERE familyMemberID = ?";


    if ($stmt = $conn->prepare($updatePersonSQL)) {
        $stmt->bind_param("sssssss", $fname, $lname, $city, $address, $postal, $province, $id);
        $stmt->execute();
        $stmt->close();
    }

    if ($stmt = $conn->prepare($updateFamilyMemberSQL)) {
        $stmt->bind_param("ssss", $phone, $email,$secondaryID, $id);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['firstName'] = $fname;
    $_SESSION['lastName'] = $lname;
    $_SESSION['email'] = $email;


    header("Location: ../profile.php");
    exit();
}


$queryPerson = "SELECT * FROM Person WHERE personID = ?";
$queryFamilyMember = "SELECT * FROM FamilyMember WHERE familyMemberID = ?";

if ($stmt = $conn->prepare($queryPerson)) {
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $personResult = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($stmt = $conn->prepare($queryFamilyMember)) {
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $familyMemberResult = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <div class="form">
        <form class="login-form" action="editmyinfo.php" method="post">
            <h1>Edit your information</h1>
            <p>Logged in as <?php echo $_SESSION['firstName']?> <?php echo $_SESSION['lastName']?> </p>
            <input type="text" placeholder="First Name" name="fname" value="<?php echo htmlspecialchars($personResult['firstName']); ?>" />
            <input type="text" placeholder="Last Name" name="lname" value="<?php echo htmlspecialchars($personResult['lastName']); ?>" />
            <input type="text" placeholder="Email" name="email" value="<?php echo htmlspecialchars($familyMemberResult['email']); ?>" />
            <input type="text" placeholder="Phone Number" name="phone" value="<?php echo htmlspecialchars($familyMemberResult['phone']); ?>" />
            <input type="text" placeholder="City" name="city" value="<?php echo htmlspecialchars($personResult['city']); ?>" />
            <input type="text" placeholder="Address" name="address" value="<?php echo htmlspecialchars($personResult['address']); ?>" />
            <input type="text" placeholder="Postal Code" name="postal" value="<?php echo htmlspecialchars($personResult['postalCode']); ?>" />
            <select name="province">
              <option value="NL" <?php if ($personResult['province'] == 'NL') echo 'selected'; ?>>Newfoundland & Labrador</option>
              <option value="PE" <?php if ($personResult['province'] == 'PE') echo 'selected'; ?>>Prince Edward Island</option>
              <option value="NS" <?php if ($personResult['province'] == 'NS') echo 'selected'; ?>>Nova Scotia</option>
              <option value="NB" <?php if ($personResult['province'] == 'NB') echo 'selected'; ?>>New Brunswick</option>
              <option value="QC" <?php if ($personResult['province'] == 'QC') echo 'selected'; ?>>Quebec</option>
              <option value="ON" <?php if ($personResult['province'] == 'ON') echo 'selected'; ?>>Ontario</option>
              <option value="MB" <?php if ($personResult['province'] == 'MB') echo 'selected'; ?>>Manitoba</option>
              <option value="SK" <?php if ($personResult['province'] == 'SK') echo 'selected'; ?>>Saskatchewan</option>
              <option value="AB" <?php if ($personResult['province'] == 'AB') echo 'selected'; ?>>Alberta</option>
              <option value="BC" <?php if ($personResult['province'] == 'BC') echo 'selected'; ?>>British Columbia</option>
              <option value="YT" <?php if ($personResult['province'] == 'YT') echo 'selected'; ?>>Yukon</option>
              <option value="NT" <?php if ($personResult['province'] == 'NT') echo 'selected'; ?>>Northwestern Territories</option>
              <option value="NU" <?php if ($personResult['province'] == 'NU') echo 'selected'; ?>>Nunavut</option>
            </select>
            <input type="text" placeholder="Emergency Contact ID" name="secondaryID" value="<?php echo htmlspecialchars($familyMemberResult['secondaryID']); ?>"/>
            <button type="submit" style="margin-bottom: 10px">Save</button>
            <button id="logout" class="logout" style="margin-bottom: 10px"><a href="../test.php">Cancel</a></button>
        </form>
    </div>
</body>
</html>

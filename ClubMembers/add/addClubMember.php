<?php
  session_start();

include('../../config.php');


$sql = "SELECT capacity, locName, locationID FROM Location";
$result = $conn->query($sql);

$options = "";
if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        $locationID = $row['locationID'];
        $locName = $row['locName'];
        $capacity = $row['capacity'];
        $options .= "<option value='{$locationID}'>{$locName} (Capacity: {$capacity})</option>";
    }
} else {
    $options = "<option value=''>No locations available</option>";
}
$id = $_SESSION['personID'];


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/main.css">
    <title>Add member</title>
</head>
<body>
    <div class="login-page">
        <div class="form">
          <form class="login-form" action="addChild.php" method="post">
            <p>Add Member</p>
            <input type="text" placeholder="First Name" name="fname" required/>
            <input type="text" placeholder="Last Name" name="lname" required/>
            <input type="text" placeholder="Social Security Number" name="ssn" required/>
            <input type="text" placeholder="Mediccare Number" name="mednb" required/>
            <input placeholder="Date of Birth" name="dob" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"/>
            <input type="text" placeholder="City" name="city" required/>
            <input type="text" placeholder="Address" name="address" required/>
            <input type="text" placeholder="Postal Code" name="postal" required/>
            <select name="location" required>
                    <?php echo $options; ?>
                </select>
            <select name="relation">
              <option value="Father">Father</option>
              <option value="Mother">Mother</option>
              <option value="GrandFather">Grand Father</option>
              <option value="GrandMother">Grand Mother</option>
              <option value="Tutor">Tutor</option>
              <option value="Partner">Partner</option>
              <option value="Friend">Friend</option>
              <option value="Other">Other</option>
            </select>
            <select name="province">
              <option value="NL">Newfoundland & Labrador</option>
              <option value="PE">Prince Edward Island</option>
              <option value="NS">Nova Scotia</option>
              <option value="NB">New Brunswick</option>
              <option value="QC">Quebec</option>
              <option value="ON">Ontario</option>
              <option value="MB">Manitoba</option>
              <option value="SK">Saskatchewan</option>
              <option value="AB">Alberta</option>
              <option value="BC">British Columbia</option>
              <option value="YT">Yukon</option>
              <option value="NT">Northwestern Territories</option>
              <option value="NU">Nunavut</option>
            </select>
            <button style="margin-bottom: 10px" type="submit">Register member</button>
            <button style="margin-bottom: 10px"><a href="../../FamilyMembers/profile.php">Back to profile</a></button>
          </form>
        </div>
      </div>
</body>
</html>
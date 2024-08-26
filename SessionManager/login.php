<?php

session_start(); 


include '../config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];

    //admin email
    if ($email === 'admin@admin.com') {

        header("Location: ../dash/dashboard.php");
        exit();
    }

    $stmt = $conn->prepare("
        SELECT 
            Person.personID, 
            Person.firstName, 
            Person.lastName 
        FROM 
            FamilyMember 
        JOIN 
            Person 
        ON 
            FamilyMember.familyMemberID = Person.personID 
        WHERE 
            FamilyMember.email = ?
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();
        $personID = $row['personID'];
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $fullName = $firstName . ' ' . $lastName;


        $_SESSION['personID'] = $personID;
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['email'] = $email;


        header("Location: ../FamilyMembers/profile.php");
        exit();
    } 
    exit();
}
?>
<?php
if (isset($_GET['familyMemberID'])) {
    $familyMemberID = intval($_GET['familyMemberID']);

    include '../config.php';

    $query = "
        SELECT R.clubMemberID, P.firstName, P.lastName, P.dateOfBirth, 
        P.SSN, P.medicareNb, P.address, P.city, P.province, P.postalCode, R.relation, R.secondaryRelation
        FROM 
            FamilyMember AS FM
            RIGHT JOIN Related AS R 
            ON FM.familyMemberID = R.familyMemberID
            LEFT JOIN Person AS P 
            ON R.clubMemberID = P.personID
        WHERE FM.familyMemberID = ?
    ";


    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param("i", $familyMemberID);

        $stmt->execute();

        $result = $stmt->get_result();


        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Club Member ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Date of Birth</th>
                        <th>SSN</th>
                        <th>Medicare Number</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Province</th>
                        <th>Postal Code</th>
                        <th>Relation</th>
                        <th>Secondary Relation</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["clubMemberID"] . "</td>
                        <td>" . $row["firstName"] . "</td>
                        <td>" . $row["lastName"] . "</td>
                        <td>" . $row["dateOfBirth"] . "</td>
                        <td>" . $row["SSN"] . "</td>
                        <td>" . $row["medicareNb"] . "</td>
                        <td>" . $row["phone"] . "</td>
                        <td>" . $row["address"] . "</td>
                        <td>" . $row["city"] . "</td>
                        <td>" . $row["province"] . "</td>
                        <td>" . $row["postalCode"] . "</td>
                        <td>" . $row["relation"] . "</td>
                        <td>" . $row["secondaryRelation"] . "</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }


        $stmt->close();
    } else {

        echo "Error preparing statement: " . $conn->error;
    }


    $conn->close();
}
?>

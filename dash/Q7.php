<?php
include '../config.php';

$query = "
    SELECT 
        L.address AS Address, 
        L.city AS City, 
        L.province AS Province, 
        L.postalCode AS Postal_Code, 
        L.phoneNumber AS Phone_Number, 
        L.webAddress AS Website, 
        L.type AS Type_of_Location, 
        L.capacity AS Location_Capacity, 
        COUNT(CM.clubMemberID) AS Member_Count,
        CONCAT(P.firstName, ' ', P.lastName) AS General_Manager_Name
    FROM 
        Location AS L
        LEFT JOIN Personnel PL ON L.generalManagerID = PL.personnelID
        LEFT JOIN Person P ON PL.personnelID = P.personID
        LEFT JOIN PartOf AS PO ON PO.locationID = L.locationID
        LEFT JOIN ClubMember CM ON PO.clubMemberID = CM.clubMemberID
    GROUP BY 
        L.locationID, L.address, L.city, L.province, L.postalCode, L.phoneNumber, L.webAddress, L.type, L.capacity, General_Manager_Name
    ORDER BY 
        L.province ASC, 
        L.city ASC;
    ";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Address</th>
                <th>City</th>
                <th>Province</th>
                <th>Postal Code</th>
                <th>Phone Number</th>
                <th>Website</th>
                <th>Type of Location</th>
                <th>Location Capacity</th>
                <th>Member Count</th>
                <th>General Manager Name</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["Address"]."</td>
                <td>".$row["City"]."</td>
                <td>".$row["Province"]."</td>
                <td>".$row["Postal_Code"]."</td>
                <td>".$row["Phone_Number"]."</td>
                <td>".$row["Website"]."</td>
                <td>".$row["Type_of_Location"]."</td>
                <td>".$row["Location_Capacity"]."</td>
                <td>".$row["Member_Count"]."</td>
                <td>".$row["General_Manager_Name"]."</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
?>

<?php
include '../config.php';
session_start();


$sql = "SELECT * FROM Location";
$result = $conn->query($sql);
$locations = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 70%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: slide-down 0.4s ease-out;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        @keyframes slide-down {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        #personDetails, #addPersonForm {
            text-align: center;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="form">
        <div id="locationList">
            <?php foreach ($locations as $location): ?>
                <button style="margin-top:10px" class="locationBtn" data-location='<?php echo json_encode($location); ?>'>
                    <?php echo $location['locName']; ?>
                </button>
            <?php endforeach; ?>
        </div>
        <button style="margin-top:40px" id="addLocationBtn">Add Location</button>
        <button style="margin-top: 10px"><a href="./dashboard.php">Back</a></button>


    </div>

    <div id="locationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="locationForm">
                <input type="hidden" name="locationID" id="locationID">
                <label for="generalManagerID">General Manager ID:</label>
                <input type="text" name="generalManagerID" id="generalManagerID">
                <label for="province">Province:</label>
                <select name="province" id="province">
                    <option value="AB">AB</option>
                    <option value="BC">BC</option>
                    <option value="MB">MB</option>
                    <option value="NB">NB</option>
                    <option value="NL">NL</option>
                    <option value="NS">NS</option>
                    <option value="ON">ON</option>
                    <option value="PE">PE</option>
                    <option value="QC">QC</option>
                    <option value="SK">SK</option>
                </select>
                <label for="city">City:</label>
                <input type="text" name="city" id="city">
                <label for="address">Address:</label>
                <input type="text" name="address" id="address">
                <label for="postalCode">Postal Code:</label>
                <input type="text" name="postalCode" id="postalCode">
                <label for="locName">Location Name:</label>
                <input type="text" name="locName" id="locName">
                <label for="phoneNumber">Phone Number:</label>
                <input type="text" name="phoneNumber" id="phoneNumber">
                <label for="webAddress">Web Address:</label>
                <input type="text" name="webAddress" id="webAddress">
                <label for="type">Type:</label>
                <input type="text" name="type" id="type">
                <label for="capacity">Capacity:</label>
                <input type="text" name="capacity" id="capacity">
                <button type="submit">Save</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('locationModal');
    const span = document.getElementsByClassName('close')[0];
    const addLocationBtn = document.getElementById('addLocationBtn');
    const locationForm = document.getElementById('locationForm');

    document.querySelectorAll('.locationBtn').forEach(button => {
        button.addEventListener('click', () => {
            const location = JSON.parse(button.getAttribute('data-location'));
            document.getElementById('locationID').value = location.locationID;
            document.getElementById('generalManagerID').value = location.generalManagerID;
            document.getElementById('province').value = location.province;
            document.getElementById('city').value = location.city;
            document.getElementById('address').value = location.address;
            document.getElementById('postalCode').value = location.postalCode;
            document.getElementById('locName').value = location.locName;
            document.getElementById('phoneNumber').value = location.phoneNumber;
            document.getElementById('webAddress').value = location.webAddress;
            document.getElementById('type').value = location.type;
            document.getElementById('capacity').value = location.capacity;
            modal.style.display = 'block';
        });
    });

    addLocationBtn.addEventListener('click', () => {
        document.getElementById('locationForm').reset();
        document.getElementById('locationID').value = '';
        modal.style.display = 'block';
    });

    span.onclick = () => {
        modal.style.display = 'none';
    }

    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    locationForm.onsubmit = (event) => {
        event.preventDefault();
        const formData = new FormData(locationForm);

        fetch('processLocation.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
                locationForm.reset();
                modal.style.display = 'none';

            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the location');
        });
    }
});
    </script>
</body>
</html>

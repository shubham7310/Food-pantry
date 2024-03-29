<?php
// Ensure that the database connection variables are defined in database.php
require_once("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish database connection
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if (!$conn) {
        die("Failed to connect to MySQL: " . mysqli_connect_error());
    }

    // Get form data and sanitize inputs
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
    $alternatePhoneNumber = mysqli_real_escape_string($conn, $_POST['alternatePhoneNumber']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $pinCode = mysqli_real_escape_string($conn, $_POST['pinCode']);
    $nearbyStation = mysqli_real_escape_string($conn, $_POST['NearbyStation']);
    $foodName = mysqli_real_escape_string($conn, $_POST['foodName']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $foodType = mysqli_real_escape_string($conn, $_POST['foodType']);

    // File upload information
    $foodPictureFilename = $_FILES['foodPicture']['name'];
    $foodPictureTempname = $_FILES['foodPicture']['tmp_name'];
    $foodPictureFolder = "profile_pictures/" . basename($foodPictureFilename);

    // Validate uploaded file
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($foodPictureFilename, PATHINFO_EXTENSION));
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    if (!in_array($fileExtension, $allowedExtensions) || $_FILES['foodPicture']['size'] > $maxFileSize) {
        echo "Error: Invalid file or file size exceeds limit.";
        exit();
    }

    // Prepare and bind SQL statement
    $sql = "INSERT INTO donation (Full_Name, Phone_Number, Alternate_Phone_Number, Address, City, State, Pin_Code, Nearby_station, Food_Name, Quantity, Food_Picture, Food_Type) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssssss", $fullName, $phoneNumber, $alternatePhoneNumber, $address, $city, $state, $pinCode, $nearbyStation, $foodName, $quantity, $foodPictureFilename, $foodType);

    // Check if the file is successfully uploaded
    if (move_uploaded_file($foodPictureTempname, $foodPictureFolder)) {
        // Execute the SQL query
        if (mysqli_stmt_execute($stmt)) {
            // Redirect after successful database insertion
            header("Location: thank_you_page.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error uploading files.";
    }

    // Close statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Food Donation Form</title>
    <link rel="stylesheet" href="donate.css"/>
    <script>
        function submitForm() {
            // Perform form validation
            if (validateForm()) {
                // Submit the form (not implemented in this function)
                // If the form submission is successful, redirect to thank you page
                if (confirm("Confirm donation?")) {
                    document.getElementById("foodDonationForm").submit();
                }
            }
        }

        function validateForm() {
            var fullName = document.getElementById("fullName").value;
            var phoneNumber = document.getElementById("phoneNumber").value;
            var alternatePhoneNumber = document.getElementById("alternatePhoneNumber").value;
            var address = document.getElementById("address").value;
            var city = document.getElementById("city").value;
            var state = document.getElementById("state").value;
            var pinCode = document.getElementById("pinCode").value;
            var NearbyStation = document.getElementById("NearbyStation").value;
            var foodName = document.getElementById("foodName").value;
            var quantity = document.getElementById("quantity").value;
            var foodPicture = document.getElementById("foodPicture").value;

            // Simple validation - ensure required fields are not empty
            if (
                fullName === "" ||
                phoneNumber === "" ||
                address === "" ||
                city === "" ||
                state === "" ||
                pinCode === "" ||
                NearbyStation === "" ||
                foodName === "" ||
                quantity === "" ||
                foodPicture === ""
            ) {
                alert("Please fill out all required fields.");
                return false;
            }

            // Validate phone number format
            var phoneRegex = /^[0-9]{10}$/;
            if (!phoneRegex.test(phoneNumber)) {
                alert("Please enter a valid phone number.");
                return false;
            }

            // Validate alternate phone number if provided
            if (alternatePhoneNumber !== "" && !phoneRegex.test(alternatePhoneNumber)) {
                alert("Please enter a valid alternate phone number.");
                return false;
            }

            // Validate pin code format
            var pinCodeRegex = /^[0-9]{6}$/;
            if (!pinCodeRegex.test(pinCode)) {
                alert("Please enter a valid pin code.");
                return false;
            }

            // Validate quantity format
            var quantityRegex = /^[1-9][0-9]*$/; // Ensures quantity is a positive integer
            if (!quantityRegex.test(quantity)) {
                alert("Please enter a valid quantity (must be a positive integer).");
                return false;
            }

            return true; // Return true if all validations pass
        }
    </script>
</head>
<body>
<h1>Donation Form</h1>
<form id="foodDonationForm" action="donate.php" method="post" enctype="multipart/form-data">

    <label for="fullName">Full Name:</label>
    <input type="text" id="fullName" name="fullName" required/>

    <label for="phoneNumber">Phone Number:</label>
    <input
            type="tel"
            id="phoneNumber"
            name="phoneNumber"
            pattern="[0-9]{10}"
            required
    />

    <label for="alternatePhoneNumber">Alternate Phone Number:</label>
    <input
            type="tel"
            id="alternatePhoneNumber"
            name="alternatePhoneNumber"
            pattern="[0-9]{10}"
    />

    <label for="address">Address:</label>
    <textarea id="address" name="address" required></textarea>

    <label for="city">City:</label>
    <input type="text" id="city" name="city" required/>

    <label for="state">State:</label>
    <input type="text" id="state" name="state" required/>

    <label for="pinCode">Pin Code:</label>
    <input type="text" id="pinCode" name="pinCode" required/>

    <label for="NearbyStation">Nearby Station:</label>
      <select id="NearbyStation" name="NearbyStation" required>
        <option value="">Select Nearby Station</option>
          <option value="Andheri">Andheri</option>
          <option value="Bandra">Bandra</option>
          <option value="Borivali">Borivali</option>
          <option value="Charni Road">Charni Road</option>
          <option value="Churchgate">Churchgate</option>
          <option value="Dadar">Dadar</option>
          <option value="Goregaon">Goregaon</option>
          <option value="Grant Road">Grant Road</option>
          <option value="Kandivali">Kandivali</option>
          <option value="Kurla">Kurla</option>
          <option value="Lower Parel">Lower Parel</option>
          <option value="Mahalaxmi">Mahalaxmi</option>
          <option value="Malad">Malad</option>
          <option value="Matunga">Matunga</option>
          <option value="Mumbai Central">Mumbai Central</option>
          <option value="Santacruz">Santacruz</option>
          <option value="Vashi">Vashi</option>
          <option value="Nerul">Nerul</option>
          <option value="CBD Belapur">CBD Belapur</option>
          <option value="Kharghar">Kharghar</option>
          <option value="Panvel">Panvel</option>
          <option value="Kopar Khairane">Kopar Khairane</option>
          <option value="Airoli">Airoli</option>
          <option value="Turbhe">Turbhe</option>
          <option value="Sanpada">Sanpada</option>
          <option value="Vashi">Vashi</option>
        </optgroup>
      </select>


    <label for="foodName">Food Name:</label>
    <input type="text" id="foodName" name="foodName" required/>

    <label for="foodPicture">Upload Food Picture:</label>
    <input
            type="file"
            id="foodPicture"
            name="foodPicture"
            accept="image/*"
            required
    />

    <label for="quantity">Quantity (Approx in Kgs):</label>
    <input type="number" id="quantity" name="quantity" required/>

    <label for="foodType">Food Type:</label>
    <select id="foodType" name="foodType" required>
        <option value="">Select Food Type</option>
        <option value="cooked">Cooked</option>
        <option value="uncooked">Uncooked</option>
    </select>

    <button type="button" onclick="submitForm()">Submit</button>
</form>

<div id="successMessage" style="display: none">
    Donation successfully completed!
</div>

</body>
</html>

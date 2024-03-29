<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Donations</title>
    <style>
/* Reset some default styles */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
      /* Reset some default styles */
      body,
      h1,
      p {
        margin: 0;
        padding: 0;
      }
      background-color:rgb(255, 145, 0)
      /* Apply a background color to the body */
      body {
        background-color:  rgb(255, 145, 0)
        font-family: "Poppins", sans-serif;
      }

      /* Style for the main container */
      .container {
        max-width: 800px;
        margin: 20px auto;
        background-color: rgb(255, 145, 0); /* Same as specified */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      /* CSS styles */
.donation-image {
    width: 100px; /* Adjust the width as needed */
    height: 100px; /* Set the height equal to the width to maintain square shape */
    object-fit: cover; /* Ensure the image covers the entire container */
    border-radius: 5px; /* Add rounded corners if desired */
    margin-bottom: 10px; /* Add some spacing between images */
}


      /* Style for the heading */
      h1 {
        color: #f7f5f5; /* Same as specified */
        margin-bottom: 20px;
      }

      /* Style for donation container */
      .donation-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between; /* Equal spacing between each donation container */
      }

      /* Style for each donation */
      .donation {
        margin: 0 0 20px 0; /* Equal margin on all sides */
        padding: 10px;
        border: 1px solid #000000;
        border-radius: 5px;
        width: 300px; /* Adjust width as needed */
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        background-color: #ececec; /* Same as specified */
      }

      .donation img {
        width: 100px; /* Adjust size of image */
        height: auto;
        border-radius: 5px;
        margin-right: 10px;
      }

      .donation div {
        color: #000000; /* Same as specified */
      }

      .donation p {
        margin: 5px 0;
      }

      /* CSS styles */
.card {
    display: flex; /* Use flexbox for layout */
    margin-bottom: 20px; /* Add some spacing between cards */
}

.donation-image {
    width: 100px; /* Adjust the width as needed */
    height: 100px; /* Set the height equal to the width to maintain square shape */
    object-fit: cover; /* Ensure the image covers the entire container */
    border-radius: 5px; /* Add rounded corners if desired */
    margin-right: 20px; /* Add some spacing between the image and text */
}

.content {
    display: flex; /* Use flexbox for layout */
    flex-direction: row; /* Align content horizontally */
    align-items: center; /* Align items vertically */
}

.donation-info {
    text-align: left; /* Align text to the left */
}
/* Style for the button */
.button {
    display: inline-block;
    padding: 10px 20px; /* Adjust padding as needed */
    background-color: black; /* Green */
    color: white;
    text-align: center;
    text-decoration: none;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

/* Hover effect */
.button:hover {
    background-color:black; /* Darker green */
}
    </style>
</head>
<body>
    <div class="container">
        <h1>NGO Donations</h1>
        <div class="donation-container">
            <?php
            // Include the database connection file
            require_once "database.php";

            // Establish a connection to the database
            $conn = mysqli_connect($servername, $username, $password, $database);

            // Check if the connection was successful
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Query to retrieve donation data from the database

           $sql = "SELECT * FROM donation ";  

            // Execute the query
            $result = mysqli_query($conn, $sql);

// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
  // Output data of each row
  while ($row = mysqli_fetch_assoc($result)) {
      // Display the donation information in HTML format
      echo "<div class='card'>";
      echo "<div class='content'>";
      // Set a class for the image to apply CSS styling
      echo "<img class='donation-image' src='profile_pictures/{$row['Food_Picture']}' alt='{$row['Food_Name']}' />";
      echo "<div class='donation-info'>";
      echo "<p>Food Name: {$row['Food_Name']}</p>";
      echo "<p>Quantity (Approx in Kgs): {$row['Quantity']}</p>";
      echo "<p>Food Type: {$row['Food_Type']}</p>";
      echo "<p>Full Name: {$row['Full_Name']}</p>";
      echo "<p>Phone Number: {$row['Phone_Number']}</p>";
      echo "<p>Alternate Phone Number: {$row['Alternate_Phone_Number']}</p>";
      echo "<p>Address: {$row['Address']}</p>";
      echo "<p>City: {$row['City']}</p>";
      echo "<p>State: {$row['State']}</p>";
      echo "<p>Pin Code: {$row['Pin_Code']}</p>";
      echo "<p>Nearby Station: {$row['Nearby_station']}</p>";
      echo "</div>"; // Close .donation-info
      echo "</div>"; // Close .content
      echo "</div>"; // Close .card
  }
} else {
  // Display message if no donations found
  echo "No donations found.";
}



            // Close the database connection
            mysqli_close($conn);
            ?>
        </div>
        <div class="main-button">
        <a href="ngo.php" class="button">Go to Main Page</a>
    </div>
    </div>
</body>
</html>


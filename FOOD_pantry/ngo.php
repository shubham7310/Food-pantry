<?php
session_start(); // Start the session
require_once "database.php";

// Redirect to login page if user is not logged in
if (!isset($_SESSION["ngo_user"])) {
    header("Location: login.php");
    exit();
}

// Process form submission to confirm pickup
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_pickup"])) {
    // Check if the form data is valid
    if (!empty($_POST['donation_id'])) {
        $donation_id = $_POST['donation_id'];
        $ngo_id = $_SESSION["ngo_user"]["id"];

        // Establish database connection
        $conn = mysqli_connect($servername, $username, $password, $database);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();
        }

        // Update the donation record with the confirming NGO's ID
        $update_sql = "UPDATE donation SET confirmed_by_ngo_id = $ngo_id WHERE d_no = $donation_id";
        if (mysqli_query($conn, $update_sql)) {
            // Echo a success message or perform any necessary actions
            echo "Donation confirmed successfully.";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }

        // Close the database connection
        mysqli_close($conn);
    } else {
        echo "Invalid donation ID.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NGO Page</title>
  <!-- Link to CSS file -->
  <link rel="stylesheet" href="ngo.css">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Catamaran:wght@200&family=Courgette&family=Dancing+Script:wght@700&family=Edu+TAS+Beginner:wght@700&family=Lato:wght@300;900&family=Mukta:wght@700&family=Mulish:wght@300&family=Open+Sans&family=PT+Sans:ital,wght@1,700&family=Poppins:wght@300&family=Raleway:wght@100&family=Roboto&family=Roboto+Condensed:wght@700&family=Roboto+Slab&display=swap" rel="stylesheet">
  <!-- Font Awesome icons -->
  <script src="https://kit.fontawesome.com/f30fac2c61.js" crossorigin="anonymous"></script>
</head>
<body>
  <div class="container">
    <nav>
      <div class="logo">
        <h1>Neighborhood Nourish</h1>
      </div>
      <ul>
        <li><a href="#food">FOOD</a></li>
        <li><a href="#gallery">GALLERY</a></li>
      </ul>
      <div class="icons">
        <div class="dropdown">
          <button class="dropbtn">
            <!-- Icon for user dropdown -->
            <i class="fa-solid fa-user"></i>
          </button>
          <div class="dropdown-content">
            <!-- Logout link -->
            <a href="logout.php">Logout</a>
          </div>
          <!-- Link to view accepted donations -->
          <a href="accepted_donations.php"><i class="fas fa-history"></i></a>
        </div>
      </div>
    </nav>

    <!-- Sort button -->
    <button id="sortButton" onclick="sortDonations()">Sort Donations</button>

    <!-- Section for displaying donated foods -->
    <div id="food">
      <div class="head">
        <h1>Our Foods</h1>
      </div>
      <div class="foodCard">
        <?php
        // Establish a connection to the database
        $conn = mysqli_connect($servername, $username, $password, $database);

        // Check if the connection was successful
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Query to delete old donations
        $delete_sql = "DELETE FROM donation WHERE Donation_Date < NOW() - INTERVAL 20 MINUTE and confirmed_by_ngo_id IS NULL";

        // Execute the delete query
        if (mysqli_query($conn, $delete_sql)) {
            // Display message if old donations were deleted
            if (mysqli_affected_rows($conn) > 0) {
                echo "Old donations successfully deleted.";
            } 
        } else {
            // Display error if deletion fails
            echo "Error deleting old donations: " . mysqli_error($conn);
        }

        // Query to retrieve donation data from the database
        $select_sql = "SELECT * FROM donation WHERE confirmed_by_ngo_id IS NULL";

        // Execute the query
        $result = mysqli_query($conn, $select_sql);

        // Check if there are any rows returned
        if (mysqli_num_rows($result) > 0) {
            // Output data of each row
            while ($row = mysqli_fetch_assoc($result)) {

                // Display the donation information in HTML format
                echo "<div class='card'>";
                echo "<img src='profile_pictures/{$row['Food_Picture']}' alt='{$row['Food_Name']}' />";
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

                // Add the form for Confirm Pickup button
                echo "<form method='post' action='ngo.php'>";
                echo "<input type='hidden' name='donation_id' value='{$row['d_no']}'>";
                echo "<button type='submit' name='confirm_pickup'>Confirm Pickup</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            // Display message if no donations found
            echo "No donations found.";
        }

        // Close the database connection
        mysqli_close($conn);
        ?>
      </div>
    </div>

    <!-- Gallery section -->
    <div id="gallery">
      <div class="head">
        <h1>Foods Gallery</h1>
      </div>
      <div class="gallery">
        <!-- Gallery images -->
        <img src="https://th.bing.com/th/id/OIP.ZBmeV9S22ak8-r9pRW3XgAHaFj?rs=1&pid=ImgDetMain" alt="" />
        <img src="https://i0.wp.com/www.udayfoundation.org/wp-content/uploads/2023/06/Outside-Hospital-Food-Donation.jpg?w=1224&ssl=1" alt="" />
        <img src="https://www.riddhisiddhicharitabletrust.org/images/gallery/4.jpg" alt="" />
        <img src="https://th.bing.com/th/id/OIP.Zat-RaeuwnUDvelLELM2lQAAAA?w=474&h=474&rs=1&pid=ImgDetMain" alt="" />
        <img src="https://im.whatshot.in/img/2018/Sep/og-1537186597.jpg?wm=1&w=1200&h=630&cc=1" alt="" />
        <img src="https://im.whatshot.in/img/2018/Sep/s-2-1537186436.jpg" alt="" />
      </div>
    </div>

    <!-- Footer section -->
    <div class="footer">
      <div class="text">
        <h2>About Us</h2>
        <p>Fresh Food</p>
        <p>Quality</p>
        <p>Affordable</p>
      </div>
      <div class="text">
        <h2>Connect on</h2>
        <p>Twitter</p>
        <p>Facebook</p>
        <p>LinkedIn</p>
        <p>Instagram</p>
      </div>
    </div>
  </div>

  <!-- JavaScript for sorting donations -->
  <script>
    function sortDonations() {
      // Get the container element for donation cards
      var foodCardContainer = document.querySelector('.foodCard');

      // Convert the collection of donation cards to an array
      var donationCards = Array.from(foodCardContainer.children);

      // Sort the donation cards based on food name
      donationCards.sort(function(a, b) {
        var foodNameA = a.querySelector('p:nth-of-type(1)').innerText.toUpperCase();
        var foodNameB = b.querySelector('p:nth-of-type(1)').innerText.toUpperCase();
        if (foodNameA < foodNameB) {
          return -1;
        }
        if (foodNameA > foodNameB) {
          return 1;
        }
        return 0;
      });

      // Clear the container before re-adding sorted donation cards
      foodCardContainer.innerHTML = '';

      // Re-add the sorted donation cards to the container
      donationCards.forEach(function(card) {
        foodCardContainer.appendChild(card);
      });
    }
  </script>
</body>
</html>

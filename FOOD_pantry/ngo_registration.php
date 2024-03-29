<?php
session_start();
require_once "database.php";

if (isset($_SESSION["ngo_user"])) {
    header("Location: ngo_login.php");
    exit();
}

if (isset($_POST["register"])) {
    if(isset($conn)){
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $contact_no = mysqli_real_escape_string($conn, $_POST["contact_no"]);
        $location = mysqli_real_escape_string($conn, $_POST["location"]);
        $registration_no = mysqli_real_escape_string($conn, $_POST["registration_no"]);

        $check_email_sql = "SELECT * FROM ngos WHERE email = '$email'";
        $check_email_result = mysqli_query($conn, $check_email_sql);
        if (mysqli_num_rows($check_email_result) > 0) {
            echo "<div class='alert alert-danger'>Email already exists</div>";
        } else {
            $insert_sql = "INSERT INTO ngos (name, email, password, contact_no, location, registration_no) 
                           VALUES ('$name', '$email', '$hashed_password', '$contact_no', '$location', '$registration_no')";
            if (mysqli_query($conn, $insert_sql)) {
                echo "<div class='alert alert-success'>Registration successful</div>";
                // Redirect to login page after successful registration
                header("Location: ngo_login.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Database connection is not established</div>";
    }
}
?>

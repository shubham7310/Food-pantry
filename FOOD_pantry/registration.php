<?php
session_start();
require_once "database.php";

if (isset($_SESSION["user"])) {
    header("Location: login.html");
    exit();
}

if (isset($_POST["register"])) {
    if(isset($conn)){
        $full_name = mysqli_real_escape_string($conn, $_POST["full_name"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $phone_number = mysqli_real_escape_string($conn, $_POST["phone_number"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
        $confirm_password = mysqli_real_escape_string($conn, $_POST["confirm_password"]);

        $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
        $check_email_result = mysqli_query($conn, $check_email_sql);
        if (mysqli_num_rows($check_email_result) > 0) {
            echo "<div class='alert alert-danger'>Email already exists</div>";
        } else {
            if ($password !== $confirm_password) {
                echo "<div class='alert alert-danger'>Passwords do not match</div>";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert_sql = "INSERT INTO users (name, email, phone_no, password) VALUES ('$full_name', '$email', '$phone_number', '$hashed_password')";
                if (mysqli_query($conn, $insert_sql)) {
                    echo "<div class='alert alert-success'>Registration successful</div>";
                    // Redirect to login page after successful registration
                     header("Location: login.html");
                    // exit();
                } else {
                    echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
                }
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Database connection is not established</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <link rel="stylesheet" href="registration.css">
</head>
<body>
<div class="container">
    <div class="title"><span>Registration Form</span></div>
    <div class="content">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Full Name</span>
                    <input type="text" name="full_name" placeholder="Enter your name" required>
                </div>
                <div class="input-box">
                    <span class="details">Email</span>
                    <input type="text" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-box">
                    <span class="details">Phone Number</span>
                    <input type="text" name="phone_number" placeholder="Enter your number" required>
                </div>
                <div class="input-box">
                    <span class="details">Password</span>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="input-box">
                    <span class="details">Confirm Password</span>
                    <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
            </div>
            <div class="button">
                <input type="submit" name="register" value="Register">
            </div>
            <div class="signup-link">Already registered? <a href="login.html">Login now</a></div>
        </form>
    </div>
</div>
</body>
</html>

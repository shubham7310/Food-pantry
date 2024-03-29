<?php
session_start();
require_once "database.php";

if (isset($_SESSION["ngo_user"])) {
    header("Location: ngo.html");
    exit();
}

if (isset($_POST["login"])) {
    if(isset($conn)){
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);

        $check_user_sql = "SELECT * FROM ngos WHERE email = '$email'";
        $check_user_result = mysqli_query($conn, $check_user_sql);
        if (mysqli_num_rows($check_user_result) > 0) {
            $user_row = mysqli_fetch_assoc($check_user_result);
            if (password_verify($password, $user_row["password"])) {
                $_SESSION["ngo_user"] = $user_row;
                header("Location: ngo.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Invalid email or password</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Invalid email or password</div>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Login</title>
    <link rel="stylesheet" href="ngo_login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>

</head>
<body>
    <div class="container">
        <div class="wrapper">
            <div class="title"><span>NGO Login</span></div>
            <form action="" method="post">
                <div class="row">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="row">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="row button">
                    <input type="submit" name="login" value="Login">
                </div>
                <div class="signup-link">
                    Don't have an account? <a href="ngo_registration.html">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

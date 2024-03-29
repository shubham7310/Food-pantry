<?php
session_start();
include_once "database.php"; // Include your database connection file

if (isset($_SESSION["user"])) {
   header("Location: index.php");
   exit();
}

if (isset($_POST["login"])) {
    if(isset($conn)){
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);

        $sql = "SELECT * FROM users WHERE email = '$username'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user["password"])) {
                $_SESSION["user"] = $user["id"];
                header("Location: user.php");
                exit();
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Database connection is not established";
    }
}
?>

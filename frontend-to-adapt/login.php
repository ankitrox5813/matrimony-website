<?php

session_start();

include("includes/db.php");

if(isset($_POST['login_btn'])){

    $email = $_POST['email'];

    $password = $_POST['password'];

    // Find User

    $query = "SELECT * FROM users
              WHERE email='$email'";

    $result = mysqli_query($conn,$query);

    // Check User Exists

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        // Verify Password

        if(password_verify(
            $password,
            $row['password']
        )){

            // Create Session

            $_SESSION['user_id'] = $row['id'];

            $_SESSION['fullname'] = $row['fullname'];

            // echo "Login Successful";
            header("Location: dashboard/index.php");

exit(); //Stops PHP execution immediately after redirect.

        }else{

            echo "Wrong Password";

        }

    }else{

        echo "User Not Found";

    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Aurivah</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">

</head>

<body>
    <?php include("includes/header.php"); ?>

<section class="form-section">

    <!-- <form class="auth-form"> -->
        <form class="auth-form" method="POST">

        <h2>Welcome Back</h2>

        <p class="form-subtitle">
            Login to continue your matchmaking journey.
        </p>

        <!-- <input type="email" placeholder="Email Address" required> -->
         <input type="email"
       name="email"
       placeholder="Email Address"
       required>

        <!-- <input type="password" placeholder="Password" required> -->
         <input type="password"
       name="password"
       placeholder="Password"
       required>

        <!-- <button type="submit">Login</button> -->
         <!-- <button type="submit"
        name="login_btn">

    Login

</button>

        <div class="form-links">

            <a href="forgotpassword.php">
                Forgot Password?
            </a>

        </div> -->
        <button type="submit"
        name="login_btn">

    Login

</button>

<div class="form-links">

    <a href="forgotpassword.php">
        Forgot Password?
    </a>

</div>

        <div class="signup-link">

            <p>
                Don't have an account?
                <a href="register.php">Create Account</a>
            </p>

        </div>

    </form>

</section>

<footer>

        <div class="footer-content">
            <h3>Aurivah</h3>
            <p>© 2019-2026. All Rights Reserved.</p>
        </div>

    </footer>

</body>

</html>
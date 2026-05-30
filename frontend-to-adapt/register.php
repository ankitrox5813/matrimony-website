<!-- <?php

// include("includes/db.php"); //backend logic: runs first//

?> -->
<?php

include("includes/db.php");

if(isset($_POST['register_btn'])){

    $fullname = $_POST['fullname'];

    $email = $_POST['email'];

    $phone = $_POST['phone'];

    $password = $_POST['password'];

    $confirm_password = $_POST['confirm_password'];

    $gender = $_POST['gender'];

    $age = (int) ($_POST['age'] ?? 0);
    $date_of_birth = date('Y-m-d', strtotime('-' . $age . ' years'));

    // Password Match Check

    if($password != $confirm_password){

        die("Passwords Do Not Match");

    }

    // Encrypt Password

    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    // Insert Into Users Table

    $query1 = "INSERT INTO users
(fullname,email,phone,password,gender)

VALUES

('$fullname','$email','$phone','$hashed_password','$gender')";

    $result1 = mysqli_query($conn,$query1);

    if($result1){

        // Get Last Inserted User ID

        $user_id = mysqli_insert_id($conn);

        // Insert Into Profiles Table

        $query2 = "INSERT INTO profiles
        (user_id,date_of_birth)

        VALUES

        ('$user_id','$date_of_birth')";

        $result2 = mysqli_query($conn,$query2);

        // if($result2){

        //     echo "Registration Successful";

        // }else{

        //     echo "Profile Insert Failed";

        // }
        if($result2){

    echo "Registration Successful";

}else{

    die(mysqli_error($conn));

}

    }else{

        echo "Registration Failed";

    }

}

?>

<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - Aurivah</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">

</head>

<body>
    <?php include("includes/header.php"); ?>

<section class="form-section">

    <!-- <form class="auth-form"> -->
    <form class="auth-form" method="POST">
        <!-- without method_post php receives nothing -->

        <h2>Create Your Account</h2>

        <p class="form-subtitle">
            Join Aurivah and begin your trusted matchmaking journey.
        </p>

        <!-- <input type="text" placeholder="Full Name" required> -->
         <input type="text"
       name="fullname"
       placeholder="Full Name"
       required>

        <!-- <input type="email" placeholder="Email Address" required> -->
         <input type="email"
       name="email"
       placeholder="Email Address"
       required>

        <!-- <input type="tel" placeholder="Mobile Number" required> -->
         <input type="tel"
       name="phone"
       placeholder="Mobile Number"
       required>

        <!-- <input type="password" placeholder="Create Password" required> -->
         <input type="password"
       name="password"
       placeholder="Create Password"
       required>

        <!-- <input type="password" placeholder="Confirm Password" required> -->
         <input type="password"
       name="confirm_password"
       placeholder="Confirm Password"
       required>

        <!-- <select required>

            <option value="">
                Select Gender
            </option>

            <option>Male</option>

            <option>Female</option>

            <option>Other</option>

        </select> -->
        <select name="gender" required>

    <option value="">
        Select Gender
    </option>

    <option value="Male">Male</option>

    <option value="Female">Female</option>

    <option value="Other">Other</option>

</select>
 

        <input
            type="number"
            name="age"
            placeholder="Age"
            min="18"
            max="100"
            required
        >

        <div class="terms-box">

            <input type="checkbox" required>

            <label>
                I agree to the 
                <a href="usersagreement.php" target="_blank">
                    Terms & Conditions
                </a>
                and
                <a href="privacypolicy.php" target="_blank">
                    Privacy Policy
                </a>.
            </label>

        </div>

        <!-- <button type="submit"> -->
            <button type="submit"
        name="register_btn">
            Create Account
        </button>

        <div class="signup-link">

            <p>
                Already have an account?
                <a href="login.php">Login</a>
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
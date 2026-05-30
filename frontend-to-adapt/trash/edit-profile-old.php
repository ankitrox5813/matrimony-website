<?php

session_start();

include("../includes/db.php");


// =============================
// CHECK LOGIN
// =============================

if(!isset($_SESSION['user_id'])){

    header("Location: ../login.php");

    exit();

}


// =============================
// GET USER ID
// =============================

$user_id = $_SESSION['user_id'];


// =============================
// CHECK PROFILE EXISTS
// =============================

$checkProfile = "SELECT * FROM profiles
                 WHERE user_id='$user_id'";

$checkResult = mysqli_query(
    $conn,
    $checkProfile
);


// =============================
// IF PROFILE DOESN'T EXIST
// CREATE EMPTY PROFILE ROW
// =============================

if(mysqli_num_rows($checkResult) == 0){

    $createProfile = "INSERT INTO profiles(user_id)
                      VALUES('$user_id')";

    mysqli_query($conn,$createProfile);

}


// =============================
// FETCH PROFILE DATA
// =============================

$getProfile = "SELECT * FROM profiles
               WHERE user_id='$user_id'";

$profileResult = mysqli_query(
    $conn,
    $getProfile
);

$profile = mysqli_fetch_assoc(
    $profileResult
);


// =============================
// SAVE PROFILE
// =============================

if(isset($_POST['save_profile'])){

    $religion = $_POST['religion'];

    $caste = $_POST['caste'];

    $occupation = $_POST['occupation'];

    $income = $_POST['income'];

    $city = $_POST['city'];

    $state = $_POST['state'];

    $bio = $_POST['bio'];

    $photo_name = $_FILES['profile_photo']['name'];

$tmp_name = $_FILES['profile_photo']['tmp_name'];

move_uploaded_file(
    $tmp_name,
    "../uploads/".$photo_name
);


    $updateQuery = "UPDATE profiles SET

    religion='$religion',

    caste='$caste',

    occupation='$occupation',

    income='$income',

    city='$city',

    state='$state',

    bio='$bio',

    profile_photo='$photo_name'

    WHERE user_id='$user_id'";


    $updateResult = mysqli_query(
        $conn,
        $updateQuery
    );


    // if($updateResult){

    //     echo "Profile Saved Successfully";
    if($updateResult){

    // echo "Profile Updated";
    $success = "Profile Updated";

}else{

    die(mysqli_error($conn));

}

    }else{

        // echo "Profile Save Failed";
        $error = "Profile Save Failed";

    }


?>

<?php

if(!empty($profile['profile_photo'])){

?>

<img src="../uploads/<?php
echo $profile['profile_photo'];
?>"

width="120">

<?php } ?>


<?php

$query = "SELECT * FROM profiles
          WHERE user_id='$user_id'";

$result = mysqli_query($conn,$query);

$profile = mysqli_fetch_assoc($result);
?>

<?php

if(isset($success)){
    echo "<p style='color:green;'>$success</p>";
}

if(isset($error)){
    echo "<p style='color:red;'>$error</p>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Profile</title>

    <link rel="stylesheet"
          href="../css/style.css">

</head>

<body>

<?php include("../includes/header.php"); ?>

<section class="form-section">

<!-- <form class="auth-form"
      method="POST"> -->
     <form method="POST"
      enctype="multipart/form-data">
      

    <h2>Complete Your Profile</h2>

    <!-- <select name="religion" required>

        <option value="">
            Select Religion
        </option>

        <option value="Hindu">Hindu</option>

        <option value="Muslim">Muslim</option>

        <option value="Sikh">Sikh</option>

        <option value="Christian">Christian</option>

        <option value="Jain">Jain</option>

        <option value="Buddhisht">Buddhisht</option>

    </select> -->
    <select id="religion-select"
        name="religion"
        required>

    <option value="">
        Select Religion
    </option>

    <option value="Hindu"
<?php
if(isset($profile['religion']) &&
   $profile['religion']=="Hindu"){
    echo "selected";
}
?>>

Hindu

</option>

    <option value="Muslim"

<?php
if(($profile['religion'] ?? '')=="Muslim"){
    echo "selected";
}
?>

>

Muslim

</option>

   <option value="Christian"

<?php
if(($profile['religion'] ?? '')=="Christian"){
    echo "selected";
}
?>

>

Christian

</option>

   <option value="Sikh"

<?php
if(($profile['religion'] ?? '')=="Sikh"){
    echo "selected";
}
?>

>

Sikh

</option>

<option value="Jain"

<?php
if(($profile['religion'] ?? '')=="Jain"){
    echo "selected";
}
?>

>

Jain

</option>

<option value="Buddhisht"

<?php
if(($profile['religion'] ?? '')=="Buddhisht"){
    echo "selected";
}
?>

>

Buddhisht

</option>


</select>

    <!-- <input type="text"
           name="caste"
           placeholder="Community/Caste"> -->
           <select id="community-select"
        name="caste"
        required>

    <option value="">
        Select Community
    </option>

</select>

    <!-- <input type="text"
           name="occupation"
           placeholder="Occupation"> -->
           <select name="occupation" required>

    <option value="">
        Select Occupation
    </option>

    <option value="Government Job"
<?php
if($profile['occupation']=="Government Job"){
    echo "selected";
}
?>>

Government Job

</option>

    <option value="Business"
<?php
if($profile['occupation']=="Business"){
    echo "selected";
}
?>>

Business

</option>

<option value="Engineer"
<?php
if($profile['occupation']=="Engineer"){
    echo "selected";
}
?>>

Engineer

</option>

<option value="Doctor"
<?php
if($profile['occupation']=="Doctor"){
    echo "selected";
}
?>>

Doctor

</option>

<option value="Teacher"
<?php
if($profile['occupation']=="Teacher"){
    echo "selected";
}
?>>

Teacher

</option>

<option value="Lawyer"
<?php
if($profile['occupation']=="Lawyer"){
    echo "selected";
}
?>>

Lawyer

</option>

<option value="Freelancer"
<?php
if($profile['occupation']=="Freelancer"){
    echo "selected";
}
?>>

Freelancer

</option>

<option value="Aspirant"
<?php
if($profile['occupation']=="Aspirant"){
    echo "selected";
}
?>>

Aspirant

</option>

<option value="Not Working"
<?php
if($profile['occupation']=="Not Working"){
    echo "selected";
}
?>>

Not Working

</option>


</select>

<select name="income" required>

    <option value="">
        Select income
    </option>

    <option value="0-2 LPA"
<?php
if($profile['income']=="0-2 LPA"){
    echo "selected";
}
?>>

0-2 LPA

</option>

    <!-- <input type="text"
           name="income"
           placeholder="Annual Income"> -->

    <option value="2-5 LPA">
        2-5 LPA
    </option>

    <option value="5-10 LPA">
        5-10 LPA
    </option>

    <option value="10-20 LPA">
        10-20 LPA
    </option>

    <option value="20+ LPA">
        20+ LPA
    </option>

</select>

<!-- <input type="text"
           name="state"
           placeholder="State"> -->

           <select name="State" required>

    <option value="">
        Select State
    </option>

    <option value="Jharkhand"
<?php
if($profile['State']=="Jharkhand"){
    echo "selected";
}
?>>

Jharkhand

</option>


<option value="Andhra Pradesh">Andhra Pradesh</option>
<option value="Arunachal Pradesh">Arunachal Pradesh</option>
<option value="Assam">Assam</option>
<option value="Bihar">Bihar</option>
<option value="Chhattisgarh">Chhattisgarh</option>
<option value="Delhi">Delhi</option>
<option value="Goa">Goa</option>
<option value="Gujarat">Gujarat</option>
<option value="Haryana">Haryana</option>
<option value="Himachal Pradesh">Himachal Pradesh</option>
<!-- <option value="Jharkhand">Jharkhand</option> -->
<option value="Karnataka">Karnataka</option>
<option value="Kerala">Kerala</option>
<option value="Madhya Pradesh">Madhya Pradesh</option>
<option value="Maharashtra">Maharashtra</option>
<option value="Manipur">Manipur</option>
<option value="Mizoram">Mizoram</option>
<option value="Nagaland">Nagaland</option>
<option value="Odisha">Odisha</option>
<option value="Punjab">Punjab</option>
<option value="Sikkim">Sikkim</option>
<option value="Rajasthan">Rajasthan</option>
<option value="Tamil Nadu">Tamil Nadu</option>
<option value="Telangana">Telangana</option>
<option value="Tripura">Tripura</option>
<option value="Uttar Pradesh">Uttar Pradesh</option>
<option value="Uttarakhand">Uttarakhand</option>
<option value="West Bengal">West Bengal</option>

</select>

    <input type="text"
       name="city"
       placeholder="City"
       value="<?php echo $profile['city']; ?>">

       <input type="file"
       name="profile_photo">
       

    <!-- <textarea name="bio" -->
     <textarea name="bio"><?php echo $profile['bio']; ?></textarea>
<!-- placeholder="Write About Yourself">
 <?php
// echo $profile['bio'] ?? '';
?></textarea> -->

    <button type="submit"
            name="save_profile">

        Save Profile

    </button>

</form>

</section>

<script src="../js/app.js"></script>

<script>

const savedCommunity =
"<?php echo $profile['caste']; ?>";

</script>

</body>

</html>
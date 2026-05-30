<?php

session_start();

include("../includes/db.php");

if(!isset($_SESSION['user_id'])){

    header("Location: ../login.php");

    exit();

}

$user_id = $_SESSION['user_id'];

if(isset($_POST['upload_photo'])){

    $photo_name = $_FILES['profile_photo']['name'];

    $temp_name = $_FILES['profile_photo']['tmp_name'];

    $target_folder =
    "../uploads/" . $photo_name;

    // Move Image To Uploads Folder

    move_uploaded_file(
        $temp_name,
        $target_folder
    );

    // Save Filename In Database

    $updatePhoto = "UPDATE profiles SET

    profile_photo='$photo_name'

    WHERE user_id='$user_id'";

    $result = mysqli_query(
        $conn,
        $updatePhoto
    );

    if($result){

        echo "Photo Uploaded Successfully";

    }else{

        echo "Upload Failed";

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,
initial-scale=1.0">

<title>Upload Photo</title>

<link rel="stylesheet"
href="../css/style.css">

</head>

<body>

<?php include("../includes/header.php"); ?>

<section class="form-section">

<form class="auth-form"

method="POST"

enctype="multipart/form-data">
<!-- mandatory for file uploads. -->

<h2>Upload Profile Photo</h2>

<input type="file"

name="profile_photo"

accept="image/*"

required>

<button type="submit"

name="upload_photo">

Upload Photo

</button>

</form>

</section>

</body>

</html>
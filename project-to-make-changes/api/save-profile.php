<?php

session_start();

require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {

    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$profilePhoto = null;

if (
    isset($_FILES['profile_photo']) &&
    $_FILES['profile_photo']['error'] === 0
) {

    $extension = pathinfo(
        $_FILES['profile_photo']['name'],
        PATHINFO_EXTENSION
    );

    $fileName =
        uniqid('profile_', true)
        . '.'
        . $extension;

    $uploadPath =
        '../uploads/profiles/' .
        $fileName;

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array(strtolower($extension), $allowed)) {

        $_SESSION['error'] =
            'Only JPG, PNG and WEBP allowed';

        header('Location: ../create-profile.php');
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Verify it is actually an image
    |--------------------------------------------------------------------------
    */

    $imageInfo = getimagesize(
        $_FILES['profile_photo']['tmp_name']
    );

    if ($imageInfo === false) {

        $_SESSION['error'] =
            'Invalid image file';

        header('Location: ../create-profile.php');
        exit;
    }

    move_uploaded_file(
        $_FILES['profile_photo']['tmp_name'],
        $uploadPath
    );

    $profilePhoto =
        'uploads/profiles/' .
        $fileName;
}

$state = trim($_POST['state'] ?? '');
$city = trim($_POST['city'] ?? '');
$religion = trim($_POST['religion'] ?? '');
$caste = trim($_POST['caste'] ?? '');
$education = trim($_POST['education'] ?? '');
$occupation = trim($_POST['occupation'] ?? '');
$annual_income = trim($_POST['annual_income'] ?? '');
$height = trim($_POST['height'] ?? '');
$marital_status = trim($_POST['marital_status'] ?? '');
$about_me = trim($_POST['about_me'] ?? '');

// if (
//     empty($state) ||
//     empty($city) ||
//     empty($religion) ||
//     empty($caste) ||
//     empty($education) ||
//     empty($occupation)
// ) {

//     $_SESSION['error'] =
//         'Please fill all required fields';

//     header('Location: ../create-profile.php');

//     exit;
// }

if ($profilePhoto !== null) {

    $stmt = $conn->prepare(
        "UPDATE users
         SET profile_photo=?
         WHERE id=?"
    );

    $stmt->bind_param(
        "si",
        $profilePhoto,
        $user_id
    );

    $stmt->execute();
}

$stmt = $conn->prepare(
    "INSERT INTO user_profiles
    (
        user_id,
        state,
        city,
        religion,
        caste,
        education,
        occupation,
        annual_income,
        height,
        marital_status,
        about_me
    )
    VALUES
    (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )"
);

$stmt->bind_param(
    "issssssssss",
    $user_id,
    $state,
    $city,
    $religion,
    $caste,
    $education,
    $occupation,
    $annual_income,
    $height,
    $marital_status,
    $about_me
);

/*
|--------------------------------------------------------------------------
| Save Hobbies
|--------------------------------------------------------------------------
*/

if (!empty($_POST['hobbies'])) {

    $hobbies = $_POST['hobbies'];

    $hobbyStmt = $conn->prepare(
        "INSERT INTO user_hobbies
         (user_id, hobby_id)
         VALUES (?, ?)"
    );

    foreach ($hobbies as $hobbyId) {

        $hobbyStmt->bind_param(
            "ii",
            $user_id,
            $hobbyId
        );

        $hobbyStmt->execute();

    }
}

if ($stmt->execute()) {

    $_SESSION['success'] =
        'Profile created successfully';

    header(
        'Location: ../dashboard.php'
    );

    exit;
}



echo "Failed to save profile.";
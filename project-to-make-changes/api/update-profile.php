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

        header('Location: ../edit-profile.php');
        exit;
    }

    $imageInfo = getimagesize(
        $_FILES['profile_photo']['tmp_name']
    );

    if ($imageInfo === false) {

        $_SESSION['error'] =
            'Invalid image file';

        header('Location: ../edit-profile.php');
        exit;
    }

    move_uploaded_file(
        $_FILES['profile_photo']['tmp_name'],
        $uploadPath
    );

    $profilePhoto =
        'uploads/profiles/' .
        $fileName;

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

//     header('Location: ../edit-profile.php');
//     exit;
// }

$stmt = $conn->prepare(
    "UPDATE user_profiles
     SET
        state=?,
        city=?,
        religion=?,
        caste=?,
        education=?,
        occupation=?,
        annual_income=?,
        height=?,
        marital_status=?,
        about_me=?
     WHERE user_id=?"
);

$stmt->bind_param(
    "ssssssssssi",
    $state,
    $city,
    $religion,
    $caste,
    $education,
    $occupation,
    $annual_income,
    $height,
    $marital_status,
    $about_me,
    $user_id
);



if ($stmt->execute()) {

    /*
    |--------------------------------------------------------------------------
    | Update Hobbies
    |--------------------------------------------------------------------------
    */

    $deleteStmt = $conn->prepare(
        "DELETE FROM user_hobbies
     WHERE user_id = ?"
    );

    $deleteStmt->bind_param(
        "i",
        $user_id
    );

    $deleteStmt->execute();

    if (!empty($_POST['hobbies'])) {

        $hobbyStmt = $conn->prepare(
            "INSERT INTO user_hobbies
         (user_id, hobby_id)
         VALUES (?, ?)"
        );

        foreach ($_POST['hobbies'] as $hobbyId) {

            $hobbyStmt->bind_param(
                "ii",
                $user_id,
                $hobbyId
            );

            $hobbyStmt->execute();

        }
    }

    $_SESSION['success'] =
        'Profile updated successfully';

    header('Location: ../view-profile.php');
    exit;
}



echo "Profile update failed";
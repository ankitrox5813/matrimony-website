<?php

session_start();

require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {

    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| FORM DATA
|--------------------------------------------------------------------------
*/

$min_age = !empty($_POST['min_age'])
    ? (int)$_POST['min_age']
    : null;

$max_age = !empty($_POST['max_age'])
    ? (int)$_POST['max_age']
    : null;

$religions = json_encode(
    $_POST['religions'] ?? []
);

$castes = json_encode(
    $_POST['castes'] ?? []
);

$states = json_encode(
    $_POST['states'] ?? []
);

$cities = json_encode(
    $_POST['cities'] ?? []
);

$educations = json_encode(
    $_POST['educations'] ?? []
);

$marital_statuses = json_encode(
    $_POST['marital_statuses'] ?? []
);

$hobbies = json_encode(
    $_POST['hobbies'] ?? []
);

/*
|--------------------------------------------------------------------------
| CHECK IF RECORD EXISTS
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT id
     FROM partner_preferences
     WHERE user_id = ?"
);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

$exists = $result->num_rows > 0;

/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

if ($exists) {

    $stmt = $conn->prepare(
        "UPDATE partner_preferences
         SET
            min_age=?,
            max_age=?,
            religions=?,
            castes=?,
            states=?,
            cities=?,
            educations=?,
            marital_statuses=?,
            hobbies=?
         WHERE user_id=?"
    );

    $stmt->bind_param(
        "iisssssssi",
        $min_age,
        $max_age,
        $religions,
        $castes,
        $states,
        $cities,
        $educations,
        $marital_statuses,
        $hobbies,
        $user_id
    );

} else {

    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare(
        "INSERT INTO partner_preferences
        (
            user_id,
            min_age,
            max_age,
            religions,
            castes,
            states,
            cities,
            educations,
            marital_statuses,
            hobbies
        )
        VALUES
        (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )"
    );

    $stmt->bind_param(
        "iiisssssss",
        $user_id,
        $min_age,
        $max_age,
        $religions,
        $castes,
        $states,
        $cities,
        $educations,
        $marital_statuses,
        $hobbies
    );
}

/*
|--------------------------------------------------------------------------
| SAVE
|--------------------------------------------------------------------------
*/

if ($stmt->execute()) {

    $_SESSION['success'] =
        'Partner preferences saved successfully';

    header(
        'Location: ../partner-preferences.php'
    );

    exit;
}

$_SESSION['error'] =
    'Failed to save preferences';

header(
    'Location: ../partner-preferences.php'
);

exit;
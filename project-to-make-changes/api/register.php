<?php

session_start();

require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: ../register.php');
    exit;
}

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$gender = trim($_POST['gender'] ?? '');
$age = (int)($_POST['age'] ?? 0);


/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

if (
    empty($full_name) ||
    empty($email) ||
    empty($mobile) ||
    empty($password) ||
    empty($confirm_password) ||
    empty($gender)
) {

    $_SESSION['error'] = 'All fields are required.';
header('Location: ../register.php');
exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION['error'] = 'Invalid email address.';
header('Location: ../register.php');
exit;
}

if (!preg_match('/^[0-9]{10}$/', $mobile)) {

    $_SESSION['error'] = 'Invalid mobile number.';
header('Location: ../register.php');
exit;
}

if ($age < 18) {

    $_SESSION['error'] = 'Age must be at least 18.';
header('Location: ../register.php');
exit;
}

if ($password !== $confirm_password) {

    $_SESSION['error'] = 'Passwords do not match.';
header('Location: ../register.php');
exit;
}

if (strlen($password) < 6) {

    $_SESSION['error'] = 'Password must be at least 6 characters.';
    header('Location: ../register.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Duplicate Email Check
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT id FROM users WHERE email = ?"
);

$stmt->bind_param(
    "s",
    $email
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $_SESSION['error'] = 'Email already registered.';
header('Location: ../register.php');
exit;
}


/*
|--------------------------------------------------------------------------
| Duplicate Mobile Check
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT id FROM users WHERE mobile = ?"
);

$stmt->bind_param(
    "s",
    $mobile
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $_SESSION['error'] = 'Mobile number already registered.';
header('Location: ../register.php');
exit;
}


/*
|--------------------------------------------------------------------------
| Password Hash
|--------------------------------------------------------------------------
*/

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);


/*
|--------------------------------------------------------------------------
| Insert User
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "INSERT INTO users
    (
        full_name,
        email,
        mobile,
        password,
        gender,
        age
    )
    VALUES
    (
        ?, ?, ?, ?, ?, ?
    )"
);

$stmt->bind_param(
    "sssssi",
    $full_name,
    $email,
    $mobile,
    $hashedPassword,
    $gender,
    $age
);

if ($stmt->execute()) {

    $_SESSION['success'] =
        "Registration successful. Please login.";

    header(
        "Location: ../login.php"
    );

    exit;
}

$_SESSION['error'] = 'Registration failed. Please try again.';
header('Location: ../register.php');
exit;
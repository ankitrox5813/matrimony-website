<?php

session_start();

require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: ../login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {

    $_SESSION['error'] =
        'Email and password are required';

    header('Location: ../login.php');
    exit;
}

$stmt = $conn->prepare(
    "SELECT *
     FROM users
     WHERE email = ?"
);

$stmt->bind_param(
    "s",
    $email
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {

    $_SESSION['error'] =
        'Invalid email or password';

    header('Location: ../login.php');
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify(
    $password,
    $user['password']
)) {

    $_SESSION['error'] =
        'Invalid email or password';

    header('Location: ../login.php');
    exit;
}

if ($user['status'] === 'Blocked') {

    $_SESSION['error'] =
        'Account blocked';

    header('Location: ../login.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Create Session
|--------------------------------------------------------------------------
*/

$_SESSION['user_id'] =
    $user['id'];

$_SESSION['user_name'] =
    $user['full_name'];

$_SESSION['user_email'] =
    $user['email'];

header(
    'Location: ../dashboard.php'
);

exit;
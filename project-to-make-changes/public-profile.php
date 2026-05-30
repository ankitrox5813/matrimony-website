<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/config.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare(
    "SELECT
        u.full_name,
        u.profile_photo,
        u.gender,
        u.age,
        p.*
     FROM users u
     INNER JOIN user_profiles p
        ON u.id = p.user_id
     WHERE u.id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if (!$user) {

    $_SESSION['error'] =
        'Profile not found.';

    header(
        'Location: profiles.php'
    );

    exit;
}

$photo =
    !empty($user['profile_photo'])
    ? $user['profile_photo']
    : 'assets/images/default-user.png';

$pageTitle = "Profile";

include 'includes/header.php';
?>

<div class="profile-page">

    <h1>
        <?= htmlspecialchars($user['full_name']) ?>
    </h1>

    <img
        src="<?= htmlspecialchars($photo) ?>"
        alt="Profile Photo"
        class="profile-photo"
    >

    <div class="profile-details">

        <div class="detail-box">
            <strong>Gender</strong>
            <?= htmlspecialchars($user['gender']) ?>
        </div>

        <div class="detail-box">
            <strong>Age</strong>
            <?= htmlspecialchars($user['age']) ?>
        </div>

        <div class="detail-box">
            <strong>State</strong>
            <?= htmlspecialchars($user['state']) ?>
        </div>

        <div class="detail-box">
            <strong>City</strong>
            <?= htmlspecialchars($user['city']) ?>
        </div>

        <div class="detail-box">
            <strong>Education</strong>
            <?= htmlspecialchars($user['education']) ?>
        </div>

        <div class="detail-box">
            <strong>Occupation</strong>
            <?= htmlspecialchars($user['occupation']) ?>
        </div>

        <div class="detail-box">
            <strong>Annual Income</strong>
            <?= htmlspecialchars($user['annual_income']) ?>
        </div>

        <div class="detail-box">
            <strong>Height</strong>
            <?= htmlspecialchars($user['height']) ?>
        </div>

        <div class="detail-box">
            <strong>Marital Status</strong>
            <?= htmlspecialchars($user['marital_status']) ?>
        </div>

    </div>

    <div class="detail-box about-box">

        <strong>About Me</strong>

        <p>
            <?= nl2br(htmlspecialchars($user['about_me'])) ?>
        </p>

    </div>

</div>

<?php include 'includes/footer.php'; ?>
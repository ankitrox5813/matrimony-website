<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/config.php';

$user_id = $_SESSION['user_id'];



$stmt = $conn->prepare(
    "SELECT u.*, p.*
     FROM users u
     LEFT JOIN user_profiles p
     ON u.id = p.user_id
     WHERE u.id = ?"
);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

$hobbyStmt = $conn->prepare(
    "SELECT h.name
     FROM hobbies h
     JOIN user_hobbies uh
     ON h.id = uh.hobby_id
     WHERE uh.user_id = ?"
);

$hobbyStmt->bind_param(
    "i",
    $user_id
);

$hobbyStmt->execute();

$hobbyResult =
    $hobbyStmt->get_result();

$hobbies = [];

while (
    $row = $hobbyResult->fetch_assoc()
) {

    $hobbies[] = $row['name'];

}

$pageTitle = "My Profile";

include 'includes/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>

    <div class="alert-success">
        <?= $_SESSION['success']; ?>
    </div>

    <?php
    unset($_SESSION['success']);
endif;
?>

<div class="profile-page">

    <h1>My Profile</h1>

    <hr><br>

    <?php

    $photo =
        !empty($user['profile_photo'])
        ? $user['profile_photo']
        : 'assets/images/default-user.png';

    ?>

    <img src="<?= htmlspecialchars($photo) ?>" alt="Profile Photo" class="profile-photo">

    <div class="profile-details">

        <div class="detail-box">
            <strong>Name</strong><br>
            <?= htmlspecialchars($user['full_name']) ?>
        </div>

        <div class="detail-box">
            <strong>Email</strong><br>
            <?= htmlspecialchars($user['email']) ?>
        </div>

        <div class="detail-box">
            <strong>Gender</strong><br>
            <?= htmlspecialchars($user['gender']) ?>
        </div>

        <div class="detail-box">
            <strong>Age</strong><br>
            <?= htmlspecialchars($user['age']) ?>
        </div>

        <div class="detail-box">
            <strong>Religion</strong><br>
            <?= htmlspecialchars($user['religion'] ?? '') ?>
        </div>

        <div class="detail-box">
            <strong>Caste</strong><br>
            <?= htmlspecialchars($user['caste'] ?? '') ?>
        </div>

        <div class="detail-box">
            <strong>State</strong><br>
            <?= htmlspecialchars($user['state'] ?? '') ?>
        </div>

        <div class="detail-box">
            <strong>City</strong><br>
            <?= htmlspecialchars($user['city'] ?? '') ?>
        </div>

        <div class="detail-box">
            <strong>Education</strong><br>
            <?= htmlspecialchars($user['education'] ?? '') ?>
        </div>

        <div class="detail-box">
            <strong>Occupation</strong><br>
            <?= htmlspecialchars($user['occupation'] ?? '') ?>
        </div>

        <div class="detail-box">
            <strong>Income</strong><br>
            <?= htmlspecialchars($user['annual_income'] ?? '') ?>
        </div>

        <div class="detail-box">
            <strong>Height</strong><br>
            <?= htmlspecialchars($user['height'] ?? '') ?>
        </div>

        <div class="detail-box">
            <strong>Marital Status</strong><br>
            <?= htmlspecialchars($user['marital_status'] ?? '') ?>
        </div>

        <div class="detail-box hobbies-box">

            <strong>Hobbies</strong><br>

            <div class="hobby-tags">

                <?php foreach ($hobbies as $hobby): ?>

                    <span class="hobby-tag">
                        <?= htmlspecialchars($hobby) ?>
                    </span>

                <?php endforeach; ?>

            </div>

        </div>

    </div>

    <div class="detail-box about-box">

        <strong>About Me</strong>

        <p>
            <?= nl2br(htmlspecialchars($user['about_me'])) ?>
        </p>

    </div>

    <div class="profile-actions">

        <a href="edit-profile.php" class="btn-primary">
            Edit Profile
        </a>

    </div>

</div>

<?php include 'includes/footer.php'; ?>
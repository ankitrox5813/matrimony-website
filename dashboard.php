<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header('Location: login.php');
    exit;
}

require_once 'includes/config.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT id FROM user_profiles WHERE user_id = ?"
);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$profileExists = $result->num_rows > 0;

$stmt = $conn->prepare(
    "SELECT profile_photo
     FROM users
     WHERE id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();



$photo =
    !empty($user['profile_photo'])
    ? $user['profile_photo']
    : 'assets/images/default-user.png';

$pageTitle = "Dashboard";

include 'includes/header.php';
?>

<div style="
max-width:1200px;
margin:50px auto;
padding:20px;
">

    <h1>
        Welcome,
        <?= htmlspecialchars($_SESSION['user_name']) ?>
    </h1>
    <img src="<?= $photo ?>" style="
        width:100px;
        height:100px;
        border-radius:50%;
        object-fit:cover;
    ">
    <p>
        Email:
        <?= htmlspecialchars($_SESSION['user_email']) ?>
    </p>

    <br>

    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
        gap:20px;
        margin-top:30px;
    ">

        <div style="
            background:#fff;
            padding:25px;
            border-radius:10px;
            box-shadow:0 2px 10px rgba(0,0,0,.1);
        ">
            <h3>Complete Profile</h3>

            <p>
                Add education, occupation,
                religion and preferences.
            </p>

            <br>

            <?php if (!$profileExists): ?>

                <a href="create-profile.php" class="btn-primary">
                    Complete Profile
                </a>

                        <?php else: ?>

                <a href="view-profile.php" class="btn-primary">
                    View Profile
                </a>

                        <?php endif; ?>

        </div>

        <div style="
        background:#fff;
        padding:25px;
        border-radius:10px;
        box-shadow:0 2px 10px rgba(0,0,0,.1);
        ">
            <h3>My Profile</h3>

            <p>
                View your matrimonial profile.
            </p>

            <br>

            <a href="view-profile.php" class="btn-primary">
                View Profile
            </a>

        </div>

        <div style="
        background:#fff;
        padding:25px;
        border-radius:10px;
        box-shadow:0 2px 10px rgba(0,0,0,.1);
        ">
            <h3>Search Matches</h3>

            <p>
                Find suitable matches.
            </p>

            <br>

            <a href="profiles.php" class="btn-primary">
                Search
            </a>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>
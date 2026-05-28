<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header('Location: login.php');
    exit;
}

require_once 'includes/config.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT *
     FROM user_profiles
     WHERE user_id = ?"
);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$profile = $result->fetch_assoc();

$profileExists = !empty($profile);

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

/*
|--------------------------------------------------------------------------
| PROFILE COMPLETION
|--------------------------------------------------------------------------
*/

$completion = 0;

if ($profileExists) {

    $fields = [

        $user['profile_photo'] ?? '',
        $profile['state'] ?? '',
        $profile['city'] ?? '',
        $profile['religion'] ?? '',
        $profile['caste'] ?? '',
        $profile['education'] ?? '',
        $profile['occupation'] ?? '',
        $profile['annual_income'] ?? '',
        $profile['height'] ?? '',
        $profile['marital_status'] ?? '',
        $profile['about_me'] ?? ''

    ];

    $filled = 0;

    foreach ($fields as $field) {

        if (!empty(trim($field))) {

            $filled++;

        }

    }

    $completion =
        round(
            ($filled / count($fields)) * 100
        );
}



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
        width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            object-position: center top;
            /* display: block; */
            margin: 0 auto 25px;
            border: 4px solid #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .1);
    ">
    <p>
        Email:
        <?= htmlspecialchars($_SESSION['user_email']) ?>
    </p>

    <br>

    <div style="
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 4px 15px rgba(0,0,0,.08);
">

    <h3>
        Profile Completion
    </h3>

    <br>

    <div style="
        width:100%;
        height:14px;
        background:#eee;
        border-radius:30px;
        overflow:hidden;
    ">

        <div style="
            width:<?= $completion ?>%;
            height:100%;
            background:#e91e63;
        "></div>

    </div>

    <br>

    <p style="
        font-size:18px;
        font-weight:bold;
        color:#e91e63;
    ">
        <?= $completion ?>% Complete
    </p>

    <br>

    <?php if ($completion < 100): ?>

        <a href="
        <?= $profileExists
            ? 'edit-profile.php'
            : 'create-profile.php'
        ?>
        " class="btn-primary">

            Complete Now

        </a>

    <?php else: ?>

        <p style="color:green;font-weight:600;">
            Your profile is complete
        </p>

    <?php endif; ?>

</div>

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
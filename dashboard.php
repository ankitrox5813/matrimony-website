<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header('Location: login.php');
    exit;
}

require_once 'includes/config.php';

require_once 'includes/match-engine.php';

require_once 'includes/recommendation-engine.php';

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

        if (!empty($field)) {
            $filled++;
        }
    }

    $completion = round(
        ($filled / count($fields)) * 100
    );
}

/*
|--------------------------------------------------------------------------
| PARTNER PREFERENCES
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT id
     FROM partner_preferences
     WHERE user_id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$preferencesExist =
    $stmt->get_result()->num_rows > 0;

/*
|--------------------------------------------------------------------------
| MATCH COUNT
|--------------------------------------------------------------------------
*/

$allMatches = [];

if ($preferencesExist && $profileExists) {

    $allMatches = getRecommendedMatches(
        $conn,
        $user_id,
        []
    );
}

$matchCount = count($allMatches);
$topMatches = array_slice($allMatches, 0, 3);

$pageTitle = "Dashboard";

include 'includes/header.php';

$initials = '';

if (!empty($_SESSION['user_name'])) {
    $parts = preg_split('/\s+/', trim($_SESSION['user_name']));
    $initials = strtoupper(substr($parts[0], 0, 1));
    if (isset($parts[1])) {
        $initials .= strtoupper(substr($parts[1], 0, 1));
    }
}
?>

<section class="dashboard-section">
    <div class="dashboard-inner">

        <div class="dash-welcome">
            <div class="dash-welcome-text">
                <h1>Welcome, <span><?= htmlspecialchars($_SESSION['user_name']) ?></span></h1>
                <p><?= htmlspecialchars($_SESSION['user_email']) ?></p>
            </div>
            <div class="dash-avatar">
                <?php if (!empty($user['profile_photo'])): ?>
                    <img src="<?= htmlspecialchars($photo) ?>" alt="Profile photo">
                <?php else: ?>
                    <?= htmlspecialchars($initials ?: 'A') ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="completion-card">
            <div class="completion-header">
                <span>Profile completion</span>
                <strong><?= (int) $completion ?>%</strong>
            </div>
            <div class="completion-bar">
                <div class="completion-fill" style="width: <?= (int) $completion ?>%;"></div>
            </div>
            <?php if ($completion < 100): ?>
                <p class="completion-tip">Complete your profile to get better match recommendations.</p>
            <?php else: ?>
                <p class="completion-tip" style="color: #166534;">Your profile is complete.</p>
            <?php endif; ?>
        </div>

        <div class="dash-stats">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-label">Profile status</div>
                <div class="stat-value"><?= (int) $completion ?>%</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⚙️</div>
                <div class="stat-label">Preferences set</div>
                <div class="stat-value"><?= $preferencesExist ? 'Yes' : 'No' ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💞</div>
                <div class="stat-label">Recommended matches</div>
                <div class="stat-value"><?= (int) $matchCount ?></div>
            </div>
        </div>

        <?php if ($completion < 100): ?>
            <p style="margin-bottom: 28px;">
                <a href="<?= $profileExists ? 'edit-profile.php' : 'create-profile.php' ?>" class="btn-primary">
                    Complete profile
                </a>
            </p>
        <?php endif; ?>

        <h2 class="dash-actions-title">Quick actions</h2>
        <div class="dash-grid">

            <?php if (!$profileExists): ?>
                <a href="create-profile.php" class="dash-card">
                    <div class="dash-card-icon">📝</div>
                    <div class="dash-card-title">Complete profile</div>
                    <div class="dash-card-desc">Add education, occupation, religion and other details.</div>
                    <span class="dash-card-arrow">Get started →</span>
                </a>
            <?php else: ?>
                <a href="view-profile.php" class="dash-card">
                    <div class="dash-card-icon">👤</div>
                    <div class="dash-card-title">My profile</div>
                    <div class="dash-card-desc">View and manage your matrimonial profile.</div>
                    <span class="dash-card-arrow">View profile →</span>
                </a>
            <?php endif; ?>

            <a href="partner-preferences.php" class="dash-card">
                <div class="dash-card-icon">🎯</div>
                <div class="dash-card-title">Partner preferences</div>
                <div class="dash-card-desc">Set religion, location and other partner criteria.</div>
                <span class="dash-card-arrow">Edit preferences →</span>
            </a>

            <a href="preferred-profiles.php" class="dash-card">
                <div class="dash-card-icon">✨</div>
                <div class="dash-card-title">Recommended matches</div>
                <div class="dash-card-desc">Profiles matched to your partner preferences.</div>
                <span class="dash-card-arrow">View matches →</span>
            </a>

            <a href="profiles.php" class="dash-card">
                <div class="dash-card-icon">🔍</div>
                <div class="dash-card-title">Browse profiles</div>
                <div class="dash-card-desc">Search all profiles with advanced filters.</div>
                <span class="dash-card-arrow">Search →</span>
            </a>

        </div>

        <div class="completion-card" style="margin-top: 40px;">
            <div class="completion-header">
                <span>Top recommended matches</span>
            </div>

            <?php if (!empty($topMatches)): ?>

                <?php foreach ($topMatches as $match): ?>
                    <div class="dash-match-row">
                        <div>
                            <strong><?= htmlspecialchars($match['full_name']) ?></strong>
                            <br>
                            <small style="color: var(--clr-muted);">
                                <?= htmlspecialchars($match['city']) ?>,
                                <?= htmlspecialchars($match['state']) ?>
                            </small>
                        </div>
                        <div class="dash-match-score"><?= (int) $match['match_score'] ?>%</div>
                    </div>
                <?php endforeach; ?>

                <p style="margin-top: 20px;">
                    <a href="preferred-profiles.php" class="btn-primary">View all matches</a>
                </p>

            <?php else: ?>
                <p class="completion-tip">No recommended matches yet. Set partner preferences to see matches.</p>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>

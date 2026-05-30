<?php
 
session_start();
 
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}
 
// Load profile photo & completion data
include("../includes/db.php");
 
$user_id = $_SESSION['user_id'];
$query   = "SELECT * FROM profiles WHERE user_id='$user_id'";
$result  = mysqli_query($conn, $query);
$profile = mysqli_fetch_assoc($result);
 
// Calculate profile completion %
$fields   = ['religion','caste','occupation','income','city','state','bio','profile_photo'];
$filled   = 0;
foreach($fields as $f){ if(!empty($profile[$f])) $filled++; }
$completion = round(($filled / count($fields)) * 100);
 
// Initials for avatar
$name_parts = explode(' ', trim($_SESSION['fullname']));
$initials   = strtoupper(substr($name_parts[0],0,1));
if(count($name_parts) > 1) $initials .= strtoupper(substr(end($name_parts),0,1));
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aurivah</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
 
<?php include("../includes/header.php"); ?>
 
<section class="dashboard-section">
<div class="dashboard-inner">
 
    <!-- Welcome banner -->
    <div class="dash-welcome">
        <div class="dash-welcome-text">
            <h1>Welcome back, <span><?php echo htmlspecialchars($_SESSION['fullname']); ?></span></h1>
            <p>Manage your Aurivah profile and discover your perfect match.</p>
        </div>
        <div class="dash-avatar">
            <?php if(!empty($profile['profile_photo'])): ?>
                <img src="../uploads/<?php echo htmlspecialchars($profile['profile_photo']); ?>" alt="Profile Photo">
            <?php else: ?>
                <?php echo $initials; ?>
            <?php endif; ?>
        </div>
    </div>
 
    <!-- Stat cards -->
    <div class="dash-stats">
        <div class="stat-card">
            <div class="stat-icon">👁️</div>
            <div class="stat-label">Profile Views</div>
            <div class="stat-value">—</div>
            <div class="stat-sub">Coming soon</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💌</div>
            <div class="stat-label">Interests</div>
            <div class="stat-value">—</div>
            <div class="stat-sub">Coming soon</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⭐</div>
            <div class="stat-label">Shortlisted</div>
            <div class="stat-value">—</div>
            <div class="stat-sub">Coming soon</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-label">Completion</div>
            <div class="stat-value"><?php echo $completion; ?>%</div>
            <div class="stat-sub">Profile strength</div>
        </div>
    </div>
 
    <!-- Profile completion bar -->
    <div class="completion-card">
        <div class="completion-header">
            <span>Profile Strength</span>
            <strong><?php echo $completion; ?>% Complete</strong>
        </div>
        <div class="completion-bar">
            <div class="completion-fill" style="width: <?php echo $completion; ?>%;"></div>
        </div>
        <?php if($completion < 100): ?>
        <p class="completion-tip">
            ✨ Complete your profile to get better match recommendations.
            <a href="edit-profile.php" style="color:var(--clr-primary);font-weight:600;">Fill now →</a>
        </p>
        <?php else: ?>
        <p class="completion-tip">🎉 Your profile is complete! You're getting maximum visibility.</p>
        <?php endif; ?>
    </div>
 
    <!-- Action cards -->
    <div class="dash-actions-title">Quick Actions</div>
 
    <div class="dash-grid">
 
        <a href="edit-profile.php" class="dash-card">
            <div class="dash-card-icon">✏️</div>
            <div class="dash-card-title">Edit Profile</div>
            <div class="dash-card-desc">Update your religion, occupation, location, bio, and profile photo.</div>
            <div class="dash-card-arrow">→</div>
        </a>
 
        <a href="view-profile.php" class="dash-card">
            <div class="dash-card-icon">👤</div>
            <div class="dash-card-title">View Profile</div>
            <div class="dash-card-desc">See how your profile appears to other members on Aurivah.</div>
            <div class="dash-card-arrow">→</div>
        </a>
 
        <a href="../profiles.php" class="dash-card">
            <div class="dash-card-icon">🔍</div>
            <div class="dash-card-title">Browse Matches</div>
            <div class="dash-card-desc">Explore recommended profiles matching your preferences.</div>
            <div class="dash-card-arrow">→</div>
        </a>
 
        <a href="logout.php" class="dash-card danger">
            <div class="dash-card-icon">🚪</div>
            <div class="dash-card-title">Logout</div>
            <div class="dash-card-desc">Sign out of your Aurivah account safely.</div>
            <div class="dash-card-arrow">→</div>
        </a>
 
    </div>
 
</div>
</section>
 
<footer>
    <div class="footer-content">
        <div>
            <h3>Aurivah</h3>
            <p>© 2019-2026. All Rights Reserved.</p>
        </div>
    </div>
</footer>
 
</body>
</html>
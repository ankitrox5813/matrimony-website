<?php
 
session_start();
include("../includes/db.php");
 
// LOGIN CHECK
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}
 
$user_id = $_SESSION['user_id'];
 
// Load user + profile data
$userQuery = "SELECT * FROM users WHERE id='$user_id'";
$userResult = mysqli_query($conn, $userQuery);
$user = mysqli_fetch_assoc($userResult);
 
$profileQuery = "SELECT * FROM profiles WHERE user_id='$user_id'";
$profileResult = mysqli_query($conn, $profileQuery);
$profile = mysqli_fetch_assoc($profileResult);
 
// Profile completion
$fields     = ['religion','caste','occupation','income','city','state','bio','profile_photo'];
$filled     = 0;
foreach($fields as $f){ if(!empty($profile[$f])) $filled++; }
$completion = round(($filled / count($fields)) * 100);
 
// Initials
$name_parts = explode(' ', trim($_SESSION['fullname']));
$initials   = strtoupper(substr($name_parts[0],0,1));
if(count($name_parts) > 1) $initials .= strtoupper(substr(end($name_parts),0,1));
 
// Helper: display value or dash
function val($v, $fallback = '—'){ return !empty($v) ? htmlspecialchars($v) : $fallback; }
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Aurivah</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
 
<?php include("../includes/header.php"); ?>
 
<section class="view-profile-section">
<div class="view-profile-inner">
 
    <a href="index.php" class="back-link">← Back to Dashboard</a>
 
    <!-- Incomplete profile nudge -->
    <?php if($completion < 100): ?>
    <div class="profile-nudge">
        ⚡ Your profile is <?php echo $completion; ?>% complete.
        <a href="edit-profile.php" style="color:#92400e;font-weight:700;margin-left:4px;">Complete it now →</a>
    </div>
    <?php endif; ?>
 
    <!-- Action buttons -->
    <div class="profile-actions">
        <a href="edit-profile.php" class="btn-edit">✏️ Edit Profile</a>
        <a href="index.php" class="btn-back">← Dashboard</a>
    </div>
 
    <!-- Hero card -->
    <div class="profile-hero-card">
        <div class="profile-hero-banner"></div>
        <div class="profile-hero-body">
 
            <div class="profile-photo-wrap">
                <?php if(!empty($profile['profile_photo'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($profile['profile_photo']); ?>"
                         alt="Profile Photo">
                <?php else: ?>
                    <div class="profile-photo-initials"><?php echo $initials; ?></div>
                <?php endif; ?>
            </div>
 
            <div class="profile-name"><?php echo htmlspecialchars($_SESSION['fullname']); ?></div>
 
            <!-- Meta tags -->
            <div class="profile-meta-tags">
                <?php if(!empty($profile['religion'])): ?>
                    <span class="meta-tag">🕉️ <?php echo val($profile['religion']); ?></span>
                <?php endif; ?>
                <?php if(!empty($profile['caste'])): ?>
                    <span class="meta-tag">👪 <?php echo val($profile['caste']); ?></span>
                <?php endif; ?>
                <?php if(!empty($profile['city']) || !empty($profile['state'])): ?>
                    <span class="meta-tag">📍 <?php echo val($profile['city']); ?><?php echo (!empty($profile['city']) && !empty($profile['state'])) ? ', ' : ''; ?><?php echo val($profile['state']); ?></span>
                <?php endif; ?>
                <?php if(!empty($profile['occupation'])): ?>
                    <span class="meta-tag">💼 <?php echo val($profile['occupation']); ?></span>
                <?php endif; ?>
            </div>
 
            <!-- Bio -->
            <?php if(!empty($profile['bio'])): ?>
                <div class="profile-bio"><?php echo nl2br(htmlspecialchars($profile['bio'])); ?></div>
            <?php else: ?>
                <div class="profile-bio empty">No bio added yet. <a href="edit-profile.php" style="color:var(--clr-primary);font-weight:600;">Add one →</a></div>
            <?php endif; ?>
 
        </div>
    </div><!-- /.profile-hero-card -->
 
    <!-- Detail cards grid -->
    <div class="profile-details-grid">
 
        <!-- Personal info -->
        <div class="detail-card">
            <div class="detail-card-title">👤 Personal Information</div>
            <div class="detail-row">
                <span class="detail-key">Full Name</span>
                <span class="detail-val"><?php echo htmlspecialchars($_SESSION['fullname']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-key">Religion</span>
                <span class="detail-val <?php echo empty($profile['religion']) ? 'empty' : ''; ?>">
                    <?php echo val($profile['religion']); ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-key">Community</span>
                <span class="detail-val <?php echo empty($profile['caste']) ? 'empty' : ''; ?>">
                    <?php echo val($profile['caste']); ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-key">Date of Birth</span>
                <span class="detail-val <?php echo empty($profile['date_of_birth']) ? 'empty' : ''; ?>">
                    <?php echo !empty($profile['date_of_birth']) ? date('d M Y', strtotime($profile['date_of_birth'])) : '—'; ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-key">Gender</span>
                <span class="detail-val <?php echo empty($user['gender']) ? 'empty' : ''; ?>">
                    <?php echo val($user['gender']); ?>
                </span>
            </div>
        </div>
 
        <!-- Professional info -->
        <div class="detail-card">
            <div class="detail-card-title">💼 Professional Details</div>
            <div class="detail-row">
                <span class="detail-key">Occupation</span>
                <span class="detail-val <?php echo empty($profile['occupation']) ? 'empty' : ''; ?>">
                    <?php echo val($profile['occupation']); ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-key">Annual Income</span>
                <span class="detail-val <?php echo empty($profile['income']) ? 'empty' : ''; ?>">
                    <?php echo val($profile['income']); ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-key">City</span>
                <span class="detail-val <?php echo empty($profile['city']) ? 'empty' : ''; ?>">
                    <?php echo val($profile['city']); ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-key">State</span>
                <span class="detail-val <?php echo empty($profile['state']) ? 'empty' : ''; ?>">
                    <?php echo val($profile['state']); ?>
                </span>
            </div>
        </div>
 
        <!-- Contact info -->
        <div class="detail-card">
            <div class="detail-card-title">📞 Contact Information</div>
            <div class="detail-row">
                <span class="detail-key">Email</span>
                <span class="detail-val"><?php echo val($user['email']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-key">Phone</span>
                <span class="detail-val <?php echo empty($user['phone']) ? 'empty' : ''; ?>">
                    <?php echo val($user['phone']); ?>
                </span>
            </div>
        </div>
 
        <!-- Profile strength -->
        <div class="detail-card">
            <div class="detail-card-title">📊 Profile Strength</div>
            <div class="detail-row">
                <span class="detail-key">Completion</span>
                <span class="detail-val" style="color:var(--crimson-700);font-family:var(--font-display);font-size:1.1rem;">
                    <?php echo $completion; ?>%
                </span>
            </div>
            <div style="margin: 10px 0;">
                <div class="completion-bar" style="height:6px;">
                    <div class="completion-fill" style="width:<?php echo $completion; ?>%;"></div>
                </div>
            </div>
            <?php
            $missing = [];
            foreach($fields as $f){ if(empty($profile[$f])) $missing[] = ucfirst(str_replace('_',' ',$f)); }
            if(!empty($missing)):
            ?>
            <p style="font-size:.8rem;color:var(--clr-muted);margin-top:8px;">
                Missing: <?php echo implode(', ', $missing); ?>
            </p>
            <?php endif; ?>
            <div class="detail-row" style="margin-top:8px;">
                <span class="detail-key">Member Since</span>
                <span class="detail-val">
                    <?php echo !empty($user['created_at']) ? date('M Y', strtotime($user['created_at'])) : '2026'; ?>
                </span>
            </div>
        </div>
 
    </div><!-- /.profile-details-grid -->
 
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
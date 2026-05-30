<?php
 
session_start();
include("../includes/db.php");
 
// LOGIN CHECK
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}
 
$user_id = $_SESSION['user_id'];
 
// LOAD PROFILE DATA
$query   = "SELECT * FROM profiles WHERE user_id='$user_id'";
$result  = mysqli_query($conn, $query);
$profile = mysqli_fetch_assoc($result);
 
// SAVE PROFILE
if(isset($_POST['save_profile'])){
 
    $religion   = $_POST['religion'];
    $caste      = $_POST['caste'];
    $occupation = $_POST['occupation'];
    $income     = $_POST['income'];
    $city       = $_POST['city'];
    $state      = $_POST['state'];
    $bio        = $_POST['bio'];
 
    // Keep old photo if no new one uploaded
    $photo_name = $profile['profile_photo'];
 
    // PHOTO UPLOAD
    if(!empty($_FILES['profile_photo']['name'])){
        $photo_name = $_FILES['profile_photo']['name'];
        $tmp_name   = $_FILES['profile_photo']['tmp_name'];
        move_uploaded_file($tmp_name, "../uploads/".$photo_name);
    }
 
    // UPDATE PROFILE
    $updateQuery = "UPDATE profiles SET
        religion='$religion',
        caste='$caste',
        occupation='$occupation',
        income='$income',
        city='$city',
        state='$state',
        bio='$bio',
        profile_photo='$photo_name'
        WHERE user_id='$user_id'";
 
    $updateResult = mysqli_query($conn, $updateQuery);
 
    if($updateResult){
        $success = "Profile updated successfully!";
    }else{
        $error = mysqli_error($conn);
    }
 
    // Reload updated data
    $query   = "SELECT * FROM profiles WHERE user_id='$user_id'";
    $result  = mysqli_query($conn, $query);
    $profile = mysqli_fetch_assoc($result);
}
 
// Initials for avatar placeholder
$name_parts = explode(' ', trim($_SESSION['fullname']));
$initials   = strtoupper(substr($name_parts[0],0,1));
if(count($name_parts) > 1) $initials .= strtoupper(substr(end($name_parts),0,1));
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Aurivah</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
 
<?php include("../includes/header.php"); ?>
 
<section class="edit-profile-section">
<div class="edit-profile-inner">
 
    <a href="index.php" class="back-link">← Back to Dashboard</a>
 
    <div class="edit-profile-header">
        <h1>Complete Your Profile</h1>
        <p>Fill in your details to get the best match recommendations.</p>
    </div>
 
    <div class="profile-form-card">
 
        <!-- Alerts -->
        <?php if(isset($success)): ?>
            <div class="alert success">✅ <?php echo $success; ?></div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div class="alert error">❌ <?php echo $error; ?></div>
        <?php endif; ?>
 
        <!-- Current photo -->
        <div class="current-photo-wrap">
            <?php if(!empty($profile['profile_photo'])): ?>
                <img src="../uploads/<?php echo htmlspecialchars($profile['profile_photo']); ?>" alt="Profile Photo">
            <?php else: ?>
                <div class="current-photo-placeholder"><?php echo $initials; ?></div>
            <?php endif; ?>
            <div class="current-photo-info">
                <h4><?php echo htmlspecialchars($_SESSION['fullname']); ?></h4>
                <p><?php echo !empty($profile['city']) ? htmlspecialchars($profile['city'].', '.$profile['state']) : 'Location not set'; ?></p>
            </div>
        </div>
 
        <form method="POST" enctype="multipart/form-data">
 
            <!-- PERSONAL DETAILS -->
            <div class="form-section-label">Personal Details</div>
 
            <div class="form-row">
                <div class="form-group">
                    <label>Religion</label>
                    <select id="religion-select" name="religion" required>
                        <option value="">Select Religion</option>
                        <option value="Hindu"     <?php if($profile['religion']=="Hindu")     echo "selected"; ?>>Hindu</option>
                        <option value="Muslim"    <?php if($profile['religion']=="Muslim")    echo "selected"; ?>>Muslim</option>
                        <option value="Christian" <?php if($profile['religion']=="Christian") echo "selected"; ?>>Christian</option>
                        <option value="Sikh"      <?php if($profile['religion']=="Sikh")      echo "selected"; ?>>Sikh</option>
                        <option value="Jain"      <?php if($profile['religion']=="Jain")      echo "selected"; ?>>Jain</option>
                        <option value="Buddhist"  <?php if($profile['religion']=="Buddhist")  echo "selected"; ?>>Buddhist</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Community / Caste</label>
                    <select id="community-select" name="caste" required>
                        <option value="">Select Community</option>
                    </select>
                </div>
            </div>
 
            <!-- PROFESSIONAL DETAILS -->
            <div class="form-section-label">Professional Details</div>
 
            <div class="form-row">
                <div class="form-group">
                    <label>Occupation</label>
                    <select name="occupation" required>
                        <option value="">Select Occupation</option>
                        <option value="Business"       <?php if($profile['occupation']=="Business")       echo "selected"; ?>>Business</option>
                        <option value="Private Job"    <?php if($profile['occupation']=="Private Job")    echo "selected"; ?>>Private Job</option>
                        <option value="Government Job" <?php if($profile['occupation']=="Government Job") echo "selected"; ?>>Government Job</option>
                        <option value="Doctor"         <?php if($profile['occupation']=="Doctor")         echo "selected"; ?>>Doctor</option>
                        <option value="Teacher"        <?php if($profile['occupation']=="Teacher")        echo "selected"; ?>>Teacher</option>
                        <option value="Lawyer"         <?php if($profile['occupation']=="Lawyer")         echo "selected"; ?>>Lawyer</option>
                        <option value="Freelancer"     <?php if($profile['occupation']=="Freelancer")     echo "selected"; ?>>Freelancer</option>
                        <option value="Engineer"       <?php if($profile['occupation']=="Engineer")       echo "selected"; ?>>Engineer</option>
                        <option value="Aspirant"       <?php if($profile['occupation']=="Aspirant")       echo "selected"; ?>>Aspirant</option>
                        <option value="Not Working"    <?php if($profile['occupation']=="Not Working")    echo "selected"; ?>>Not Working</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Annual Income</label>
                    <select name="income" required>
                        <option value="">Select Income</option>
                        <option value="0-2 LPA"  <?php if($profile['income']=="0-2 LPA")  echo "selected"; ?>>0–2 LPA</option>
                        <option value="2-5 LPA"  <?php if($profile['income']=="2-5 LPA")  echo "selected"; ?>>2–5 LPA</option>
                        <option value="5-10 LPA" <?php if($profile['income']=="5-10 LPA") echo "selected"; ?>>5–10 LPA</option>
                        <option value="10-15 LPA"<?php if($profile['income']=="10-15 LPA")echo "selected"; ?>>10–15 LPA</option>
                        <option value="15-20 LPA"<?php if($profile['income']=="15-20 LPA")echo "selected"; ?>>15–20 LPA</option>
                        <option value="20+ LPA"  <?php if($profile['income']=="20+ LPA")  echo "selected"; ?>>20+ LPA</option>
                    </select>
                </div>
            </div>
 
            <!-- LOCATION -->
            <div class="form-section-label">Location</div>
 
            <div class="form-row">
                <div class="form-group">
                    <label>State</label>
                    <select name="state" required>
                        <option value="">Select State</option>
                        <option value="Assam"             <?php if($profile['state']=="Assam")             echo "selected"; ?>>Assam</option>
                        <option value="Arunachal Pradesh" <?php if($profile['state']=="Arunachal Pradesh") echo "selected"; ?>>Arunachal Pradesh</option>
                        <option value="Andhra Pradesh"    <?php if($profile['state']=="Andhra Pradesh")    echo "selected"; ?>>Andhra Pradesh</option>
                        <option value="Bihar"             <?php if($profile['state']=="Bihar")             echo "selected"; ?>>Bihar</option>
                        <option value="Chhattisgarh"      <?php if($profile['state']=="Chhattisgarh")      echo "selected"; ?>>Chhattisgarh</option>
                        <option value="Delhi"             <?php if($profile['state']=="Delhi")             echo "selected"; ?>>Delhi</option>
                        <option value="Gujarat"           <?php if($profile['state']=="Gujarat")           echo "selected"; ?>>Gujarat</option>
                        <option value="Goa"               <?php if($profile['state']=="Goa")               echo "selected"; ?>>Goa</option>
                        <option value="Haryana"           <?php if($profile['state']=="Haryana")           echo "selected"; ?>>Haryana</option>
                        <option value="Himachal Pradesh"  <?php if($profile['state']=="Himachal Pradesh")  echo "selected"; ?>>Himachal Pradesh</option>
                        <option value="Karnataka"         <?php if($profile['state']=="Karnataka")         echo "selected"; ?>>Karnataka</option>
                        <option value="Jharkhand"         <?php if($profile['state']=="Jharkhand")         echo "selected"; ?>>Jharkhand</option>
                        <option value="Kerala"            <?php if($profile['state']=="Kerala")            echo "selected"; ?>>Kerala</option>
                        <option value="Maharashtra"       <?php if($profile['state']=="Maharashtra")       echo "selected"; ?>>Maharashtra</option>
                        <option value="Odisha"            <?php if($profile['state']=="Odisha")            echo "selected"; ?>>Odisha</option>
                        <option value="Punjab"            <?php if($profile['state']=="Punjab")            echo "selected"; ?>>Punjab</option>
                        <option value="Rajasthan"         <?php if($profile['state']=="Rajasthan")         echo "selected"; ?>>Rajasthan</option>
                        <option value="Tamil Nadu"        <?php if($profile['state']=="Tamil Nadu")        echo "selected"; ?>>Tamil Nadu</option>
                        <option value="Telangana"         <?php if($profile['state']=="Telangana")         echo "selected"; ?>>Telangana</option>
                        <option value="Uttar Pradesh"     <?php if($profile['state']=="Uttar Pradesh")     echo "selected"; ?>>Uttar Pradesh</option>
                        <option value="West Bengal"       <?php if($profile['state']=="West Bengal")       echo "selected"; ?>>West Bengal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text"
                           name="city"
                           placeholder="Enter your city"
                           value="<?php echo htmlspecialchars($profile['city'] ?? ''); ?>"
                           required>
                </div>
            </div>
 
            <!-- PHOTO & BIO -->
            <div class="form-section-label">Photo & About You</div>
 
            <div class="form-group">
                <label>Upload Profile Photo</label>
                <input type="file" name="profile_photo" accept="image/*">
            </div>
 
            <div class="form-group">
                <label>About Me</label>
                <textarea name="bio" placeholder="Write a few words about yourself — your interests, values, and what you're looking for..." rows="5"><?php echo htmlspecialchars($profile['bio'] ?? ''); ?></textarea>
            </div>
 
            <button type="submit" name="save_profile" class="btn-save">
                💾 Save Profile
            </button>
 
        </form>
 
    </div><!-- /.profile-form-card -->
 
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
 
<script>const savedCommunity = "<?php echo $profile['caste']; ?>";</script>
<script src="../js/app.js"></script>
 
</body>
</html>
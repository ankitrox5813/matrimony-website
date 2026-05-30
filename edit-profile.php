<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/config.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT u.profile_photo, p.*
     FROM user_profiles p
     JOIN users u ON p.user_id = u.id
     WHERE p.user_id = ?"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();

$locations = json_decode(file_get_contents('assets/data/locations.json'), true);

$hobbiesResult = $conn->query("SELECT * FROM hobbies ORDER BY name ASC");
$hobbies = [];
$userHobbies = [];

$hobbyStmt = $conn->prepare("SELECT hobby_id FROM user_hobbies WHERE user_id = ?");
$hobbyStmt->bind_param("i", $user_id);
$hobbyStmt->execute();
$hobbyResult = $hobbyStmt->get_result();

while ($row = $hobbyResult->fetch_assoc()) {
    $userHobbies[] = $row['hobby_id'];
}

while ($row = $hobbiesResult->fetch_assoc()) {
    $hobbies[] = $row;
}

$photo = !empty($profile['profile_photo'])
    ? $profile['profile_photo']
    : 'assets/images/default-user.png';

$pageTitle = "Edit Profile";

include 'includes/header.php';
?>

<section class="edit-profile-section">
    <div class="edit-profile-inner">

        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

        <div class="edit-profile-header">
            <h1>Edit Profile</h1>
            <p>Update your details to improve match recommendations.</p>
        </div>

        <div class="profile-form-card">

            <form method="POST" enctype="multipart/form-data" action="api/update-profile.php">

                <div class="form-group photo-upload">
                    <label class="form-field-label">Profile photo</label>
                    <div class="photo-upload-preview">
                        <img
                            id="profilePhotoPreview"
                            src="<?= htmlspecialchars($photo) ?>"
                            alt="Current profile photo"
                        >
                        <div class="photo-upload-meta">
                            <h4>Update your photo</h4>
                            <p>Leave empty to keep your current photo.</p>
                        </div>
                    </div>
                    <div class="file-upload-control">
                        <label class="file-upload-btn">
                            <input
                                type="file"
                                name="profile_photo"
                                id="profilePhotoInput"
                                class="file-upload-input"
                                accept="image/*"
                            >
                            Choose new photo
                        </label>
                        <span class="file-upload-name" id="profilePhotoFileName">No file chosen</span>
                        <p class="file-upload-hint">JPG or PNG recommended.</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="state">State</label>
                    <select name="state" id="state">
                        <?php foreach ($locations as $state => $cities): ?>
                            <option value="<?= htmlspecialchars($state) ?>" <?= $profile['state'] == $state ? 'selected' : '' ?>>
                                <?= htmlspecialchars($state) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="city">City</label>
                    <select name="city" id="city"></select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="religion">Religion</label>
                    <select name="religion" id="religion"></select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="caste">Community / Caste</label>
                    <select name="caste" id="caste"></select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="hobbies">Hobbies</label>
                    <select name="hobbies[]" id="hobbies" multiple>
                        <?php foreach ($hobbies as $hobby): ?>
                            <option
                                value="<?= (int) $hobby['id'] ?>"
                                <?= in_array($hobby['id'], $userHobbies) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($hobby['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="education">Education</label>
                    <input type="text" name="education" id="education" value="<?= htmlspecialchars($profile['education']) ?>">
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="occupation">Occupation</label>
                    <input type="text" name="occupation" id="occupation" value="<?= htmlspecialchars($profile['occupation']) ?>">
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="annual_income">Annual income</label>
                    <input type="text" name="annual_income" id="annual_income" value="<?= htmlspecialchars($profile['annual_income']) ?>">
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="height">Height</label>
                    <input type="text" name="height" id="height" value="<?= htmlspecialchars($profile['height']) ?>">
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="marital_status">Marital status</label>
                    <select name="marital_status" id="marital_status">
                        <option value="Never Married" <?= $profile['marital_status'] == 'Never Married' ? 'selected' : '' ?>>Never Married</option>
                        <option value="Divorced" <?= $profile['marital_status'] == 'Divorced' ? 'selected' : '' ?>>Divorced</option>
                        <option value="Widowed" <?= $profile['marital_status'] == 'Widowed' ? 'selected' : '' ?>>Widowed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="about_me">About you</label>
                    <textarea name="about_me" id="about_me" rows="5"><?= htmlspecialchars($profile['about_me']) ?></textarea>
                </div>

                <button type="submit" class="btn-save">Update Profile</button>

            </form>
        </div>
    </div>
</section>

<script src="assets/js/profile-form.js"></script>
<script type="module">
    const locations = <?= json_encode($locations) ?>;
    const religionData = await fetch('assets/data/religion-caste.json').then(res => res.json());

    const select2Single = { width: '100%', allowClear: true };
    const select2Multi = { width: '100%', closeOnSelect: false };

    $('#state').select2({ ...select2Single, placeholder: 'Select State' });
    $('#city').select2({ ...select2Single, placeholder: 'Select City' });
    $('#religion').select2({ ...select2Single, placeholder: 'Select Religion' });
    $('#caste').select2({ ...select2Single, placeholder: 'Select Caste' });
    $('#marital_status').select2({ ...select2Single, placeholder: 'Select Marital Status' });
    $('#hobbies').select2({ ...select2Multi, placeholder: 'Select hobbies' });

    function loadCities(state, selectedCity = '') {
        $('#city').empty().append('<option value="">Select City</option>');
        (locations[state] || []).forEach(city => {
            $('#city').append(`<option value="${city}">${city}</option>`);
        });
        $('#city').val(selectedCity).trigger('change');
    }

    function loadReligions(selectedReligion = '') {
        $('#religion').empty().append('<option value="">Select Religion</option>');
        religionData.religion.forEach(item => {
            $('#religion').append(`<option value="${item.name}">${item.name}</option>`);
        });
        $('#religion').val(selectedReligion).trigger('change');
    }

    function loadCastes(religionName, selectedCaste = '') {
        $('#caste').empty().append('<option value="">Select Caste</option>');
        const religion = religionData.religion.find(r => r.name === religionName);
        if (!religion) return;
        religion.castes.forEach(caste => {
            if (caste.name === '-- Select --') return;
            $('#caste').append(`<option value="${caste.name}">${caste.name}</option>`);
        });
        $('#caste').val(selectedCaste).trigger('change');
    }

    loadCities(<?= json_encode($profile['state']) ?>, <?= json_encode($profile['city']) ?>);
    loadReligions(<?= json_encode($profile['religion']) ?>);
    loadCastes(<?= json_encode($profile['religion']) ?>, <?= json_encode($profile['caste']) ?>);

    $('#state').on('change', function () { loadCities($(this).val()); });
    $('#religion').on('change', function () { loadCastes($(this).val()); });
</script>

<?php include 'includes/footer.php'; ?>

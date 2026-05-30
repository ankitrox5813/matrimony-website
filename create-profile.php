<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/config.php';

$hobbiesResult = $conn->query("SELECT * FROM hobbies ORDER BY name ASC");
$hobbies = [];

while ($row = $hobbiesResult->fetch_assoc()) {
    $hobbies[] = $row;
}

$stmt = $conn->prepare("SELECT id FROM user_profiles WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

if ($stmt->get_result()->fetch_assoc()) {
    header('Location: edit-profile.php');
    exit;
}

$pageTitle = "Create Profile";

$locations = json_decode(
    file_get_contents('assets/data/locations.json'),
    true
);

include 'includes/header.php';
?>

<section class="edit-profile-section">
    <div class="edit-profile-inner">

        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

        <div class="edit-profile-header">
            <h1>Complete Your Profile</h1>
            <p>Add your details to start receiving match recommendations.</p>
        </div>

        <div class="profile-form-card">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form method="POST" action="api/save-profile.php" enctype="multipart/form-data">

                <div class="form-group photo-upload">
                    <label class="form-field-label">Profile photo</label>
                    <div class="photo-upload-preview">
                        <img
                            id="profilePhotoPreview"
                            src="assets/images/default-user.png"
                            alt="Profile preview"
                        >
                        <div class="photo-upload-meta">
                            <h4>Upload your photo</h4>
                            <p>A clear photo helps you get more responses.</p>
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
                            Choose photo
                        </label>
                        <span class="file-upload-name" id="profilePhotoFileName">No file chosen</span>
                        <p class="file-upload-hint">JPG or PNG recommended.</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="state">State</label>
                    <select name="state" id="state">
                        <option value="">Select State</option>
                        <?php foreach ($locations as $state => $cities): ?>
                            <option value="<?= htmlspecialchars($state) ?>"><?= htmlspecialchars($state) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="city">City</label>
                    <select name="city" id="city">
                        <option value="">Select City</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="religion">Religion</label>
                    <select name="religion" id="religion">
                        <option value="">Select Religion</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="caste">Community / Caste</label>
                    <select name="caste" id="caste">
                        <option value="">Select Caste</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="hobbies">Hobbies</label>
                    <select name="hobbies[]" id="hobbies" multiple>
                        <?php foreach ($hobbies as $hobby): ?>
                            <option value="<?= (int) $hobby['id'] ?>">
                                <?= htmlspecialchars($hobby['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="education">Education</label>
                    <input type="text" name="education" id="education" placeholder="e.g. B.Tech, MBA">
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="occupation">Occupation</label>
                    <input type="text" name="occupation" id="occupation" placeholder="Your profession">
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="annual_income">Annual income</label>
                    <input type="text" name="annual_income" id="annual_income" placeholder="e.g. 5–10 LPA">
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="height">Height</label>
                    <input type="text" name="height" id="height" placeholder="e.g. 5 ft 10 in">
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="marital_status">Marital status</label>
                    <select name="marital_status" id="marital_status">
                        <option value="">Select Marital Status</option>
                        <option value="Never Married">Never Married</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Widowed">Widowed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="about_me">About you</label>
                    <textarea name="about_me" id="about_me" rows="5" placeholder="Tell us about yourself"></textarea>
                </div>

                <button type="submit" class="btn-save">Save Profile</button>

            </form>
        </div>
    </div>
</section>

<script src="assets/js/profile-form.js"></script>
<script type="module">
    const locations = <?= json_encode($locations) ?>;
    const religionData = await fetch('assets/data/religion-caste.json').then(res => res.json());

    const select2Single = { width: '100%', allowClear: true };
    const select2Multi = { width: '100%', closeOnSelect: false, placeholder: '' };

    $('#state').select2({ ...select2Single, placeholder: 'Select State' });
    $('#city').select2({ ...select2Single, placeholder: 'Select City' });
    $('#religion').select2({ ...select2Single, placeholder: 'Select Religion' });
    $('#caste').select2({ ...select2Single, placeholder: 'Select Caste' });
    $('#marital_status').select2({ ...select2Single, placeholder: 'Select Marital Status' });
    $('#hobbies').select2({ ...select2Multi, placeholder: 'Select hobbies' });

    function loadCities(state) {
        $('#city').empty().append('<option value="">Select City</option>');
        (locations[state] || []).forEach(city => {
            $('#city').append(`<option value="${city}">${city}</option>`);
        });
        $('#city').trigger('change');
    }

    function loadReligions() {
        $('#religion').empty().append('<option value="">Select Religion</option>');
        religionData.religion.forEach(item => {
            $('#religion').append(`<option value="${item.name}">${item.name}</option>`);
        });
        $('#religion').trigger('change');
    }

    function loadCastes(religionName) {
        $('#caste').empty().append('<option value="">Select Caste</option>');
        const religion = religionData.religion.find(r => r.name === religionName);
        if (!religion) return;
        religion.castes.forEach(caste => {
            if (caste.name === '-- Select --') return;
            $('#caste').append(`<option value="${caste.name}">${caste.name}</option>`);
        });
        $('#caste').trigger('change');
    }

    loadReligions();

    $('#state').on('change', function () { loadCities($(this).val()); });
    $('#religion').on('change', function () { loadCastes($(this).val()); });
</script>

<?php include 'includes/footer.php'; ?>

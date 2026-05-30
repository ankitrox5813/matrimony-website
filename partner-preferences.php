<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = "Partner Preferences";

$locations = json_decode(
    file_get_contents('assets/data/locations.json'),
    true
);

require_once 'includes/config.php';

$stmt = $conn->prepare("SELECT id, name FROM hobbies ORDER BY name");
$stmt->execute();
$hobbies = $stmt->get_result();

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM partner_preferences WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$preferences = $stmt->get_result()->fetch_assoc() ?: [];

$selectedReligions = json_decode($preferences['religions'] ?? '[]', true);
$selectedCastes = json_decode($preferences['castes'] ?? '[]', true);
$selectedStates = json_decode($preferences['states'] ?? '[]', true);
$selectedCities = json_decode($preferences['cities'] ?? '[]', true);
$selectedEducations = json_decode($preferences['educations'] ?? '[]', true);
$selectedMaritalStatuses = json_decode($preferences['marital_statuses'] ?? '[]', true);
$selectedHobbies = json_decode($preferences['hobbies'] ?? '[]', true);

include 'includes/header.php';
?>

<section class="edit-profile-section">
    <div class="edit-profile-inner">

        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

        <div class="edit-profile-header">
            <h1>Partner Preferences</h1>
            <p>Tell us what you are looking for in a life partner.</p>
        </div>

        <div class="profile-form-card">

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="api/save-preferences.php">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-field-label" for="min_age">Minimum age</label>
                        <input
                            type="number"
                            name="min_age"
                            id="min_age"
                            min="18"
                            max="100"
                            placeholder="18"
                            value="<?= htmlspecialchars($preferences['min_age'] ?? '') ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-field-label" for="max_age">Maximum age</label>
                        <input
                            type="number"
                            name="max_age"
                            id="max_age"
                            min="18"
                            max="100"
                            placeholder="35"
                            value="<?= htmlspecialchars($preferences['max_age'] ?? '') ?>"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="religions">Preferred religions</label>
                    <select id="religions" name="religions[]" multiple></select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="castes">Preferred communities</label>
                    <select id="castes" name="castes[]" multiple></select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="states">Preferred states</label>
                    <select id="states" name="states[]" multiple>
                        <?php foreach ($locations as $state => $cities): ?>
                            <option value="<?= htmlspecialchars($state) ?>"><?= htmlspecialchars($state) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="cities">Preferred cities</label>
                    <select id="cities" name="cities[]" multiple></select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="educations">Preferred education</label>
                    <select id="educations" name="educations[]" multiple>
                        <option>MCA</option>
                        <option>BCA</option>
                        <option>B.Tech</option>
                        <option>M.Tech</option>
                        <option>BA</option>
                        <option>MA</option>
                        <option>B.Com</option>
                        <option>M.Com</option>
                        <option>MBBS</option>
                        <option>MBA</option>
                        <option>PhD</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="marital_statuses">Preferred marital status</label>
                    <select id="marital_statuses" name="marital_statuses[]" multiple>
                        <option>Never Married</option>
                        <option>Divorced</option>
                        <option>Widowed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-field-label" for="hobbies">Preferred hobbies</label>
                    <select id="hobbies" name="hobbies[]" multiple>
                        <?php while ($hobby = $hobbies->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($hobby['name']) ?>">
                                <?= htmlspecialchars($hobby['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn-save">Save Preferences</button>

            </form>
        </div>
    </div>
</section>

<script type="module">
    const selectedReligions = <?= json_encode($selectedReligions) ?>;
    const selectedCastes = <?= json_encode($selectedCastes) ?>;
    const selectedStates = <?= json_encode($selectedStates) ?>;
    const selectedCities = <?= json_encode($selectedCities) ?>;
    const selectedEducations = <?= json_encode($selectedEducations) ?>;
    const selectedMaritalStatuses = <?= json_encode($selectedMaritalStatuses) ?>;
    const selectedHobbies = <?= json_encode($selectedHobbies) ?>;

    const locations = <?= json_encode($locations) ?>;
    const religionData = await fetch('assets/data/religion-caste.json').then(res => res.json());

    const select2Multi = { width: '100%', closeOnSelect: false };

    $('#religions').select2({ ...select2Multi, placeholder: 'Select religions' });
    $('#castes').select2({ ...select2Multi, placeholder: 'Select communities' });
    $('#states').select2({ ...select2Multi, placeholder: 'Select states' });
    $('#cities').select2({ ...select2Multi, placeholder: 'Select cities' });
    $('#educations').select2({ ...select2Multi, placeholder: 'Select education levels' });
    $('#marital_statuses').select2({ ...select2Multi, placeholder: 'Select marital status' });
    $('#hobbies').select2({ ...select2Multi, placeholder: 'Select hobbies' });

    function loadReligions() {
        $('#religions').empty();
        religionData.religion.forEach(item => {
            $('#religions').append(`<option value="${item.name}">${item.name}</option>`);
        });
    }

    function loadCastes() {
        const prev = $('#castes').val() || [];
        $('#castes').empty();
        const selectedReligionList = $('#religions').val() || [];
        const casteList = [];

        selectedReligionList.forEach(religionName => {
            const religion = religionData.religion.find(r => r.name === religionName);
            if (!religion) return;
            religion.castes.forEach(caste => {
                if (caste.name && caste.name !== '-- Select --') {
                    casteList.push(caste.name);
                }
            });
        });

        [...new Set(casteList)].forEach(caste => {
            $('#castes').append(`<option value="${caste}">${caste}</option>`);
        });

        $('#castes').val(prev.filter(c => casteList.includes(c))).trigger('change');
    }

    function loadCities() {
        const prev = $('#cities').val() || [];
        $('#cities').empty();
        const selectedStateList = $('#states').val() || [];
        const cityList = [];

        selectedStateList.forEach(state => {
            if (locations[state]) {
                cityList.push(...locations[state]);
            }
        });

        [...new Set(cityList)].forEach(city => {
            $('#cities').append(`<option value="${city}">${city}</option>`);
        });

        $('#cities').val(prev.filter(c => cityList.includes(c))).trigger('change');
    }

    loadReligions();
    $('#religions').val(selectedReligions).trigger('change');
    loadCastes();
    $('#castes').val(selectedCastes).trigger('change');
    $('#states').val(selectedStates).trigger('change');
    loadCities();
    $('#cities').val(selectedCities).trigger('change');
    $('#educations').val(selectedEducations).trigger('change');
    $('#marital_statuses').val(selectedMaritalStatuses).trigger('change');
    $('#hobbies').val(selectedHobbies).trigger('change');

    $('#states').on('change', loadCities);
    $('#religions').on('change', loadCastes);
</script>

<?php include 'includes/footer.php'; ?>

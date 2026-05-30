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

$stmt = $conn->prepare(
    "SELECT id, name
     FROM hobbies
     ORDER BY name"
);

$stmt->execute();

$hobbies =
    $stmt->get_result();



$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT *
     FROM partner_preferences
     WHERE user_id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$preferences = $stmt
    ->get_result()
    ->fetch_assoc();

$selectedReligions =
    json_decode(
        $preferences['religions'] ?? '[]',
        true
    );

$selectedCastes =
    json_decode(
        $preferences['castes'] ?? '[]',
        true
    );

$selectedStates =
    json_decode(
        $preferences['states'] ?? '[]',
        true
    );

$selectedCities =
    json_decode(
        $preferences['cities'] ?? '[]',
        true
    );

$selectedEducations =
    json_decode(
        $preferences['educations'] ?? '[]',
        true
    );

$selectedMaritalStatuses =
    json_decode(
        $preferences['marital_statuses'] ?? '[]',
        true
    );

$selectedHobbies =
    json_decode(
        $preferences['hobbies'] ?? '[]',
        true
    );

include 'includes/header.php';
?>

<div class="form-section">

    <form class="auth-form" method="POST" action="api/save-preferences.php">

        <h2>Partner Preferences</h2>

        <?php if (isset($_SESSION['success'])): ?>

            <div class="alert-success">
                <?= $_SESSION['success'] ?>
            </div>

            <?php unset($_SESSION['success']); ?>

        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>

            <div class="alert-error">
                <?= $_SESSION['error'] ?>
            </div>

            <?php unset($_SESSION['error']); ?>

        <?php endif; ?>

        <!-- <input type="number" name="min_age" placeholder="Minimum Age" min="18" max="100">

        <input type="number" name="max_age" placeholder="Maximum Age" min="18" max="100"> -->
        <input type="number" name="min_age" placeholder="Minimum Age" value="<?= $preferences['min_age'] ?? '' ?>">

        <input type="number" name="max_age" placeholder="Maximum Age" value="<?= $preferences['max_age'] ?? '' ?>">

        <!-- Religion -->

        <select id="religions" name="religions[]" multiple>
        </select>

        <!-- Caste -->

        <select id="castes" name="castes[]" multiple>
        </select>

        <!-- States -->

        <select id="states" name="states[]" multiple>

            <?php foreach ($locations as $state => $cities): ?>

                <option value="<?= $state ?>">
                    <?= $state ?>
                </option>

            <?php endforeach; ?>

        </select>

        <!-- Cities -->

        <select id="cities" name="cities[]" multiple>
        </select>

        <!-- Education -->

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

        <!-- Marital Status -->

        <select id="marital_statuses" name="marital_statuses[]" multiple>

            <option>Never Married</option>
            <option>Divorced</option>
            <option>Widowed</option>

        </select>

        <!-- Hobbies -->

        <!-- Hobbies -->

        <select id="hobbies" name="hobbies[]" multiple>

            <?php while ($hobby = $hobbies->fetch_assoc()): ?>

                <option value="<?= $hobby['name'] ?>">
                    <?= $hobby['name'] ?>
                </option>

            <?php endwhile; ?>

        </select>

        <button type="submit">
            Save Preferences
        </button>

    </form>

</div>

<script type="module">

    const selectedReligions =
        <?= json_encode($selectedReligions) ?>;

    const selectedCastes =
        <?= json_encode($selectedCastes) ?>;

    const selectedStates =
        <?= json_encode($selectedStates) ?>;

    const selectedCities =
        <?= json_encode($selectedCities) ?>;

    const selectedEducations =
        <?= json_encode($selectedEducations) ?>;

    const selectedMaritalStatuses =
        <?= json_encode($selectedMaritalStatuses) ?>;

    const selectedHobbies =
        <?= json_encode($selectedHobbies) ?>;

    const locations =
        <?= json_encode($locations) ?>;

    const religionData = await fetch(
        'assets/data/religion-caste.json'
    ).then(res => res.json());

    /*
    |--------------------------------------------------------------------------
    | SELECT2
    |--------------------------------------------------------------------------
    */

    $('#religions').select2({
        placeholder: 'Preferred Religions',
        width: '100%'
    });

    $('#castes').select2({
        placeholder: 'Preferred Castes',
        width: '100%'
    });

    $('#states').select2({
        placeholder: 'Preferred States',
        width: '100%'
    });

    $('#cities').select2({
        placeholder: 'Preferred Cities',
        width: '100%'
    });

    $('#educations').select2({
        placeholder: 'Preferred Education',
        width: '100%'
    });

    $('#marital_statuses').select2({
        placeholder: 'Preferred Marital Status',
        width: '100%'
    });

    $('#hobbies').select2({
        placeholder: 'Preferred Hobbies',
        width: '100%'
    });

    /*
    |--------------------------------------------------------------------------
    | LOAD RELIGIONS
    |--------------------------------------------------------------------------
    */

    function loadReligions() {

        $('#religions').empty();

        religionData.religion.forEach(item => {

            $('#religions').append(`
            <option value="${item.name}">
                ${item.name}
            </option>
        `);

        });

    }

    /*
    |--------------------------------------------------------------------------
    | LOAD CASTES
    |--------------------------------------------------------------------------
    */

    function loadCastes() {

        let selectedCastes =
            $('#castes').val() || [];

        $('#castes').empty();

        let selectedReligions =
            $('#religions').val() || [];

        let casteList = [];

        selectedReligions.forEach(religionName => {

            let religion =
                religionData.religion.find(
                    r => r.name === religionName
                );

            if (!religion) return;

            religion.castes.forEach(caste => {

                if (
                    caste.name &&
                    caste.name !== '-- Select --'
                ) {

                    casteList.push(
                        caste.name
                    );

                }

            });

        });

        casteList = [...new Set(casteList)];

        casteList.forEach(caste => {

            $('#castes').append(`
            <option value="${caste}">
                ${caste}
            </option>
        `);

        });

        let validCastes =
            selectedCastes.filter(
                caste => casteList.includes(caste)
            );

        $('#castes')
            .val(validCastes)
            .trigger('change');
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD CITIES
    |--------------------------------------------------------------------------
    */

    function loadCities() {

        let selectedCities =
            $('#cities').val() || [];

        $('#cities').empty();

        let cityList = [];

        let selectedStates =
            $('#states').val() || [];

        selectedStates.forEach(state => {

            if (locations[state]) {

                cityList.push(
                    ...locations[state]
                );

            }

        });

        cityList = [...new Set(cityList)];

        cityList.forEach(city => {

            $('#cities').append(`
            <option value="${city}">
                ${city}
            </option>
        `);

        });

        let validCities =
            selectedCities.filter(
                city => cityList.includes(city)
            );

        $('#cities')
            .val(validCities)
            .trigger('change');
    }
    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    loadReligions();

    $('#religions')
        .val(selectedReligions)
        .trigger('change');

    loadCastes();

    $('#castes')
        .val(selectedCastes)
        .trigger('change');

    $('#states')
        .val(selectedStates)
        .trigger('change');

    loadCities();

    $('#cities')
        .val(selectedCities)
        .trigger('change');

    $('#educations')
        .val(selectedEducations)
        .trigger('change');

    $('#marital_statuses')
        .val(selectedMaritalStatuses)
        .trigger('change');

    $('#hobbies')
        .val(selectedHobbies)
        .trigger('change');

    /*
    |--------------------------------------------------------------------------
    | EVENTS
    |--------------------------------------------------------------------------
    */

    $('#states').on(
        'change',
        function () {

            loadCities();

        }
    );

    $('#religions').on(
        'change',
        function () {

            loadCastes();

        }
    );

</script>

<?php include 'includes/footer.php'; ?>
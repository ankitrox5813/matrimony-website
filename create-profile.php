<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = "Create Profile";

$locations = json_decode(
    file_get_contents(
        'assets/data/locations.json'
    ),
    true
);

include 'includes/header.php';
?>

<div class="form-section">

    <form class="auth-form" method="POST" action="api/save-profile.php" enctype="multipart/form-data">

        <h2>Complete Your Profile</h2>

        <label>Profile Photo</label>

        <input type="file" name="profile_photo" accept="image/*">

        <select name="state" id="state" required>

            <option value="">
                Select State
            </option>

            <?php foreach ($locations as $state => $cities): ?>

                <option value="<?= $state ?>">
                    <?= $state ?>
                </option>

            <?php endforeach; ?>

        </select>

        <select name="city" id="city" required>

            <option value="">
                Select City
            </option>

        </select>

        <!-- Religion -->

        <select name="religion" id="religion" required>

            <option value="">
                Select Religion
            </option>

        </select>

        <!-- Caste -->

        <select name="caste" id="caste" required>

            <option value="">
                Select Caste
            </option>

        </select>

        <input type="text" name="education" placeholder="Education" required>

        <input type="text" name="occupation" placeholder="Occupation" required>

        <input type="text" name="annual_income" placeholder="Annual Income">

        <input type="text" name="height" placeholder="Height (e.g. 5ft 10in)">

        <select name="marital_status" required>
            <option value="">
                Select Marital Status
            </option>

            <option value="Never Married">
                Never Married
            </option>

            <option value="Divorced">
                Divorced
            </option>

            <option value="Widowed">
                Widowed
            </option>
        </select>

        <textarea name="about_me" rows="5" placeholder="Tell us about yourself" style="
            width:100%;
            padding:15px;
            margin-bottom:20px;
            "></textarea>

        <button type="submit">
            Save Profile
        </button>

    </form>

</div>


<script type="module">

    const locations =
<?= json_encode($locations) ?>;

    const religionData = await fetch(
        'assets/data/religion-caste.json'
    ).then(res => res.json());

    /*
    |--------------------------------------------------------------------------
    | STATE / CITY
    |--------------------------------------------------------------------------
    */

    const stateSelect =
        document.getElementById('state');

    const citySelect =
        document.getElementById('city');



    /*
    |--------------------------------------------------------------------------
    | RELIGION / CASTE
    |--------------------------------------------------------------------------
    */

    const religionSelect =
        document.getElementById('religion');

    const casteSelect =
        document.getElementById('caste');




    /*
    |--------------------------------------------------------------------------
    | Religion Change
    |--------------------------------------------------------------------------
    */


/*
|--------------------------------------------------------------------------
|  SELECT2 INIT
|--------------------------------------------------------------------------
*/

$('#state').select2({
    placeholder: 'Select State',
    width: '100%'
});

$('#city').select2({
    placeholder: 'Select City',
    width: '100%'
});

$('#religion').select2({
    placeholder: 'Select Religion',
    width: '100%'
});

$('#caste').select2({
    placeholder: 'Select Caste',
    width: '100%'
});

/*
|--------------------------------------------------------------------------
| LOAD CITIES
|--------------------------------------------------------------------------
*/

function loadCities(state) {

    $('#city').empty();

    $('#city').append(`
        <option value="">
            Select City
        </option>
    `);

    let cities =
        locations[state] || [];

    cities.forEach(city => {

        $('#city').append(`
            <option value="${city}">
                ${city}
            </option>
        `);

    });

    $('#city').trigger('change');

}

/*
|--------------------------------------------------------------------------
| LOAD RELIGIONS
|--------------------------------------------------------------------------
*/

function loadReligions() {

    $('#religion').empty();

    $('#religion').append(`
        <option value="">
            Select Religion
        </option>
    `);

    religionData.religion.forEach(item => {

        $('#religion').append(`
            <option value="${item.name}">
                ${item.name}
            </option>
        `);

    });

    $('#religion').trigger('change');

}

/*
|--------------------------------------------------------------------------
| LOAD CASTES
|--------------------------------------------------------------------------
*/

function loadCastes(religionName) {

    $('#caste').empty();

    $('#caste').append(`
        <option value="">
            Select Caste
        </option>
    `);

    let religion =
        religionData.religion.find(
            r => r.name === religionName
        );

    if (!religion) return;

    religion.castes.forEach(caste => {

        if (caste.name === '-- Select --')
            return;

        $('#caste').append(`
            <option value="${caste.name}">
                ${caste.name}
            </option>
        `);

    });

    $('#caste').trigger('change');

}

/*
|--------------------------------------------------------------------------
| INITIAL LOAD
|--------------------------------------------------------------------------
*/

loadReligions();

/*
|--------------------------------------------------------------------------
| EVENTS
|--------------------------------------------------------------------------
*/

$('#state').on(
    'change',
    function () {

        loadCities($(this).val());

    }
);

$('#religion').on(
    'change',
    function () {

        loadCastes($(this).val());

    }
);

</script>

<?php include 'includes/footer.php'; ?>
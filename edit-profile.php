<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/config.php';


$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    // "SELECT * FROM user_profiles WHERE user_id = ?"
    "SELECT
u.profile_photo,
p.*
FROM user_profiles p
JOIN users u
ON p.user_id = u.id
WHERE p.user_id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$profile = $stmt->get_result()->fetch_assoc();

$locations = json_decode(
    file_get_contents(
        'assets/data/locations.json'
    ),
    true
);

$hobbiesResult =
    $conn->query(
        "SELECT * FROM hobbies ORDER BY name ASC"
    );

$hobbies = [];

/*
|--------------------------------------------------------------------------
| User Hobbies
|--------------------------------------------------------------------------
*/

$userHobbies = [];

$hobbyStmt = $conn->prepare(
    "SELECT hobby_id
     FROM user_hobbies
     WHERE user_id = ?"
);

$hobbyStmt->bind_param(
    "i",
    $user_id
);

$hobbyStmt->execute();

$hobbyResult =
    $hobbyStmt->get_result();

while ($row = $hobbyResult->fetch_assoc()) {

    $userHobbies[] =
        $row['hobby_id'];

}

while ($row = $hobbiesResult->fetch_assoc()) {

    $hobbies[] = $row;

}

$pageTitle = "Edit Profile";

include 'includes/header.php';
?>

<div class="form-section">

    <form class="auth-form" method="POST" enctype="multipart/form-data" action="api/update-profile.php">

        <h2>Edit Profile</h2>

        <!-- <label>Current Photo</label> -->

        <?php

        $photo =
            !empty($profile['profile_photo'])
            ? $profile['profile_photo']
            : 'assets/images/default-user.png';

        ?>

        <img src="<?= $photo ?>" style="
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            object-position: center top;
            display: block;
            margin: 0 auto 25px;
            border: 4px solid #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .1);
        ">


        <input type="file" name="profile_photo" accept="image/*">

        <select name="state" id="state">

            <?php foreach ($locations as $state => $cities): ?>

                <option value="<?= $state ?>" <?= $profile['state'] == $state
                      ? 'selected'
                      : '' ?>>
                    <?= $state ?>
                </option>

            <?php endforeach; ?>

        </select>

        <select name="city" id="city">

        </select>

        <select name="religion" id="religion">

        </select>

        <select name="caste" id="caste">

        </select>

        <select name="hobbies[]" id="hobbies" multiple>

            <?php foreach ($hobbies as $hobby): ?>

                <option value="<?= $hobby['id'] ?>" <?= in_array(
                      $hobby['id'],
                      $userHobbies
                  ) ? 'selected' : '' ?>>

                    <?= htmlspecialchars($hobby['name']) ?>

                </option>

            <?php endforeach; ?>

        </select>

        <input type="text" name="education" value="<?= htmlspecialchars($profile['education']) ?>"
            placeholder="Education">

        <input type="text" name="occupation" value="<?= htmlspecialchars($profile['occupation']) ?>"
            placeholder="Occupation">

        <input type="text" name="annual_income" value="<?= htmlspecialchars($profile['annual_income']) ?>"
            placeholder="Annual Income">

        <input type="text" name="height" value="<?= htmlspecialchars($profile['height']) ?>" placeholder="Height">

        <select name="marital_status">

            <option value="Never Married" <?= $profile['marital_status'] == 'Never Married' ? 'selected' : '' ?>>
                Never Married
            </option>

            <option value="Divorced" <?= $profile['marital_status'] == 'Divorced' ? 'selected' : '' ?>>
                Divorced
            </option>

            <option value="Widowed" <?= $profile['marital_status'] == 'Widowed' ? 'selected' : '' ?>>
                Widowed
            </option>

        </select>

        <textarea name="about_me" rows="5"
            style="width:100%;padding:15px;margin-bottom:20px;"><?= htmlspecialchars($profile['about_me']) ?></textarea>

        <button type="submit">
            Update Profile
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
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const stateSelect =
        document.getElementById('state');

    const citySelect =
        document.getElementById('city');

    const religionSelect =
        document.getElementById('religion');

    const casteSelect =
        document.getElementById('caste');

    /*
    |--------------------------------------------------------------------------
    | SELECT2 INIT
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

    $('#hobbies').select2({

        placeholder: 'Select Hobbies',

        width: '100%',

        closeOnSelect: false

    });

    /*
    |--------------------------------------------------------------------------
    | LOAD CITIES
    |--------------------------------------------------------------------------
    */

    function loadCities(state, selectedCity = '') {

        $('#city').empty();

        $('#city').append(
            `<option value="">Select City</option>`
        );

        let cities = locations[state] || [];

        cities.forEach(city => {

            $('#city').append(
                `<option value="${city}">
                ${city}
            </option>`
            );

        });

        $('#city').val(selectedCity).trigger('change');

    }

    /*
    |--------------------------------------------------------------------------
    | LOAD RELIGIONS
    |--------------------------------------------------------------------------
    */

    function loadReligions(selectedReligion = '') {

        $('#religion').empty();

        $('#religion').append(
            `<option value="">Select Religion</option>`
        );

        religionData.religion.forEach(item => {

            $('#religion').append(
                `<option value="${item.name}">
                ${item.name}
            </option>`
            );

        });

        $('#religion')
            .val(selectedReligion)
            .trigger('change');

    }

    /*
    |--------------------------------------------------------------------------
    | LOAD CASTES
    |--------------------------------------------------------------------------
    */
    function loadCastes(
        religionName,
        selectedCaste = ''
    ) {

        $('#caste').empty();

        $('#caste').append(
            `<option value="">Select Caste</option>`
        );

        let religion =
            religionData.religion.find(
                r => r.name === religionName
            );

        if (!religion) return;

        religion.castes.forEach(caste => {

            if (caste.name === '-- Select --')
                return;

            $('#caste').append(
                `<option value="${caste.name}">
                ${caste.name}
            </option>`
            );

        });

        $('#caste')
            .val(selectedCaste)
            .trigger('change');

    }

    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    loadCities(
        "<?= $profile['state'] ?>",
        "<?= $profile['city'] ?>"
    );

    loadReligions(
        "<?= $profile['religion'] ?>"
    );

    loadCastes(
        "<?= $profile['religion'] ?>",
        "<?= $profile['caste'] ?>"
    );

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
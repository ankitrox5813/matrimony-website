<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}



require_once 'includes/config.php';

$locations = json_decode(
    file_get_contents(
        'assets/data/locations.json'
    ),
    true
);

$currentUser = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT gender
     FROM users
     WHERE id = ?"
);

$stmt->bind_param(
    "i",
    $currentUser
);

$stmt->execute();

$userData =
    $stmt
        ->get_result()
        ->fetch_assoc();

$currentUserGender =
    $userData['gender'] ?? null;

$preferredGender = null;

if ($currentUserGender === 'Male') {

    $preferredGender = 'Female';

} elseif ($currentUserGender === 'Female') {

    $preferredGender = 'Male';
}

$where = "WHERE u.id != ?";
$params = [$currentUser];
$types = "i";

if ($preferredGender !== null) {

    $where .= " AND u.gender = ?";

    $params[] = $preferredGender;

    $types .= "s";
}

/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

$page =
    isset($_GET['page'])
    ? max(1, (int) $_GET['page'])
    : 1;

$limit = 12;

$offset =
    ($page - 1) * $limit;

/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/


if (!empty($_GET['state'])) {

    $where .= " AND p.state = ? ";

    $params[] = $_GET['state'];

    $types .= "s";
}

if (!empty($_GET['city'])) {

    $where .= " AND p.city = ? ";

    $params[] = $_GET['city'];

    $types .= "s";
}



if (!empty($_GET['religion'])) {

    $where .= " AND p.religion = ? ";

    $params[] = $_GET['religion'];

    $types .= "s";
}

if (!empty($_GET['caste'])) {

    $where .= " AND p.caste = ? ";

    $params[] = $_GET['caste'];

    $types .= "s";
}

/*
|--------------------------------------------------------------------------
| Min Age
|--------------------------------------------------------------------------
*/

if (!empty($_GET['min_age'])) {

    $where .= " AND u.age >= ? ";

    $params[] = (int) $_GET['min_age'];

    $types .= "i";
}

/*
|--------------------------------------------------------------------------
| Max Age
|--------------------------------------------------------------------------
*/

if (!empty($_GET['max_age'])) {

    $where .= " AND u.age <= ? ";

    $params[] = (int) $_GET['max_age'];

    $types .= "i";
}

/*
|--------------------------------------------------------------------------
| Total Records
|--------------------------------------------------------------------------
*/

$countSql =
    "
SELECT COUNT(*)
FROM users u
JOIN user_profiles p
ON u.id = p.user_id
" . $where;

$countStmt =
    $conn->prepare($countSql);

$countStmt->bind_param(
    $types,
    ...$params
);

$countStmt->execute();

$countStmt->bind_result(
    $totalRecords
);

$countStmt->fetch();

$countStmt->close();

$totalPages =
    ceil($totalRecords / $limit);

/*
|--------------------------------------------------------------------------
| Fetch Profiles
|--------------------------------------------------------------------------
*/

$sql =
    "
SELECT
    u.id,
    u.full_name,
    u.profile_photo,
    u.gender,
    u.age,
    p.city,
    p.state,
    p.religion,
    p.caste
FROM users u
JOIN user_profiles p
ON u.id = p.user_id
" . $where . "
ORDER BY u.id DESC
LIMIT $limit OFFSET $offset
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    $types,
    ...$params
);

$stmt->execute();

$profiles = $stmt->get_result();

$pageTitle = "Browse Profiles";

include 'includes/header.php';
?>

<section class="profiles-section">

    <h2>Browse Profiles</h2>

    <?php if (isset($_SESSION['error'])): ?>

        <div class="alert-error">

            <?= $_SESSION['error']; ?>

        </div>

        <?php
        unset($_SESSION['error']);
    endif;
    ?>

    <!-- Filters -->

    <form method="GET" id="filterForm" class="filters">

        <!-- State -->

        <select name="state" id="stateFilter">

            <option value="">
                All States
            </option>

            <?php foreach ($locations as $state => $cities): ?>

                <option value="<?= $state ?>" <?= ($_GET['state'] ?? '') == $state
                      ? 'selected'
                      : '' ?>>
                    <?= $state ?>
                </option>

            <?php endforeach; ?>

        </select>

        <!-- City -->

        <select name="city" id="cityFilter">

            <option value="">
                All Cities
            </option>

        </select>

        <!-- Min Age -->

        <select name="min_age">

            <option value="">
                Min Age
            </option>

            <?php for ($i = 18; $i <= 60; $i++): ?>

                <option value="<?= $i ?>" <?= ($_GET['min_age'] ?? '') == $i
                      ? 'selected'
                      : '' ?>>

                    <?= $i ?>

                </option>

            <?php endfor; ?>

        </select>

        <!-- Max Age -->

        <select name="max_age">

            <option value="">
                Max Age
            </option>

            <?php for ($i = 18; $i <= 60; $i++): ?>

                <option value="<?= $i ?>" <?= ($_GET['max_age'] ?? '') == $i
                      ? 'selected'
                      : '' ?>>

                    <?= $i ?>

                </option>

            <?php endfor; ?>

        </select>



        <select name="religion" id="religionFilter">

            <option value="">
                All Religions
            </option>

        </select>

        <select name="caste" id="casteFilter">

            <option value="">
                All Castes
            </option>

        </select>

        <!-- Reset -->

        <a href="profiles.php" class="btn-primary">
            Reset
        </a>

    </form>

    <br>

    <!-- Profiles -->

    <div class="profile-grid">

        <?php if ($profiles->num_rows > 0): ?>

            <?php while ($profile = $profiles->fetch_assoc()): ?>

                <?php

                $photo =
                    !empty($profile['profile_photo'])
                    ? $profile['profile_photo']
                    : 'assets/images/default-user.png';

                ?>

                <div class="profile-card">

                    <img src="<?= $photo ?>" alt="Profile">

                    <h3>
                        <?= htmlspecialchars($profile['full_name']) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($profile['age']) ?>
                        Years |

                        <?= htmlspecialchars($profile['city']) ?>,

                        <?= htmlspecialchars($profile['state']) ?>
                    </p>

                    <a href="public-profile.php?id=<?= $profile['id'] ?>" class="btn-primary">
                        View Profile
                    </a>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p style="
            text-align:center;
            width:100%;
            font-size:18px;
            ">
                No profiles found.
            </p>

        <?php endif; ?>

    </div>

    <!-- Pagination -->

    <?php if ($totalPages > 1): ?>

        <div style="
    margin-top:30px;
    text-align:center;
    ">

            <?php if ($page > 1): ?>

                <a href="?<?= http_build_query(
                    array_merge(
                        $_GET,
                        ['page' => $page - 1]
                    )
                ) ?>" class="btn-primary">
                    Previous
                </a>

            <?php endif; ?>

            <span style="
        margin:0 15px;
        font-weight:bold;
        ">
                Page <?= $page ?>
                of
                <?= $totalPages ?>
            </span>

            <?php if ($page < $totalPages): ?>

                <a href="?<?= http_build_query(
                    array_merge(
                        $_GET,
                        ['page' => $page + 1]
                    )
                ) ?>" class="btn-primary">
                    Next
                </a>

            <?php endif; ?>

        </div>

    <?php endif; ?>

</section>

<script type="module">

    const form =
        document.getElementById('filterForm');

    const stateFilter =
        document.getElementById('stateFilter');

    const cityFilter =
        document.getElementById('cityFilter');

    const locations =
        <?= json_encode($locations) ?>;

    const religionData = await fetch(
        'assets/data/religion-caste.json'
    ).then(res => res.json());

    /*
    |--------------------------------------------------------------------------
    | TOM SELECT INIT
    |--------------------------------------------------------------------------
    */

    $('#stateFilter').select2({
        placeholder: 'All States',
        width: '100%'
    });

    $('#cityFilter').select2({
        placeholder: 'All Cities',
        width: '100%'
    });

    $('#religionFilter').select2({
        placeholder: 'All Religions',
        width: '100%'
    });

    $('#casteFilter').select2({
        placeholder: 'All Castes',
        width: '100%'
    });

    $('select[name="min_age"]').select2({
        minimumResultsForSearch: Infinity,
        width: '100%'
    });

    $('select[name="max_age"]').select2({
        minimumResultsForSearch: Infinity,
        width: '100%'
    });



    /*
    |--------------------------------------------------------------------------
    | LOAD CITIES
    |--------------------------------------------------------------------------
    */

    function loadCities(
        state,
        selectedCity = ''
    ) {

        $('#cityFilter').empty();

        $('#cityFilter').append(`
        <option value="">
            All Cities
        </option>
    `);

        let cities =
            locations[state] || [];

        cities.forEach(city => {

            $('#cityFilter').append(`
            <option value="${city}">
                ${city}
            </option>
        `);

        });

        $('#cityFilter')
            .val(selectedCity)
            .trigger('change');

    }

    /*
    |--------------------------------------------------------------------------
    | LOAD RELIGIONS
    |--------------------------------------------------------------------------
    */

    function loadReligions(
        selectedReligion = ''
    ) {

        $('#religionFilter').empty();

        $('#religionFilter').append(`
        <option value="">
            All Religions
        </option>
    `);

        religionData.religion.forEach(item => {

            $('#religionFilter').append(`
            <option value="${item.name}">
                ${item.name}
            </option>
        `);

        });

        $('#religionFilter')
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

        $('#casteFilter').empty();

        $('#casteFilter').append(`
        <option value="">
            All Castes
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

            $('#casteFilter').append(`
            <option value="${caste.name}">
                ${caste.name}
            </option>
        `);

        });

        $('#casteFilter')
            .val(selectedCaste)
            .trigger('change');

    }

    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    loadCities(
        stateFilter.value,
        "<?= $_GET['city'] ?? '' ?>"
    );

    loadReligions(
        "<?= $_GET['religion'] ?? '' ?>"
    );

    loadCastes(
        "<?= $_GET['religion'] ?? '' ?>",
        "<?= $_GET['caste'] ?? '' ?>"
    );

    /*
    |--------------------------------------------------------------------------
    | EVENTS
    |--------------------------------------------------------------------------
    */

    $('#stateFilter').on(
        'change',
        function () {

            loadCities($(this).val());

            form.submit();

        }
    );

    $('#cityFilter').on(
        'change',
        function () {

            form.submit();

        }
    );

    $('#religionFilter').on(
        'change',
        function () {

            loadCastes($(this).val());

            form.submit();

        }
    );

    $('#casteFilter').on(
        'change',
        function () {

            form.submit();

        }
    );

    $('select[name="min_age"]').on(
        'change',
        function () {

            form.submit();

        }
    );

    $('select[name="max_age"]').on(
        'change',
        function () {

            form.submit();

        }
    );



</script>

<?php include 'includes/footer.php'; ?>
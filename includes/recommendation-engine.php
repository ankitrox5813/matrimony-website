<?php

function getRecommendedMatches(
    $conn,
    $userId,
    $filters = []
) {

    $profiles = [];

    $stmt = $conn->prepare(
        "SELECT *
         FROM partner_preferences
         WHERE user_id = ?"
    );

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();

    $preferences =
        $stmt
            ->get_result()
            ->fetch_assoc();

    if (!$preferences) {

        return [];
    }

    $stmt = $conn->prepare(
        "SELECT gender
         FROM users
         WHERE id = ?"
    );

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();

    $user =
        $stmt
            ->get_result()
            ->fetch_assoc();

    $preferredGender = null;

    if (
        ($user['gender'] ?? '')
        === 'Male'
    ) {

        $preferredGender = 'Female';

    } elseif (
        ($user['gender'] ?? '')
        === 'Female'
    ) {

        $preferredGender = 'Male';
    }

    if (!$preferredGender) {

        return [];
    }

    $where =
    "WHERE u.id != ?
     AND u.gender = ?";

$params = [
    $userId,
    $preferredGender
];

$types = "is";

if (!empty($filters['state'])) {

    $where .=
        " AND p.state = ?";

    $params[] =
        $filters['state'];

    $types .= "s";
}

if (!empty($filters['city'])) {

    $where .=
        " AND p.city = ?";

    $params[] =
        $filters['city'];

    $types .= "s";
}

if (!empty($filters['religion'])) {

    $where .=
        " AND p.religion = ?";

    $params[] =
        $filters['religion'];

    $types .= "s";
}

if (!empty($filters['caste'])) {

    $where .=
        " AND p.caste = ?";

    $params[] =
        $filters['caste'];

    $types .= "s";
}

if (!empty($filters['min_age'])) {

    $where .=
        " AND u.age >= ?";

    $params[] =
        (int)$filters['min_age'];

    $types .= "i";
}

if (!empty($filters['max_age'])) {

    $where .=
        " AND u.age <= ?";

    $params[] =
        (int)$filters['max_age'];

    $types .= "i";
}

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
    p.caste,
    p.education,
    p.marital_status
FROM users u
JOIN user_profiles p
ON u.id = p.user_id
" . $where;

$stmt =
    $conn->prepare($sql);

    $stmt->bind_param($types, ...$params);

    $stmt->execute();

    $result =
        $stmt->get_result();

    while (
        $profile =
        $result->fetch_assoc()
    ) {

        $stmtHobby = $conn->prepare(
            "SELECT h.name
             FROM user_hobbies uh
             JOIN hobbies h
             ON h.id = uh.hobby_id
             WHERE uh.user_id = ?"
        );

        $stmtHobby->bind_param(
            "i",
            $profile['id']
        );

        $stmtHobby->execute();

        $candidateHobbies = [];

        $hobbyResult =
            $stmtHobby->get_result();

        while (
            $row =
            $hobbyResult->fetch_assoc()
        ) {

            $candidateHobbies[] =
                $row['name'];
        }

        $match =
            calculateMatch(
                $profile,
                $preferences,
                $candidateHobbies
            );

        $profile['match_score'] =
            $match['score'];

        $profile['match_reasons'] =
            $match['reasons'];

        if (
            $profile['match_score'] <= 0
        ) {

            continue;
        }

        $profiles[] =
            $profile;
    }

    usort(
        $profiles,
        function ($a, $b) {

            return
                $b['match_score']
                <=>
                $a['match_score'];
        }
    );

    return $profiles;
}
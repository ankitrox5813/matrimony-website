<?php

function calculateMatch(
    $profile,
    $preferences,
    $candidateHobbies = []
) {

    $score = 0;

    $reasons = [];

    /*
    |--------------------------------------------------------------------------
    | AGE
    |--------------------------------------------------------------------------
    */

    if (
        !empty($preferences['min_age']) &&
        !empty($preferences['max_age'])
    ) {

        if (
            $profile['age'] >= $preferences['min_age']
            &&
            $profile['age'] <= $preferences['max_age']
        ) {

            $score += 20;

            $reasons[] =
                'Preferred Age';

        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELIGION
    |--------------------------------------------------------------------------
    */

    $religions =
        json_decode(
            $preferences['religions'] ?: '[]',
            true
        ) ?: [];

    if (
        !empty($religions)
        &&
        in_array(
            $profile['religion'] ?? '',
            $religions
        )
    ) {

        $score += 20;

        $reasons[] =
            'Preferred Religion';
    }

    /*
    |--------------------------------------------------------------------------
    | CASTE
    |--------------------------------------------------------------------------
    */

    $castes =
        json_decode(
            $preferences['castes'] ?: '[]',
            true
        ) ?: [];

    if (
        !empty($castes)
        &&
        in_array(
            $profile['caste'] ?? '',
            $castes
        )
    ) {

        $score += 15;

        $reasons[] =
            'Preferred Caste';
    }

    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */

    $states =
        json_decode(
            $preferences['states'] ?: '[]',
            true
        ) ?: [];

    if (
        !empty($states)
        &&
        in_array(
            $profile['state'] ?? '',
            $states
        )
    ) {

        $score += 10;

        $reasons[] =
            'Preferred State';
    }

    /*
    |--------------------------------------------------------------------------
    | CITY
    |--------------------------------------------------------------------------
    */

    $cities =
        json_decode(
            $preferences['cities'] ?: '[]',
            true
        ) ?: [];

    if (
        !empty($cities)
        &&
        in_array(
            $profile['city'] ?? '',
            $cities
        )
    ) {

        $score += 10;

        $reasons[] =
            'Preferred City';
    }

    /*
    |--------------------------------------------------------------------------
    | HOBBIES
    |--------------------------------------------------------------------------
    */

    $preferredHobbies =
        json_decode(
            $preferences['hobbies'] ?: '[]',
            true
        ) ?: [];

    if (
        !empty($preferredHobbies)
    ) {

        $common =
            array_intersect(
                array_map('strtolower', $preferredHobbies),
                array_map('strtolower', $candidateHobbies)
            );

        $count =
            count($common);

        if ($count > 0) {

            $score += min(
                15,
                $count * 5
            );

            $reasons[] =
                $count .
                ' Common Hobbies';
        }
    }

    /*
|--------------------------------------------------------------------------
| EDUCATION
|--------------------------------------------------------------------------
*/

    $educations =
        json_decode(
            $preferences['educations'] ?: '[]',
            true
        ) ?: [];

    if (
        !empty($educations)
        &&
        in_array(
            $profile['education'] ?? '',
            $educations
        )
    ) {

        $score += 5;

        $reasons[] =
            'Preferred Education';
    }

    /*
    |--------------------------------------------------------------------------
    | MARITAL STATUS
    |--------------------------------------------------------------------------
    */

    $statuses =
        json_decode(
            $preferences['marital_statuses'] ?: '[]',
            true
        ) ?: [];

    if (
        !empty($statuses)
        &&
        in_array(
            $profile['marital_status'] ?? '',
            $statuses
        )
    ) {

        $score += 5;

        $reasons[] =
            'Preferred Marital Status';
    }

    return [

        'score' =>
            min(100, $score),

        'reasons' =>
            $reasons

    ];
}
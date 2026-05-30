<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($pageTitle)) {
    $pageTitle = "Aurivah";
}

$currentPage = basename($_SERVER['PHP_SELF'], '.php');

$pageStyleMap = [
    'index' => ['home.css'],
    'login' => ['auth.css'],
    'register' => ['auth.css'],
    'forgot-password' => ['auth.css'],
    'create-profile' => ['dashboard.css', 'forms.css'],
    'edit-profile' => ['dashboard.css', 'forms.css'],
    'partner-preferences' => ['dashboard.css', 'forms.css'],
    'dashboard' => ['dashboard.css'],
    'profiles' => [],
    'preferred-profiles' => [],
    'view-profile' => ['dashboard.css'],
    'public-profile' => ['dashboard.css'],
];

$extraStyles = $pageStyles ?? ($pageStyleMap[$currentPage] ?? []);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/app-extensions.css">
    <?php foreach ($extraStyles as $styleFile): ?>
        <link rel="stylesheet" href="assets/css/<?= htmlspecialchars($styleFile) ?>">
    <?php endforeach; ?>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>

    <?php include __DIR__ . '/navbar.php'; ?>
    <main>

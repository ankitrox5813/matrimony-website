<?php

define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');      // change to 3307 if Step 2 showed 3307
define('DB_USER', 'root');
define('DB_PASS', 'aurivah123');
define('DB_NAME', 'vivahsangam');

$conn = mysqli_connect(
    DB_HOST,
    DB_USER,
    DB_PASS,
    DB_NAME,
    DB_PORT
);

if(!$conn){
    die("❌ Connection Failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
?>
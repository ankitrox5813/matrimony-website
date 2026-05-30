<?php

$conn = mysqli_connect(
    "127.0.0.1",
    "root",
    "",
    "aurivah",
    3307
);

if(!$conn){

    die("Database Connection Failed");

}

?>
<?php

$host = 'localhost';
$user = 'dbuser';
$pass = 'DBuser123!';
$db = 'asset';

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
} else {
    echo 'Successful connection!';
}

?>
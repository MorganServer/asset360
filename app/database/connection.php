<?php

$host = "localhost";
$username = "dbuser";
$password = "DBuser123!";
$dbname = "asset_management";

$conn = mysqli_connect($host, $username, $password, $dbname);

if(!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
} else {
    // echo("Connection Success!");
}

?>
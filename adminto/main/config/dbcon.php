<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "linenese";

//creating database connection

$con = mysqli_connect($host, $username, $password, $dbname);

//check databse connection

if (!$con) {
    die("connection failed". mysqli_connect_error());
}


?>
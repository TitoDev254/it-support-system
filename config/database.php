<?php

$host = "sql104.infinityfree.com";
$username = "if0_42806844";
$password = "ItB08VsxhtSvU";
$database = "if0_42806844_it_support_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

?>
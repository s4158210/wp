<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "skillswap";   // 👈 this must match the DB you created/imported

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
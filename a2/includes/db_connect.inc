<?php

// ✅ Local XAMPP settings
$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "skillswap";               // your local DB

// detect if running on Titan server or locally
if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
    // ✅ Titan server settings
    $host   = "talsprddb02.int.its.rmit.edu.au";  // Titan MySQL host
    $user   = "s4158210";                // your student username
    $pass   = "p20050121!";  // replace with your Titan MySQL password
    $dbname = "s4158210";                // Titan DB name (usually same as username)
}


// connect
$conn = mysqli_connect($host, $user, $pass, $dbname);

// check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

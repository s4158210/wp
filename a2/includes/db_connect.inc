<?php
// Detect environment by server name
$serverName = $_SERVER['SERVER_NAME'];

if ($serverName === "localhost") {
    // XAMPP local connection
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "skillswap";
} elseif (strpos($serverName, "titan.csit.rmit.edu.au") !== false) {
    // Titan server connection
    $host = "titan.csit.rmit.edu.au";
    $user = "s4158210";          // your student username
    $pass = "YOUR_DB_PASSWORD";  // replace with your Titan DB password (not RMIT login password!)
    $dbname = "s4158210";        // usually same as your username
} elseif ($serverName === "jacob5.myuni.edu") {
    // Jacob5 connection
    $host = "jacob5.myuni.edu";
    $user = "your_jacob_user";
    $pass = "your_jacob_password";
    $dbname = "skillswap";
} else {
    die("❌ Unknown server environment: " . $serverName);
}

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

<?php
$host = "localhost";
$user = "root";      // change if using different db user
$pass = "";          // set password if exists
$db   = "forum_db";
$port = 3307; // XAMPP default MySQL port

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>

<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "pharmacy_system"; // Your database name

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

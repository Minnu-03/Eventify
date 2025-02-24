<?php
// Database connection details
$servername = "localhost";
$username = "root"; 
$password = "Minnu";     
$dbname = "event_booking_system"; 


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); 
}
?>

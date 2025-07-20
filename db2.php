<?php
$servername = "localhost";
$username = "root"; // Replace with your username
$password = "";     // Replace with your password
$dbname = "college_voting_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

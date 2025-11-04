<?php
// db.php

// Database configuration
$dbHost = 'localhost';
$dbUser = 'root'; // Replace with your database username
$dbPass = ''; // Replace with your database password if you have one
$dbName = 'mtp_db';

// Create a new mysqli object to connect to the database
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check for a connection error
if ($conn->connect_error) {
    // If there is an error, stop the script and display the error message
    die("Connection failed: " . $conn->connect_error);
}

// Start a new session or resume the existing one
session_start();
?>

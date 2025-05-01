<?php
// Database configuration
$servername = "127.0.0.1";
$username = "root";    // Default XAMPP username
$password = "";        // Default XAMPP password
$dbname = "toolbox_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");

// Start session
session_start();

// Base URL (adjust to your project folder)
define('BASE_URL', 'http://localhost/toolbox/');
?>
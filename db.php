<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "complaint_system";

// Create a database connection
$conn = mysqli_connect('localhost', 'root', '', 'complaint_system', 3307); //

// Check the connection
if (!$conn) {
    // Log the error message and show a generic error
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Database connection failed. Please try again later.");
}

?>

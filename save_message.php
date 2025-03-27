<?php
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "complaint_system";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the request
$sender = $_POST['sender'];
$message = $_POST['message'];

// Insert the message into the database
$sql = "INSERT INTO messages (sender, message) VALUES ('$sender', '$message')";
if ($conn->query($sql) === TRUE) {
    echo "Message sent!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "complaint_system";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve all messages
$sql = "SELECT * FROM messages ORDER BY created_at ASC";
$result = $conn->query($sql);

$messages = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

// Return messages as JSON
echo json_encode($messages);

$conn->close();
?>

<?php
include 'db.php';
session_start();
$user_id = $_SESSION['user_id'];  // Current User ID
$chat_with = $_POST['receiver_id'];  // The user to chat with

$sql = "SELECT * FROM messages WHERE (sender_id = '$user_id' AND receiver_id = '$chat_with') 
        OR (sender_id = '$chat_with' AND receiver_id = '$user_id') ORDER BY sent_at ASC";
$result = mysqli_query($conn, $sql);

$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
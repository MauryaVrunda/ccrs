<?php
ob_start(); // Start output buffering
session_start();
include 'db.php';

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['user_id']) && !empty($_POST['password'])) {
        $user_id = trim($_POST['user_id']);
        $password = trim($_POST['password']);

        $stmt = $conn->prepare("SELECT  user_id, name, password FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['name'];

                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Invalid email or password!";
            }
        } else {
            $message = "User not found!";
        }
        $stmt->close();
    } else {
        $message = "Please enter email and password!";
    }
}

ob_end_flush(); // Flush the output buffer
?>
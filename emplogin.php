<?php
ob_start(); // Start output buffering
session_start();
include 'db.php';

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['department']) && !empty($_POST['password'])) {
       
        $department = trim($_POST['department']);
        $dept_password = trim($_POST['password']);
        
        $stmt = $conn->prepare("SELECT * FROM department WHERE department = ?");
        $stmt->bind_param("s", $department);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($dept_password, $user['password'])) {

                $_SESSION['department'] = $department;
                $_SESSION['deptS_id'] = $dept_id;
                $_SESSION['password'] = $dept_password;
                header("Location: dashboardemp.php");
                exit();
            } else {
                
                $message = "Invalid password!";
            }
        } 
        $stmt->close();
    } else {
    
        exit;
        $message = "Please select deplartment and enter password!";
    }
}

ob_end_flush(); // Flush the output buffer
?>
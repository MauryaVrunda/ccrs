<?php
session_start();
if (isset($_POST['otp'])) {
    if ($_POST['otp'] == $_SESSION['otp']) {
        echo "OTP verified successfully!";
        unset($_SESSION['otp']);
    } else {
        echo "Invalid OTP. Try again.";
    }
}
?>
<?php
session_start();

if (isset($_POST['otp'])) {
    $enteredOtp = $_POST['otp'];

    // Check if the OTP matches the one stored in the session
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp) {
        echo "success"; // OTP verified successfully
    } else {
        echo "fail"; // Invalid OTP
    }
}
?>

<?php
session_start(); // Start session to store OTP

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    
    // Generate random OTP
    $otp = rand(100000, 999999);

    // Store OTP in session
    $_SESSION['otp'] = $otp;
    
    // Send OTP to the provided email (using PHP's mail function or another method)
    mail($email, "Your OTP", "Your OTP is: $otp");

    echo $otp; // Send OTP back as a response to JavaScript
}
?>

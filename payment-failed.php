<?php 
// if(isset($_GET)){
    
//     echo "<pre>";
//     print_r($_GET);
//     echo "</p>";
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 450px;
        }
        .icon {
            font-size: 80px;
            color: #ff4d4f;
            margin-bottom: 20px;
        }
        .heading {
            font-size: 24px;
            font-weight: 700;
            color: #333333;
            margin-bottom: 10px;
        }
        .message {
            font-size: 16px;
            color: #555555;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .help {
            margin-top: 20px;
            font-size: 14px;
            color: #777777;
        }
        .help a {
            color: #007bff;
            text-decoration: none;
        }
        .help a:hover {
            text-decoration: underline;
        }
    </style>
</head>   
<body>
    <div class="container">
        <div class="icon">❌</div>
        <div class="heading">Payment Failed</div>
        <div class="message">
            We’re sorry, but your payment could not be processed at this time. Please check your payment details or try again later.
        </div>
        <a href="rent.php" class="button">Retry Payment</a>
        <div class="help">
            Need help? <a href="/support">Contact Support</a>
        </div>
    </div>

    <?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

?>
</body>
</html>

 
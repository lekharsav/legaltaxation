<?php
include("./db.php");   


    $razorpay_mode = 'test';

    $razorpay_test_key = 'rzp_test_HxGU4k29y1tV9I'; // Your Test Key
    $razorpay_test_secret_key = 'KdWPIavuNysEBucKy5cyzlQv'; // Your Test Secret Key

    $razorpay_live_key = 'rzp_live_ezOvHGqHiSf1DL';
    $razorpay_live_secret_key = 'YmGJQDICIfYfh4ml23BNkxXr';

    if($razorpay_mode == 'test') {
        $razorpay_key = $razorpay_test_key;
        $authAPIkey = "Basic " . base64_encode($razorpay_test_key . ":" . $razorpay_test_secret_key);
    } else {
        $authAPIkey = "Basic " . base64_encode($razorpay_live_key . ":" . $razorpay_live_secret_key);
        $razorpay_key = $razorpay_live_key;
    }



 
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.razorpay.com/v1/orders/order_Ph3metWWMXuppx',
        CURLOPT_RETURNTRANSFER => true,
       
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => json_encode($postdata),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: '.$authAPIkey
        ),
    ));

    echo $response = curl_exec($curl);
    curl_close($curl);
   
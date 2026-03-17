<?php
include("./db.php");   
header('Content-Type: application/json');

if(isset($_POST['action']) && $_POST['action'] == 'payOrder') {
    
    $razorpay_mode = 'test';
    $razorpay_test_key = 'rzp_test_HxGU4k29y1tV9I';
    $razorpay_test_secret_key = 'KdWPIavuNysEBucKy5cyzlQv';

    if($razorpay_mode == 'test') {
        $razorpay_key = $razorpay_test_key;
        $authAPIkey = "Basic " . base64_encode($razorpay_test_key . ":" . $razorpay_test_secret_key);
    }

    $order_id = uniqid(); 

    $billing_name = $_POST['billing_name'];
    $billing_mobile = $_POST['billing_mobile'];
    $billing_email = $_POST['billing_email'];
    $cid = $_POST['cid'];
    $sid = $_POST['sid'];
    $paymentOption = $_POST['paymentOption'];
    $payAmount = $_POST['payAmount'];
    
    // Customer-specific data (force customer logic)
    $user_type = 'customer'; // Force customer type
    $client_count = 1; // Customers always buy for 1 client
    $is_partner_price_applied = 0; // No partner pricing for customers
    $actual_price_paid = $payAmount;

    // Get service details
    $service_sql = $con->query("SELECT * FROM service WHERE id='$sid'");
    $service_row = $service_sql->fetch_assoc();
    $service_title = isset($service_row['title']) ? $service_row['title'] : 'Service';
    $regular_price = isset($service_row['o_price']) ? $service_row['o_price'] : 0;
    
    // Get customer details
    $customer_sql = $con->query("SELECT * FROM customer WHERE id='$cid'");
    $customer_row = $customer_sql->fetch_assoc();
    $customer_name = isset($customer_row['name']) ? $customer_row['name'] : 'Customer';

    // Calculate pricing (always regular price for customers)
    $unit_price = $regular_price;
    $calculated_total = $regular_price;

    $note = "Payment of amount Rs. " . $payAmount . " (Customer Purchase)";

    // Insert into razorpaybill
    $sql123 = "INSERT INTO razorpaybill (
        order_id,
        cid,
        user_type,
        partner_id,
        client_count,
        service_id,
        is_partner_price_applied,
        billing_name, 
        billing_mobile, 
        billing_email, 
        payment_option,
        pay_amount,
        plan_name
    ) VALUES (
        '".$order_id."',
        '".$cid."',
        'customer',
        NULL,
        '1',
        '".$sid."',
        '0',
        '".$billing_name."', 
        '".$billing_mobile."', 
        '".$billing_email."', 
        '".$paymentOption."',
        '".$payAmount."',
        '".$service_title."'
    )";
    $res = mysqli_query($con, $sql123);

    // Insert into 'apply' table (customer version)
    $applyInsert = $con->query("INSERT INTO apply(
        cid, 
        sid, 
        created, 
        status,
        user_type,
        client_count,
        is_partner_price_applied,
        actual_price_paid,
        unit_price,
        total_amount,
        payment_method,
        razorpay_order_id,
        partner_id,
        all_clients_submitted,
        client_data_complete
    ) VALUES(
        '$cid', 
        '$sid', 
        NOW(),
        '0',
        'customer',
        '1',
        '0',
        '$actual_price_paid',
        '$unit_price',
        '$calculated_total',
        '$paymentOption',
        '',
        NULL,
        '1',
        '1'
    )");
    
    if($applyInsert === true) {
        $application_id = $con->insert_id;
        
        // Handle service-specific form fields
        if(isset($_POST['service_fields_data'])) {
            $serviceFieldsData = json_decode($_POST['service_fields_data'], true);
            
            if(is_array($serviceFieldsData)) {
                foreach($serviceFieldsData as $field_key => $field_value) {
                    preg_match('/\[(\d+)\]/', $field_key, $matches);
                    if(isset($matches[1])) {
                        $field_id = $matches[1];
                        
                        if(is_array($field_value)) {
                            $field_value = implode(', ', $field_value);
                        }
                        
                        // Save with client_index = 1 (for single client)
                        $con->query("INSERT INTO service_form_responses 
                                     (application_id, client_index, field_id, field_value) 
                                     VALUES ('$application_id', 1, '$field_id', '" . mysqli_real_escape_string($con, $field_value) . "')");
                    }
                }
            }
        }
        
        // Log the purchase
        $log_description = "Customer purchase: " . $service_title . " for ₹" . $payAmount;
        
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $con->query("INSERT INTO activity_logs (user_id, user_type, action, description, ip_address, user_agent, created_at) 
                     VALUES ('$cid', 'customer', 'customer_service_purchase', '$log_description', '$ip_address', '$user_agent', NOW())");
        
    } else {
        echo json_encode(['res' => 'error', 'message' => 'Error inserting application data: ' . $con->error]);
        exit;
    }

    // Create Razorpay order
    $postdata = array(
        "amount" => $payAmount * 100,
        "currency" => "INR",
        "receipt" => $order_id,
        "notes" => array(
            "service_id" => $sid,
            "user_id" => $cid,
            "user_type" => 'customer',
            "client_count" => 1,
            "service_name" => $service_title,
            "application_id" => $application_id ?? 0
        )
    );
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($postdata),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: '.$authAPIkey
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $orderRes = json_decode($response);

    if(isset($orderRes->id)) {
        $rpay_order_id = $orderRes->id;
        
        // Update razorpay_order_id
        $con->query("UPDATE apply SET razorpay_order_id = '$rpay_order_id' WHERE id = '$application_id'");

        $dataArr = array(
            'amount' => $payAmount,
            'description' => "Payment for " . $service_title,
            'rpay_order_id' => $rpay_order_id,
            'name' => $billing_name,
            'email' => $billing_email,
            'mobile' => $billing_mobile
        );

        // Save to rayzerdata
        $jsonData = json_encode($dataArr);
        $con->query("INSERT INTO rayzerdata (data, created) VALUES ('$jsonData', NOW())");

        echo json_encode([
            'res' => 'success', 
            'order_number' => $order_id, 
            'userData' => $dataArr, 
            'razorpay_key' => $razorpay_key,
            'application_id' => $application_id ?? 0
        ]);
        exit;
    } else {
        echo json_encode([
            'res' => 'error', 
            'order_id' => $order_id, 
            'info' => 'Error with payment',
            'razorpay_response' => $response
        ]); 
        exit;
    }
} else {
    echo json_encode(['res' => 'error', 'message' => 'No action specified']); 
    exit;
}
?>
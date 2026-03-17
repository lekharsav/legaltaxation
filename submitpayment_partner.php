<?php
include("./db.php");
header('Content-Type: application/json');

if(isset($_POST['action']) && $_POST['action'] == 'payOrder') {
    // Extract all data
    $billing_name = $_POST['billing_name'];
    $billing_mobile = $_POST['billing_mobile'];
    $billing_email = $_POST['billing_email'];
    $cid = $_POST['cid'];
    $sid = $_POST['sid'];
    $user_type = $_POST['user_type'];
    $payAmount = $_POST['payAmount'];
    $client_count = intval($_POST['client_count']);
    $is_partner_price_applied = intval($_POST['is_partner_price_applied']);
    $actual_price_paid = $_POST['actual_price_paid'];
    
    // Validate user is partner
    if ($user_type != 'partner') {
        echo json_encode(['res' => 'error', 'message' => 'This endpoint is for partners only']);
        exit;
    }
    
    // Get service details
    $service_sql = $con->query("SELECT * FROM service WHERE id='$sid'");
    $service_row = $service_sql->fetch_assoc();
    $service_title = $service_row['title'];
    
    // Calculate unit price based on partner pricing logic
    $unit_price = $service_row['o_price']; // Default to regular price
    
    // Check if partner price should be applied
    if ($is_partner_price_applied && $service_row['partner_o_price'] > 0) {
        $unit_price = $service_row['partner_o_price'];
    }
    
    // Generate unique order ID
    $order_id = uniqid();
    
    // Razorpay credentials
    $razorpay_mode = 'test';
    $razorpay_test_key = 'rzp_test_HxGU4k29y1tV9I';
    $razorpay_test_secret_key = 'KdWPIavuNysEBucKy5cyzlQv';
    
    if($razorpay_mode == 'test') {
        $razorpay_key = $razorpay_test_key;
        $authAPIkey = "Basic " . base64_encode($razorpay_test_key . ":" . $razorpay_test_secret_key);
    }
    
    // Insert into razorpaybill
    $sql = "INSERT INTO razorpaybill (
        order_id, cid, user_type, partner_id, client_count, service_id,
        is_partner_price_applied, billing_name, billing_mobile, billing_email,
        payment_option, pay_amount, plan_name
    ) VALUES (
        '$order_id', '$cid', 'partner', '$cid',
        '$client_count', '$sid', '$is_partner_price_applied', '$billing_name',
        '$billing_mobile', '$billing_email', 'razorpay', '$payAmount',
        'Partner Purchase: $service_title ($client_count clients)'
    )";
    $con->query($sql);
    
    // Insert into 'apply' table
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
        'partner',
        '$client_count',
        '$is_partner_price_applied',
        '$actual_price_paid',
        '$unit_price',
        '$payAmount',
        'razorpay',
        '',
        '$cid',
        '1',
        '1'
    )");
    
    if($applyInsert) {
        $application_id = $con->insert_id;
        
        // Save client forms data if provided
        if(isset($_POST['client_forms_data'])) {
            $clientFormsData = json_decode($_POST['client_forms_data'], true);
            $formFieldsData = isset($_POST['form_fields_data']) ? json_decode($_POST['form_fields_data'], true) : [];
            
            if(is_array($clientFormsData)) {
                foreach($clientFormsData as $clientIndex => $clientData) {
                    if(isset($clientData['data']) && is_array($clientData['data'])) {
                        foreach($clientData['data'] as $fieldId => $fieldValue) {
                            // Insert into service_form_responses with client_index
                            $con->query("INSERT INTO service_form_responses 
                                       (application_id, client_index, field_id, field_value)
                                       VALUES ('$application_id', '$clientIndex', '$fieldId', '" . 
                                       mysqli_real_escape_string($con, $fieldValue) . "')");
                        }
                    }
                    
                    // Log client submission
                    $con->query("INSERT INTO activity_logs (user_id, user_type, action, description, created_at) 
                               VALUES ('$cid', 'partner', 'client_form_submitted', 
                               'Client $clientIndex form submitted for application $application_id', NOW())");
                }
            }
        }
        
        // Create Razorpay order
        $postdata = array(
            "amount" => $payAmount * 100,
            "currency" => "INR",
            "receipt" => $order_id,
            "notes" => array(
                "service_id" => $sid,
                "user_id" => $cid,
                "user_type" => 'partner',
                "client_count" => $client_count,
                "application_id" => $application_id,
                "is_partner_price_applied" => $is_partner_price_applied
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
            
            // Update razorpay_order_id in apply table
            $con->query("UPDATE apply SET razorpay_order_id = '$rpay_order_id' WHERE id = '$application_id'");
            
            // Prepare response data
            $dataArr = array(
                'amount' => $payAmount,
                'description' => "Payment for $service_title ($client_count clients)",
                'rpay_order_id' => $rpay_order_id,
                'name' => $billing_name,
                'email' => $billing_email,
                'mobile' => $billing_mobile
            );
            
            // Save to rayzerdata
            $jsonData = json_encode($dataArr);
            $con->query("INSERT INTO rayzerdata (data, created) VALUES ('$jsonData', NOW())");
            
            // Log the purchase
            $log_description = "Partner purchase: $service_title for $client_count clients - ₹$payAmount";
            if ($is_partner_price_applied) {
                $log_description .= " (Partner price applied)";
            } else {
                $log_description .= " (Regular price)";
            }
            
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $con->query("INSERT INTO activity_logs (user_id, user_type, action, description, ip_address, user_agent, created_at) 
                         VALUES ('$cid', 'partner', 'partner_service_purchase', '$log_description', '$ip_address', '$user_agent', NOW())");
            
            echo json_encode([
                'res' => 'success',
                'order_number' => $order_id,
                'userData' => $dataArr,
                'razorpay_key' => $razorpay_key,
                'application_id' => $application_id,
                'message' => 'Payment initiated successfully'
            ]);
        } else {
            echo json_encode([
                'res' => 'error', 
                'message' => 'Razorpay order creation failed',
                'razorpay_response' => $response
            ]);
        }
    } else {
        echo json_encode(['res' => 'error', 'message' => 'Failed to create application: ' . $con->error]);
    }
} else {
    echo json_encode(['res' => 'error', 'message' => 'Invalid request']);
}
?>
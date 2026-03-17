<?php
include('db.php');
header('Content-Type: application/json');

if(!isset($_POST['action']) || $_POST['action'] != 'process_partner_payment') {
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
    exit;
}

$partner_id = intval($_POST['partner_id']);
$billing_name = mysqli_real_escape_string($con, $_POST['billing_name']);
$billing_mobile = mysqli_real_escape_string($con, $_POST['billing_mobile']);
$billing_email = mysqli_real_escape_string($con, $_POST['billing_email']);

// Get all cart items for this partner
$cart_sql = $con->query("SELECT pc.*, s.title as service_name, s.o_price,
                         pcl.full_name as client_name, pcl.contact, pcl.email,
                         pcl.aadhar_upload, pcl.pan_upload, pcl.email_verified
                         FROM partner_cart pc 
                         JOIN service s ON pc.service_id = s.id 
                         JOIN partner_clients pcl ON pc.client_id = pcl.client_id AND pc.partner_id = pcl.partner_id
                         WHERE pc.partner_id = '$partner_id' 
                         ORDER BY pc.created_at DESC");

if($cart_sql->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Cart is empty']);
    exit;
}

$cart_items = [];
$total_amount = 0;
while($item = $cart_sql->fetch_assoc()) {
    $cart_items[] = $item;
    $total_amount += floatval($item['unit_price']);
}

// Razorpay configuration
$razorpay_mode = 'test';
$razorpay_test_key = 'rzp_test_HxGU4k29y1tV9I';
$razorpay_test_secret_key = 'KdWPIavuNysEBucKy5cyzlQv';

if($razorpay_mode == 'test') {
    $razorpay_key = $razorpay_test_key;
    $authAPIkey = "Basic " . base64_encode($razorpay_test_key . ":" . $razorpay_test_secret_key);
}

// Generate unique order ID
$order_id = uniqid() . '_' . $partner_id;

// Begin transaction
$con->begin_transaction();

try {
    $application_ids = [];
    $all_success = true;
    
    // Process each cart item
    foreach($cart_items as $item) {
        $client_id = $item['client_id'];
        $service_id = $item['service_id'];
        $unit_price = floatval($item['unit_price']);
        $service_fields_data = $item['service_fields_data'];
        
        // Insert into apply table
        $applyInsert = $con->query("INSERT INTO apply(
            cid, sid, created, status, user_type, client_count,
            is_partner_price_applied, actual_price_paid, unit_price,
            total_amount, payment_method, razorpay_order_id,
            partner_id, all_clients_submitted, client_data_complete
        ) VALUES(
            '$client_id', '$service_id', NOW(), '0', 'partner', '1',
            '0', '$unit_price', '$unit_price', '$unit_price',
            'razorpay', '', '$partner_id', '1', '1'
        )");
        
        if(!$applyInsert) {
            throw new Exception('Failed to create application: ' . $con->error);
        }
        
        $application_id = $con->insert_id;
        $application_ids[] = $application_id;
        
        // Save service form field responses
        if(!empty($service_fields_data)) {
            $fields = json_decode($service_fields_data, true);
            if(is_array($fields)) {
                foreach($fields as $field_id => $field_value) {
                    if(is_array($field_value)) {
                        $field_value = implode(', ', $field_value);
                    }
                    $field_value = mysqli_real_escape_string($con, $field_value);
                    $con->query("INSERT INTO service_form_responses 
                                 (application_id, client_index, field_id, field_value, partner_id) 
                                 VALUES ('$application_id', 1, '$field_id', '$field_value', '$partner_id')");
                }
            }
        }
    }
    
    // Create Razorpay order for total amount
    $postdata = array(
        "amount" => $total_amount * 100,
        "currency" => "INR",
        "receipt" => $order_id,
        "notes" => array(
            "partner_id" => $partner_id,
            "item_count" => count($cart_items),
            "application_ids" => implode(',', $application_ids)
        )
    );
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postdata),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: ' . $authAPIkey
        ),
    ));
    
    $response = curl_exec($curl);
    $curl_error = curl_error($curl);
    curl_close($curl);
    
    if($curl_error) {
        throw new Exception('Razorpay API error: ' . $curl_error);
    }
    
    $orderRes = json_decode($response);
    
    if(!isset($orderRes->id)) {
        $error_msg = isset($orderRes->error->description) ? $orderRes->error->description : 'Unknown Razorpay error';
        throw new Exception('Failed to create Razorpay order: ' . $error_msg);
    }
    
    $rpay_order_id = $orderRes->id;
    
    // Update all applications with Razorpay order ID
    $app_ids_str = implode(',', $application_ids);
    $con->query("UPDATE apply SET razorpay_order_id = '$rpay_order_id' WHERE id IN ($app_ids_str)");
    
    // Insert into razorpaybill
    $plan_name = "Partner purchase (" . count($cart_items) . " items)";
    $sql123 = "INSERT INTO razorpaybill (
        order_id, cid, user_type, partner_id, client_count,
        service_id, is_partner_price_applied, billing_name,
        billing_mobile, billing_email, payment_option, pay_amount, plan_name
    ) VALUES (
        '$order_id', '$partner_id', 'partner', '$partner_id',
        '" . count($cart_items) . "', '0', '0',
        '$billing_name', '$billing_mobile', '$billing_email',
        'razorpay', '$total_amount', '$plan_name'
    )";
    $con->query($sql123);
    
    // Prepare data for frontend
    $dataArr = array(
        'amount' => $total_amount,
        'description' => "Payment for " . count($cart_items) . " services",
        'rpay_order_id' => $rpay_order_id,
        'name' => $billing_name,
        'email' => $billing_email,
        'mobile' => $billing_mobile
    );
    
    // Save to rayzerdata
    $jsonData = json_encode($dataArr);
    $con->query("INSERT INTO rayzerdata (data, created) VALUES ('$jsonData', NOW())");
    
    // Clear the cart after successful processing
    $con->query("DELETE FROM partner_cart WHERE partner_id = '$partner_id'");
    
    $con->commit();
    
    echo json_encode([
        'success' => true,
        'order_number' => $order_id,
        'userData' => $dataArr,
        'razorpay_key' => $razorpay_key,
        'application_ids' => $application_ids,
        'total_amount' => $total_amount,
        'item_count' => count($cart_items)
    ]);
    
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
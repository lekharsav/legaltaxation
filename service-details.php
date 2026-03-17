<?php
include('db.php');
// ============================================
// ALL AJAX HANDLERS MUST COME FIRST
// ============================================
// Document upload handler
if(isset($_POST['upload_document']) && isset($_FILES['document_file'])) {
    header('Content-Type: application/json');
   
    $customer_id = $_POST['customer_id'];
    $document_type = $_POST['document_type'];
    $target_dir = "uploads/documents/";
   
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
   
    $file_extension = pathinfo($_FILES["document_file"]["name"], PATHINFO_EXTENSION);
    $new_filename = $document_type . '_' . $customer_id . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
   
    if (move_uploaded_file($_FILES["document_file"]["tmp_name"], $target_file)) {
        if(isset($_POST['is_partner_client']) && $_POST['is_partner_client'] == '1') {
            // For partner's client, we'll handle separately
            echo json_encode(['success' => true, 'file_path' => $target_file, 'temp' => true]);
        } else {
            // For regular customer
            $update_sql = "UPDATE customer SET " . $document_type . "_upload = '$target_file' WHERE id = '$customer_id'";
            $con->query($update_sql);
            echo json_encode(['success' => true, 'file_path' => $target_file]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'File upload failed']);
    }
    exit;
}
// Add to cart handler
if(isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    header('Content-Type: application/json');
   
    $partner_id = intval($_POST['partner_id']);
    $client_data = json_decode($_POST['client_data'], true);
    $service_id = intval($_POST['service_id']);
    $service_fields_data = $_POST['service_fields_data']; // This is already JSON string
    $unit_price = floatval($_POST['unit_price']);
   
    // Validate required fields
    if(empty($client_data['name']) || empty($client_data['contact']) || empty($client_data['email'])) {
        echo json_encode(['success' => false, 'error' => 'Missing required client information']);
        exit;
    }
   
    // Check if this client already exists (by email/contact)
    $check_sql = $con->query("SELECT id, client_id FROM partner_clients
                              WHERE partner_id = '$partner_id'
                              AND (email = '".mysqli_real_escape_string($con, $client_data['email'])."'
                              OR contact = '".mysqli_real_escape_string($con, $client_data['contact'])."')");
   
    if($check_sql && $check_sql->num_rows > 0) {
        // Client exists, use existing client_id
        $existing = $check_sql->fetch_assoc();
        $client_id = $existing['client_id'];
       
        // Update existing client information if needed
        $con->query("UPDATE partner_clients SET
                                      full_name = '".mysqli_real_escape_string($con, $client_data['name'])."',
                                      contact = '".mysqli_real_escape_string($con, $client_data['contact'])."',
                                      email = '".mysqli_real_escape_string($con, $client_data['email'])."',
                                      aadhar_upload = '".mysqli_real_escape_string($con, $client_data['aadhar'])."',
                                      pan_upload = '".mysqli_real_escape_string($con, $client_data['pan'])."',
                                      email_verified = '".intval($client_data['email_verified'])."'
                                      WHERE id = '".$existing['id']."'");
    } else {
        // Generate new client_id in format: PARTNERID_CLIENTID
        // Get the next client number for this partner
        $next_id_sql = $con->query("SELECT COUNT(*) as count FROM partner_clients WHERE partner_id = '$partner_id'");
        $count_row = $next_id_sql->fetch_assoc();
        $next_client_num = $count_row['count'] + 1;
        $client_id = $partner_id . '_' . $next_client_num;
       
        // Insert new client
        $insert_client = $con->query("INSERT INTO partner_clients
            (partner_id, client_id, full_name, contact, email, aadhar_upload, pan_upload, email_verified)
            VALUES (
                '$partner_id',
                '$client_id',
                '".mysqli_real_escape_string($con, $client_data['name'])."',
                '".mysqli_real_escape_string($con, $client_data['contact'])."',
                '".mysqli_real_escape_string($con, $client_data['email'])."',
                '".mysqli_real_escape_string($con, $client_data['aadhar'])."',
                '".mysqli_real_escape_string($con, $client_data['pan'])."',
                '".intval($client_data['email_verified'])."'
            )");
       
        if(!$insert_client) {
            echo json_encode(['success' => false, 'error' => 'Failed to save client: ' . $con->error]);
            exit;
        }
    }
   
    // Add to cart
    $insert_cart = $con->query("INSERT INTO partner_cart
        (partner_id, client_id, service_id, unit_price, service_fields_data)
        VALUES (
            '$partner_id',
            '$client_id',
            '$service_id',
            '$unit_price',
            '".mysqli_real_escape_string($con, $service_fields_data)."'
        )");
   
    if($insert_cart) {
        // Get updated cart count
        $cart_count_sql = $con->query("SELECT COUNT(*) as count FROM partner_cart WHERE partner_id = '$partner_id'");
        $cart_count = $cart_count_sql->fetch_assoc()['count'];
       
        echo json_encode([
            'success' => true,
            'message' => 'Added to cart successfully',
            'cart_count' => $cart_count,
            'client_id' => $client_id
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add to cart: ' . $con->error]);
    }
    exit;
}
// Get cart items handler
if(isset($_GET['action']) && $_GET['action'] == 'get_cart') {
    header('Content-Type: application/json');
   
    $partner_id = intval($_GET['partner_id']);
   
    $cart_sql = $con->query("SELECT pc.*, s.title as service_name, pcl.full_name as client_name
                              FROM partner_cart pc
                              JOIN service s ON pc.service_id = s.id
                              JOIN partner_clients pcl ON pc.client_id = pcl.client_id AND pc.partner_id = pcl.partner_id
                              WHERE pc.partner_id = '$partner_id'
                              ORDER BY pc.created_at DESC");
   
    $cart_items = [];
    while($item = $cart_sql->fetch_assoc()) {
        $cart_items[] = $item;
    }
   
    echo json_encode(['success' => true, 'items' => $cart_items, 'count' => count($cart_items)]);
    exit;
}
// Clear cart handler
if(isset($_POST['action']) && $_POST['action'] == 'clear_cart') {
    header('Content-Type: application/json');
   
    $partner_id = intval($_POST['partner_id']);
   
    if($partner_id > 0) {
        $delete_sql = $con->query("DELETE FROM partner_cart WHERE partner_id = '$partner_id'");
       
        if($delete_sql) {
            echo json_encode(['success' => true, 'message' => 'Cart cleared successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $con->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid partner ID']);
    }
    exit;
}
// ============================================
// NOW THE REST OF YOUR PHP CODE
// ============================================
$sql = $con->query("select * from service where id='" . $_GET['id'] . "'");
$row = $sql->fetch_assoc();
$sql2 = $con->query("select * from cate where id='" . $row['cate'] . "'");
if ($row2 = $sql2->fetch_assoc()) {
    $cate_name = $row2['name'];
}
// Check login status
$is_customer_logged_in = !empty($_COOKIE['tax_customer_log']);
$is_partner_logged_in = !empty($_COOKIE['tax_partner_log']);
// Get customer details if logged in
$customer_name = '';
$customer_contact = '';
$customer_email = '';
$customer_aadhar = '';
$customer_pan = '';
$customer_email_verified = 0;
$customer_id = '';
if ($is_customer_logged_in) {
    $customer_id = $_COOKIE['tax_customer_log'];
    $customer_sql = $con->query("SELECT * FROM customer WHERE id='$customer_id'");
    if ($customer_row = $customer_sql->fetch_assoc()) {
        $customer_name = $customer_row['name'] ?? '';
        $customer_contact = $customer_row['contact'] ?? '';
        $customer_email = $customer_row['email'] ?? '';
        $customer_aadhar = $customer_row['aadhar_upload'] ?? '';
        $customer_pan = $customer_row['pan_upload'] ?? '';
        $customer_email_verified = $customer_row['email_verified'] ?? 0;
    }
}
// Get partner details if logged in
$partner_id = null;
$partner_name = null;
$partner_contact = '';
$partner_email = '';
if ($is_partner_logged_in) {
    $partner_id = $_COOKIE['tax_partner_log'];
    $partner_sql = $con->query("SELECT * FROM partner WHERE id='$partner_id'");
    if ($partner_row = $partner_sql->fetch_assoc()) {
        $partner_name = $partner_row['name'];
        $partner_contact = $partner_row['contact'] ?? '';
        $partner_email = $partner_row['email'] ?? '';
    }
}
if (isset($_POST['review_submit'])) {
    $sql = $con->query("select * from reviews where sid='" . $_POST['sid'] . "' and cid='" . $_COOKIE["tax_customer_log"] . "'");
    if ($sql->fetch_assoc()) {
        echo "<script>alert('You already submit your review.');window.location='service-details.php?id=" . $_POST['sid'] . "';</script>";
    } else {
        if ($con->query("insert into reviews(sid,cid,star,content) values('" . $_POST['sid'] . "','" . $_COOKIE['tax_customer_log'] . "','" . $_POST['rate'] . "','" . $_POST['content'] . "')") === true) {
            echo "<script>alert('Thanks for submit your review');window.location='service-details.php?id=" . $_POST['sid'] . "';</script>";
        } else {
            echo "<script>alert('Server Error!!');window.location='service-details.php?id=" . $_POST['sid'] . "';</script>";
        }
    }
}
$num_of_review = 0;
$total_star = 0;
$av_rating = 0;
$query = $con->query("select * from reviews where sid='" . $_GET['id'] . "'");
while ($result = $query->fetch_assoc()) {
    $total_star += $result['star'];
    $num_of_review++;
}
if ($num_of_review == 0) {
    $av_rating = 0;
} else {
    $av_rating = $total_star / $num_of_review;
}
// Get service pricing (regular price for partners too - discount logic removed)
$o_price = floatval($row['o_price']);
// Dummy CA name for display
$dummy_ca_name = "CA Ramesh Kumar";
// Get existing partner clients for dropdown
$partner_clients = [];
if ($is_partner_logged_in) {
    $clients_sql = $con->query("SELECT * FROM partner_clients WHERE partner_id = '$partner_id' ORDER BY id DESC");
    while ($client_row = $clients_sql->fetch_assoc()) {
        $partner_clients[] = $client_row;
    }
}
?>
<!doctype html>
<html lang="en-gb" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Legal Taxation - <?php echo htmlspecialchars($cate_name ?? 'Category') ?> - <?php echo htmlspecialchars($row['title'] ?? 'Service Details'); ?></title>
    <link rel="shortcut icon" href="images/favicon.webp" type="image/webp" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries – consistent versions -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="css/slick.css" rel="stylesheet">
    <link href="css/slick-theme.css" rel="stylesheet">

    <!-- Razorpay -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

   <style>
        :root {
            --primary-color:   #667eea;
            --secondary-color: #764ba2;
            --accent-color:    #f6851f;
            --dark-color:      #2d3748;
            --light-color:     #f8f9fa;
            --text-color:      #4a5568;
            --gold:            #ffd166;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            line-height: 1.6;
            background: #fff;
        }

        /* ───────────────────────────────────────────────
           HEADER – exact match to index.php & fixed cart.php
        ─────────────────────────────────────────────── */
        .top_head {
            background: linear-gradient(135deg, #1e2b4f 0%, #2a3b6e 100%) !important;
            padding: 10px 0 !important;
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        }

        .cont_area a {
            color: #ffffff !important;
            font-size: 14px !important;
            margin-right: 25px !important;
            text-decoration: none !important;
        }

        .cont_area a:hover {
            color: var(--gold) !important;
        }

        .cart-item {
            position: relative;
            margin-right: 15px !important;
        }

        .cart-icon-link {
            position: relative;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 40px !important;
            height: 40px !important;
            background: rgba(255,255,255,0.15) !important;
            border-radius: 50% !important;
            color: #ffffff !important;
            text-decoration: none !important;
            transition: all 0.3s ease !important;
        }

        .cart-icon-link:hover {
            background: var(--gold) !important;
            color: #1e2b4f !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2) !important;
        }

        .cart-count {
            position: absolute !important;
            top: -5px !important;
            right: -5px !important;
            background: #dc3545 !important;
            color: white !important;
            border-radius: 50% !important;
            padding: 2px 6px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            min-width: 20px !important;
            height: 20px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 2px 5px rgba(220,53,69,0.3) !important;
            border: 2px solid #1e2b4f !important;
        }

        .user-menu .user-link {
            display: flex !important;
            align-items: center !important;
            color: #ffffff !important;
            padding: 8px 20px !important;
            background: #0d6efd !important;
            border-radius: 50px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
            border: none !important;
        }

        .user-menu .user-link:hover {
            background: #0b5ed7 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2) !important;
        }

        .user-menu .user-link i {
            margin-right: 8px !important;
            font-size: 18px !important;
        }

        .user-name {
            font-weight: 600 !important;
            max-width: 140px !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }

        [class*="fa-"], .fas, .far, .fab,
        i.fas, i.far, i.fab, .fa-solid, .fa-regular, .fa-brands {
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900 !important;
        }

        .head_nav {
            background: white !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08) !important;
        }

        .navbar-brand {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
        }

        .navbar-nav .nav-link {
            font-size: 15px !important;
            font-weight: 500 !important;
            color: #333 !important;
            padding: 8px 18px !important;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
            background: rgba(30,43,79,0.05) !important;
            border-radius: 6px !important;
        }

        @media (max-width: 991px) {
            .top_head { font-size: 13px !important; }
            .cont_area a { font-size: 13px !important; margin-right: 15px !important; }
        }

        /* ───────────────────────────────────────────────
           YOUR ORIGINAL PAGE STYLES – fully preserved
        ─────────────────────────────────────────────── */
        .oth_head {
            font-size: 14pt;
            color: #004678 !important;
        }
        .oth_des {
            font-size: 10pt;
        }
        .other_serv_box .card:hover {
            transition: 0.3s;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
            z-index: 1;
            position: relative;
        }

        /* Step indicator styles */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e0e0e0;
            z-index: 1;
        }
        .step-item {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }
        .step-number {
            width: 40px;
            height: 40px;
            background: #fff;
            border: 2px solid #e0e0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .step-item.active .step-number {
            background: #2575fc;
            border-color: #2575fc;
            color: white;
        }
        .step-item.completed .step-number {
            background: #28a745;
            border-color: #28a745;
            color: white;
        }
        .step-label {
            font-size: 14px;
            color: #666;
        }
        .step-item.active .step-label {
            color: #2575fc;
            font-weight: 600;
        }
        .step-item.completed .step-label {
            color: #28a745;
        }

        /* Multi-step form styling */
        .step-container {
            min-height: 400px;
            display: none;
        }
        .step-container.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .verified-badge {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .upload-preview {
            max-width: 100px;
            max-height: 100px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
        }

        .document-status {
            font-size: 12px;
            margin-top: 5px;
        }
        .document-status.uploaded { color: #28a745; }
        .document-status.not-uploaded { color: #ffc107; }

        .info-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .info-label {
            width: 150px;
            font-weight: 600;
            color: #555;
        }

        .info-value {
            flex: 1;
            color: #333;
        }

        .summary-box {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .summary-amount {
            font-size: 32px;
            font-weight: 700;
            color: #2575fc;
            margin: 10px 0;
        }

        .cart-badge {
            position: relative;
            display: inline-block;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }

        /* Modal styles */
        .modal-content {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            padding: 10px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        .modal-form-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        .modal-header {
            border-bottom: none;
        }
        .modal-title {
            font-size: 20px;
            font-weight: 600;
        }
        .modal-form-container label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .modal-form-container .form-control, 
        .modal-form-container .form-select {
            background: #f8f8f8;
            border: 2px solid #ddd;
            border-radius: 8px;
            color: #333;
            padding: 8px 12px;
            font-size: 13px;
            transition: all 0.3s ease;
        }
        .modal-form-container .form-control:focus, 
        .modal-form-container .form-select:focus {
            border-color: #2575fc;
            box-shadow: 0 0 8px rgba(37, 117, 252, 0.7);
        }
        .modal-form-container .btn-primary {
            background-color: #2575fc;
            border-color: #2575fc;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }
        .modal-form-container .btn-primary:hover {
            background-color: #1a62d6;
        }
        .modal-form-container .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .modal-form-container .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        .modal-form-container .payment-logo {
            max-width: 120px;
            display: block;
            margin: 15px 0;
        }
        .modal-form-container h6 {
            font-size: 18px;
            margin-bottom: 12px;
            color: #333;
            font-weight: 500;
        }
        .modal-form-container .para {
            font-size: 14px;
            color: #777;
            line-height: 1.5;
        }
        .modal-form-container .otp_box input {
            color: #000;
        }
        .modal-form-container .form-group {
            margin-bottom: 25px;
        }
        .modal-form-container .col-lg-6 {
            padding-left: 0;
            padding-right: 0;
        }
        .modal-form-container .input-group {
            margin-top: 8px;
        }
        .modal-dialog {
            max-width: 800px;
        }

        .existing-client-option {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: all 0.3s;
        }
        .existing-client-option:hover {
            background: #f0f0f0;
            border-color: #2575fc;
        }
        .existing-client-option.selected {
            background: #e3f2fd;
            border-color: #2575fc;
        }

        /* Renamed to avoid any possible header conflict */
        .service-cart-item {
            background: #f8f9fa;
            border-left: 3px solid #2575fc;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .cart-item-actions {
            display: flex;
            gap: 10px;
        }

        /* Toastr customization */
        .toast-success { background-color: #28a745 !important; }
        .toast-error   { background-color: #dc3545 !important; }
        .toast-info    { background-color: #17a2b8 !important; }
        .toast-warning { background-color: #ffc107 !important; }
    </style>
</head>
<body>
    <?php include("includes/header.php"); ?>
    <section class="income_area service_details_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb_area">
                        <ul>
                            <li><a href="index">Legal Taxation</a></li>
                            <li>/</li>
                            <li><?php echo $cate_name ?></li>
                            <li>/</li>
                            <li><b><?php echo $row['title'] ?></b></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-7 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="serv_doc_area">
                                        <img src="images/<?php echo $row['image'] ?>" class="w-100 mb-3">
                                        <p class="fw-bold">Document Required</p>
                                        <?php
                                        $d = explode(",", $row['doc_req']);
                                        foreach ($d as $doc) {
                                            $sql2 = $con->query("select * from doc where id='$doc'");
                                            if ($row2 = $sql2->fetch_assoc()) {
                                                echo '<p>' . $row2['name'] . '</p>';
                                            }
                                        ?>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <p class="head"><?php echo $row['title'] ?></p>
                                    <p><span class="badge"><i class="fa fa-star"></i> <?php echo $av_rating ?> Ratings</span> <a href="#cus_review"><?php if ($num_of_review == 0) {
                                                                                                                                                        echo 'No Reviews';
                                                                                                                                                    } else {
                                                                                                                                                        echo $num_of_review . " Customer's Reviews";
                                                                                                                                                    } ?> </a></p>
                                    <hr>
                                    <div class="serv_price_box">
                                        <ul>
                                            <p>Pricing Summary:- </p>
                                            <li>Market Price: <del>₹<?php echo $row['m_price'] ?></del></li>
                                            <li>Price: ₹<?php echo $o_price ?></li>
                                            <li>You Save: <span>₹<?php echo $row['m_price'] - $o_price ?></span></li>
                                        </ul>
                                    </div>
                                    <div class="serv_details_para">
                                        <p><?php echo $row['s_des'] ?></p>
                                        <?php
                                        if ($is_customer_logged_in || $is_partner_logged_in) {
                                            // Determine which ID to use
                                            $user_id = $is_customer_logged_in ? $_COOKIE['tax_customer_log'] : $_COOKIE['tax_partner_log'];
                                            $user_type = $is_customer_logged_in ? 'customer' : 'partner';
                                        ?>
                                            <button class="btn btn-primary apply_btn"
                                                data-sid='<?= $row['id'] ?>'
                                                data-uid='<?= $user_id ?>'
                                                data-utype='<?= $user_type ?>'
                                                data-o-price='<?= $o_price ?>'
                                                data-service-name='<?= $row['title'] ?>'>
                                                Apply Now <i class="fa fa-external-link"></i>
                                            </button>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="btn_area">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Apply Now <i class="fa-solid fa-arrow-right"></i></a>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                    <div class="serv_desc">
                                        <?php echo $row['des'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 mb-3">
                    <div class="service_right">
                        <p class="head">Other's Services</p>
                        <hr>
                        <div class="row">
                            <?php
                            $sql2 = $con->query("select * from service where cate='" . $row['cate'] . "' and id!='" . $_GET['id'] . "' order by id desc limit 6");
                            while ($row2 = $sql2->fetch_assoc()) {
                            ?>
                                <div class="col-lg-12 col-sm-12">
                                    <a href="service-details.php?id=<?php echo $row2['id'] ?>" class="other_serv_box">
                                        <div class="card">
                                            <div class="card_img">
                                                <img src="./images/<?php echo $row2['image'] ?>">
                                            </div>
                                            <div class="card-body">
                                                <p class="oth_head"><?php echo $row2['title'] ?></p>
                                                <p class="oth_des"><?php echo substr($row2['s_des'], 0, 50) ?>...</p>
                                                <p class="text-dark">Market Price: <del>₹<?php echo $row2['m_price'] ?></del></p>
                                                <p class="text-dark">Offer Price: ₹<?php echo $row2['o_price'] ?></p>
                                                <p class="text-dark">You Save: <span>₹<?php echo $row2['o_price'] ?></span></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12"><span id="cus_review"></span></div>
                <div class="col-md-12 mb-3">
                    <p class="h4 fw-bold text-center mb-3">Customer's Review</p>
                    <div class="row">
                        <?php
                        $r_count = 0;
                        $review_sql = $con->query("select * from reviews where sid='" . $_GET['id'] . "'");
                        while ($review_row = $review_sql->fetch_assoc()) {
                            $sql2 = $con->query("select * from customer where id='" . $review_row['cid'] . "'");
                            $row2 = $sql2->fetch_assoc();
                        ?>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="fw-bold"><?php echo $row2['name'] ?></h4>
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $review_row['star']) {
                                                $gold = "text-warning";
                                            } else {
                                                $gold = "";
                                            }
                                            echo "<small><i class='fa fa-star " . $gold . "'></i></small>";
                                        }
                                        ?>
                                        <p class="mt-2"><i class="fa fa-quote-left"></i> <?php echo $review_row['content'] ?> <i class="fa fa-quote-right"></i></p>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $r_count++;
                        }
                        if ($r_count == 0) {
                            echo "<h5 class='text-grey text-center fw-bold'>No Review Found !!</h5>";
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-12 <?php if (!@$_COOKIE['tax_customer_log']) {
                                            echo 'd-none';
                                        } ?>">
                    <p class="h4 fw-bold text-center mb-3">Write a Review for Us</p>
                    <form method="POST" class="mb-3">
                        <div class="rate">
                            <input type="radio" id="star5" name="rate" value="5" />
                            <label for="star5" title="text">5 stars</label>
                            <input type="radio" id="star4" name="rate" value="4" />
                            <label for="star4" title="text">4 stars</label>
                            <input type="radio" id="star3" name="rate" value="3" />
                            <label for="star3" title="text">3 stars</label>
                            <input type="radio" id="star2" name="rate" value="2" />
                            <label for="star2" title="text">2 stars</label>
                            <input type="radio" id="star1" name="rate" value="1" />
                            <label for="star1" title="text">1 star</label>
                        </div>
                        <textarea name="content" class="form-control mb-3" rows="5" placeholder="Write Review" required></textarea>
                        <input type="hidden" name="sid" value="<?php echo $_GET['id'] ?>">
                        <button class="btn btn-primary" name="review_submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
   <!-- Applications Modals -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header alert alert-danger text-white">
                <h5 class="modal-title text-dark fw-bold" id="exampleModalLabel">
                    Please Login First.
                    <a href="user-login.php?redirect=<?php echo urlencode('service-details.php?id=' . $_GET['id']); ?>&apply=1">Click Here</a> to Login
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>
    <!-- Customer 3-Step Application Modal -->
    <div class="modal fade" id="customerApplyModal" tabindex="-1" aria-labelledby="customerApplyModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerApplyModalLabel" style="color: #fff;">Application Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetCustomerModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-form-container">
                       
                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step-item active" id="step1-indicator">
                                <div class="step-number">1</div>
                                <div class="step-label">Personal Info & Documents</div>
                            </div>
                            <div class="step-item" id="step2-indicator">
                                <div class="step-number">2</div>
                                <div class="step-label">Service Details</div>
                            </div>
                            <div class="step-item" id="step3-indicator">
                                <div class="step-number">3</div>
                                <div class="step-label">Payment</div>
                            </div>
                        </div>
                       
                        <form method="post" enctype="multipart/form-data" id="customerApplicationForm">
                           
                            <!-- STEP 1: Personal Info & Documents -->
                            <div id="step1" class="step-container active">
                                <h5 class="mb-4">Service: <span class="text-primary" id="step1ServiceName"><?php echo $row['title'] ?></span></h5>
                               
                                <div class="info-card">
                                    <h6 class="mb-3"><i class="fas fa-user-circle me-2"></i>Personal Information</h6>
                                   
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label>Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="customer_full_name" name="full_name"
                                                   value="<?php echo htmlspecialchars($customer_name); ?>"
                                                   placeholder="Enter your full name" required>
                                        </div>
                                       
                                        <div class="col-md-6 mb-3">
                                            <label>Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="customer_contact" name="contact"
                                                   value="<?php echo htmlspecialchars($customer_contact); ?>"
                                                   placeholder="Enter your mobile number" required maxlength="10" pattern="[0-9]{10}">
                                        </div>
                                    </div>
                                   
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label>Email Address <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="email" class="form-control" id="customer_email" name="email"
                                                       value="<?php echo htmlspecialchars($customer_email); ?>"
                                                       placeholder="Enter your email address" required>
                                                <button class="btn btn-outline-secondary" type="button" id="send_otp_btn" <?php echo ($customer_email_verified == 1) ? 'disabled' : ''; ?>>
                                                    <?php if($customer_email_verified == 1): ?>
                                                        <i class="fas fa-check-circle text-success"></i> Verified
                                                    <?php else: ?>
                                                        Send OTP
                                                    <?php endif; ?>
                                                </button>
                                            </div>
                                            <small class="text-muted">We'll send a verification code to this email</small>
                                        </div>
                                    </div>
                                   
                                    <div class="row otp-section" id="otp-section" style="<?php echo ($customer_email_verified == 1) ? 'display: none;' : ''; ?>">
                                        <div class="col-md-12 mb-3">
                                            <label>OTP Verification <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="otp_input" placeholder="Enter OTP">
                                                <button class="btn btn-outline-success" type="button" id="verify_otp_btn">Verify OTP</button>
                                            </div>
                                            <div id="otp_status" class="mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="info-card mt-3">
                                    <h6 class="mb-3"><i class="fas fa-id-card me-2"></i>Document Upload</h6>
                                   
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label>Aadhar Card <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="aadhar_upload" name="aadhar_upload" accept=".jpg,.jpeg,.png,.pdf">
                                            <?php if(!empty($customer_aadhar)): ?>
                                                <div class="document-status uploaded">
                                                    <i class="fas fa-check-circle"></i> Aadhar already uploaded
                                                    <input type="hidden" name="aadhar_existing" value="<?php echo $customer_aadhar; ?>">
                                                </div>
                                            <?php else: ?>
                                                <small class="text-muted">Upload clear image of your Aadhar card</small>
                                            <?php endif; ?>
                                        </div>
                                       
                                        <div class="col-md-6 mb-3">
                                            <label>PAN Card <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="pan_upload" name="pan_upload" accept=".jpg,.jpeg,.png,.pdf">
                                            <?php if(!empty($customer_pan)): ?>
                                                <div class="document-status uploaded">
                                                    <i class="fas fa-check-circle"></i> PAN already uploaded
                                                    <input type="hidden" name="pan_existing" value="<?php echo $customer_pan; ?>">
                                                </div>
                                            <?php else: ?>
                                                <small class="text-muted">Upload clear image of your PAN card</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="text-end mt-4">
                                    <button type="button" class="btn btn-primary" id="step1NextBtn" onclick="validateStep1AndProceed()">
                                        Next <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                           
                            <!-- STEP 2: Service Details (Dynamic Fields) -->
                            <div id="step2" class="step-container">
                                <h5 class="mb-4">Service: <span class="text-primary" id="step2ServiceName"><?php echo $row['title'] ?></span></h5>
                               
                                <div class="info-card">
                                    <h6 class="mb-3"><i class="fas fa-file-alt me-2"></i>Service Information</h6>
                                   
                                    <!-- Dynamic fields will be loaded here -->
                                    <div id="serviceSpecificFields">
                                        <div class="text-center text-muted">
                                            <i class="fa fa-spinner fa-spin"></i> Loading application form...
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" onclick="goToStep(1)">
                                        <i class="fas fa-arrow-left me-2"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary" id="step2NextBtn" onclick="validateStep2AndProceed()">
                                        Next <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                           
                            <!-- STEP 3: Payment -->
                            <div id="step3" class="step-container">
                                <h5 class="mb-4">Service: <span class="text-primary" id="step3ServiceName"><?php echo $row['title'] ?></span></h5>
                               
                                <div class="info-card">
                                    <h6 class="mb-3"><i class="fas fa-credit-card me-2"></i>Payment Summary</h6>
                                   
                                    <div class="summary-box">
                                        <p class="mb-2">Verified by CA:</p>
                                        <h5 class="text-primary"><?php echo $dummy_ca_name; ?></h5>
                                       
                                        <div class="info-row mt-3">
                                            <div class="info-label">Client ID:</div>
                                            <div class="info-value" id="summaryClientId"><?php echo $customer_id; ?></div>
                                        </div>
                                       
                                        <div class="info-row">
                                            <div class="info-label">Client Name:</div>
                                            <div class="info-value" id="summaryClientName"><?php echo $customer_name; ?></div>
                                        </div>
                                       
                                        <div class="info-row">
                                            <div class="info-label">Assigned CA:</div>
                                            <div class="info-value"><span class="badge bg-success">Approved</span> <?php echo $dummy_ca_name; ?></div>
                                        </div>
                                       
                                        <hr>
                                       
                                        <div class="info-row">
                                            <div class="info-label">Service Amount:</div>
                                            <div class="info-value summary-amount" id="summaryAmount">₹<?php echo $o_price; ?></div>
                                        </div>
                                    </div>
                                   
                                    <div class="mt-4">
                                        <h6 class="text-secondary">Payment Method</h6>
                                        <img src="https://razorpay.com/assets/razorpay-glyph.svg"
                                            alt="Razorpay"
                                            class="payment-logo"
                                            style="width: 120px; height: auto;">
                                        <p class="my-3 para">Pay securely by Credit/Debit card or Internet Banking through Razorpay</p>
                                    </div>
                                </div>
                               
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" onclick="goToStep(2)">
                                        <i class="fas fa-arrow-left me-2"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-success" id="finalPayBtn" onclick="processFinalPayment()">
                                        <i class="fas fa-lock me-2"></i> Pay ₹<span id="payButtonAmount"><?php echo $o_price; ?></span>
                                    </button>
                                </div>
                            </div>
                           
                            <!-- Hidden Fields -->
                            <input type="hidden" name="sid" id="hiddenSid" value="">
                            <input type="hidden" name="cid" id="hiddenCid" value="<?php echo $customer_id; ?>">
                            <input type="hidden" name="user_type" id="hiddenUserType" value="customer">
                            <input type="hidden" name="payAmount" id="hiddenPayAmount" value="<?php echo $o_price; ?>">
                            <input type="hidden" name="email_verified" id="emailVerified" value="<?php echo $customer_email_verified; ?>">
                            <input type="hidden" name="service_fields_json" id="serviceFieldsJson" value="">
                           
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Partner 3-Step Application Modal (for Partner's Clients) -->
    <div class="modal fade" id="partnerApplyModal" tabindex="-1" aria-labelledby="partnerApplyModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="partnerApplyModalLabel">
                        <i class="fas fa-user-tie me-2"></i>Partner - Client Application
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="resetPartnerModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-form-container">
                       
                       
                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step-item active" id="partner-step1-indicator">
                                <div class="step-number">1</div>
                                <div class="step-label">Client Details</div>
                            </div>
                            <div class="step-item" id="partner-step2-indicator">
                                <div class="step-number">2</div>
                                <div class="step-label">Service Details</div>
                            </div>
                            <div class="step-item" id="partner-step3-indicator">
                                <div class="step-number">3</div>
                                <div class="step-label">Review & Add to Cart</div>
                            </div>
                        </div>
                       
                        <form method="post" enctype="multipart/form-data" id="partnerApplicationForm">
                           
                            <!-- STEP 1: Client Details (Partner's Client) -->
                            <div id="partnerStep1" class="step-container active">
                                <h5 class="mb-4">Service: <span class="text-primary" id="partnerStep1ServiceName"><?php echo $row['title'] ?></span></h5>
                               
                                <div class="mb-3">
                                    <label>Select Existing Client or Add New</label>
                                    <div class="mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="client_selection" id="newClientRadio" value="new" checked>
                                            <label class="form-check-label" for="newClientRadio">Add New Client</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="client_selection" id="existingClientRadio" value="existing">
                                            <label class="form-check-label" for="existingClientRadio">Select Existing Client</label>
                                        </div>
                                    </div>
                                </div>
                               
                                <!-- Existing Clients List (Hidden by default) -->
                                <div id="existingClientsList" style="display: none;" class="mb-4">
                                    <h6 class="mb-3">Your Clients</h6>
                                    <?php if(count($partner_clients) > 0): ?>
                                        <?php foreach($partner_clients as $client): ?>
                                        <div class="existing-client-option" onclick="selectExistingClient(<?php echo htmlspecialchars(json_encode($client)); ?>)">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong><?php echo $client['full_name']; ?></strong><br>
                                                    <small><?php echo $client['contact']; ?> | <?php echo $client['email']; ?></small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-info">ID: <?php echo $client['client_id']; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted">No existing clients found. Add a new client.</p>
                                    <?php endif; ?>
                                </div>
                               
                                <!-- New Client Form -->
                                <div id="newClientForm">
                                    <div class="info-card">
                                        <h6 class="mb-3"><i class="fas fa-user-circle me-2"></i>Client Personal Information</h6>
                                       
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Full Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="partner_client_full_name" placeholder="Enter client's full name" required>
                                            </div>
                                           
                                            <div class="col-md-6 mb-3">
                                                <label>Contact Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="partner_client_contact" placeholder="Enter client's mobile number" required maxlength="10" pattern="[0-9]{10}">
                                            </div>
                                        </div>
                                       
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Email Address <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="email" class="form-control" id="partner_client_email" placeholder="Enter client's email address" required>
                                                    <button class="btn btn-outline-secondary" type="button" id="partner_send_otp_btn">Send OTP</button>
                                                </div>
                                                <small class="text-muted">We'll send a verification code to this email</small>
                                            </div>
                                        </div>
                                       
                                        <div class="row otp-section" id="partner_otp_section" style="display: none;">
                                            <div class="col-md-12 mb-3">
                                                <label>OTP Verification <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="partner_otp_input" placeholder="Enter OTP">
                                                    <button class="btn btn-outline-success" type="button" id="partner_verify_otp_btn">Verify OTP</button>
                                                </div>
                                                <div id="partner_otp_status" class="mt-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="info-card mt-3">
                                        <h6 class="mb-3"><i class="fas fa-id-card me-2"></i>Client Document Upload</h6>
                                       
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Aadhar Card <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="partner_client_aadhar" accept=".jpg,.jpeg,.png,.pdf">
                                                <small class="text-muted">Upload clear image of client's Aadhar card</small>
                                            </div>
                                           
                                            <div class="col-md-6 mb-3">
                                                <label>PAN Card <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="partner_client_pan" accept=".jpg,.jpeg,.png,.pdf">
                                                <small class="text-muted">Upload clear image of client's PAN card</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="text-end mt-4">
                                    <button type="button" class="btn btn-primary" id="partnerStep1NextBtn" onclick="validatePartnerStep1AndProceed()">
                                        Next <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                           
                            <!-- STEP 2: Service Details (Dynamic Fields) -->
                            <div id="partnerStep2" class="step-container">
                                <h5 class="mb-4">Service: <span class="text-primary" id="partnerStep2ServiceName"><?php echo $row['title'] ?></span></h5>
                               
                                <div class="info-card">
                                    <h6 class="mb-3"><i class="fas fa-file-alt me-2"></i>Service Information for Client</h6>
                                   
                                    <!-- Dynamic fields will be loaded here -->
                                    <div id="partnerServiceSpecificFields">
                                        <div class="text-center text-muted">
                                            <i class="fa fa-spinner fa-spin"></i> Loading application form...
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" onclick="partnerGoToStep(1)">
                                        <i class="fas fa-arrow-left me-2"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary" id="partnerStep2NextBtn" onclick="validatePartnerStep2AndProceed()">
                                        Next <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                           
                            <!-- STEP 3: Review & Add to Cart -->
                            <div id="partnerStep3" class="step-container">
                                <h5 class="mb-4">Service: <span class="text-primary" id="partnerStep3ServiceName"><?php echo $row['title'] ?></span></h5>
                               
                                <div class="info-card">
                                    <h6 class="mb-3"><i class="fas fa-check-circle me-2"></i>Review Client Application</h6>
                                   
                                    <div class="summary-box">
                                        <p class="mb-2">Verified by CA:</p>
                                        <h5 class="text-primary"><?php echo $dummy_ca_name; ?></h5>
                                       
                                        <div class="info-row mt-3">
                                            <div class="info-label">Client Name:</div>
                                            <div class="info-value" id="partnerSummaryClientName"></div>
                                        </div>
                                       
                                        <div class="info-row">
                                            <div class="info-label">Client Contact:</div>
                                            <div class="info-value" id="partnerSummaryClientContact"></div>
                                        </div>
                                       
                                        <div class="info-row">
                                            <div class="info-label">Client Email:</div>
                                            <div class="info-value" id="partnerSummaryClientEmail"></div>
                                        </div>
                                       
                                        <div class="info-row">
                                            <div class="info-label">Assigned CA:</div>
                                            <div class="info-value"><span class="badge bg-success">Approved</span> <?php echo $dummy_ca_name; ?></div>
                                        </div>
                                       
                                        <hr>
                                       
                                        <div class="info-row">
                                            <div class="info-label">Service Amount:</div>
                                            <div class="info-value summary-amount" id="partnerSummaryAmount">₹<?php echo $o_price; ?></div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" onclick="partnerGoToStep(2)">
                                        <i class="fas fa-arrow-left me-2"></i> Previous
                                    </button>
                                    <div>
                                        <button type="button" class="btn btn-warning me-2" onclick="addToCartAndContinue()">
                                            <i class="fas fa-cart-plus me-2"></i> Add to Cart & Add Another Client
                                        </button>
                                        <button type="button" class="btn btn-success" onclick="addToCartAndFinish()">
                                            <i class="fas fa-check-circle me-2"></i> Add to Cart & Finish
                                        </button>
                                    </div>
                                </div>
                            </div>
                           
                            <!-- Hidden Fields -->
                            <input type="hidden" name="partner_id" id="hiddenPartnerId" value="<?php echo $partner_id; ?>">
                            <input type="hidden" name="sid" id="partnerHiddenSid" value="">
                            <input type="hidden" name="service_price" id="hiddenServicePrice" value="<?php echo $o_price; ?>">
                            <input type="hidden" name="client_id" id="hiddenClientId" value="">
                            <input type="hidden" name="client_email_verified" id="clientEmailVerified" value="0">
                            <input type="hidden" name="partner_service_fields_json" id="partnerServiceFieldsJson" value="">
                           
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("includes/footer.php"); ?>
   
    <script>
        // Configure toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };
   
        // Global variables
        var customerRegularPrice = <?php echo $o_price; ?>;
        var customerIsOtpVerified = <?php echo $customer_email_verified; ?>;
        var customerDummyOtp = "123456";
        var customerId = "<?php echo $customer_id; ?>";
        var serviceId = 0;
        var serviceName = "";
        var formFields = [];
        var collectedFormData = {};
        // Partner variables
        var partnerServiceId = 0;
        var partnerUserId = "<?php echo $partner_id; ?>";
        var partnerRegularPrice = <?php echo $o_price; ?>;
        var partnerFormFields = [];
        var partnerCollectedFormData = {};
        var partnerClientData = {};
        var partnerIsOtpVerified = false;
        var partnerDummyOtp = "123456";
        var selectedExistingClient = null;
        // ================================================
        // CUSTOMER 3-STEP FUNCTIONS
        // ================================================
        $(document).ready(function() {
            // Customer Apply button click handler
            $('.apply_btn').click(function() {
                var sid = $(this).data("sid");
                var uid = $(this).data("uid");
                var utype = $(this).data("utype");
                var regularPrice = parseFloat($(this).data("o-price")) || 0;
                var sname = $(this).data("service-name") || "";
                var isPartner = (utype === 'partner');
                console.log('Apply button clicked:', {
                    sid: sid,
                    uid: uid,
                    utype: utype,
                    isPartner: isPartner
                });
                if (isPartner) {
                    // Partner login - show partner 3-step modal
                    partnerServiceId = sid;
                    serviceName = sname;
                   
                    // Set service name in all steps
                    $('#partnerStep1ServiceName, #partnerStep2ServiceName, #partnerStep3ServiceName').text(serviceName);
                   
                    // Set hidden fields
                    $('#partnerHiddenSid').val(sid);
                   
                    // Reset to step 1
                    resetPartnerModal();
                   
                    // Load service-specific fields for step 2
                    $('#partnerServiceSpecificFields').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading form fields...</div>');
                    loadPartnerServiceFormFields(sid);
                   
                    // Load cart items
                    loadCartItems();
                   
                    // Force hide any other modals first
                    $('.modal').modal('hide');
                   
                    // Show partner modal
                    $('#partnerApplyModal').modal('show');
                } else {
                    // Customer login - show customer 3-step modal
                    serviceId = sid;
                    serviceName = sname;
                   
                    // Set service name in all steps
                    $('#step1ServiceName, #step2ServiceName, #step3ServiceName').text(serviceName);
                   
                    // Set hidden fields
                    $('#hiddenSid').val(sid);
                   
                    // Reset to step 1
                    resetCustomerModal();
                   
                    // Load service-specific fields for step 2
                    $('#serviceSpecificFields').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading form fields...</div>');
                    loadServiceFormFields(sid);
                   
                    // Force hide any other modals first
                    $('.modal').modal('hide');
                   
                    // Show customer modal
                    $('#customerApplyModal').modal('show');
                }
            });
           
            // Customer OTP Handlers
            $('#send_otp_btn').click(function() {
                var email = $('#customer_email').val();
                if (email == "") {
                    toastr.warning("Please enter an email address");
                    return;
                }
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    toastr.warning("Please enter a valid email address");
                    return;
                }
                $(this).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
                $('#customer_email').attr("readonly", true);
                setTimeout(() => {
                    $('#send_otp_btn').html('<i class="fa fa-check"></i> OTP Sent');
                    $('#send_otp_btn').prop('disabled', true);
                    $('#otp_input').val(customerDummyOtp);
                    $('#otp-section').show();
                    toastr.success('OTP sent to ' + email + ' (Demo OTP: 123456)');
                }, 1000);
            });
           
            $('#verify_otp_btn').click(function() {
                var enteredOtp = $('#otp_input').val();
                if (enteredOtp == customerDummyOtp) {
                    customerIsOtpVerified = true;
                    $('#emailVerified').val('1');
                    $(this).html('<i class="fa fa-check"></i> Verified');
                    $(this).removeClass('btn-outline-success').addClass('btn-success');
                    $(this).prop('disabled', true);
                    $('#otp_input').attr('readonly', true);
                    $('#otp_status').html('<span class="text-success"><i class="fas fa-check-circle"></i> Email verified successfully!</span>');
                    toastr.success('Email verified successfully!');
                   
                    // Update customer email in database via AJAX
                    updateCustomerEmail();
                } else {
                    toastr.error("Invalid OTP. Please try again.");
                    $('#otp_input').val('');
                }
            });
           
            // Partner OTP Handlers
            $('#partner_send_otp_btn').click(function() {
                var email = $('#partner_client_email').val();
                if (email == "") {
                    toastr.warning("Please enter client's email address");
                    return;
                }
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    toastr.warning("Please enter a valid email address");
                    return;
                }
                $(this).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
                $('#partner_client_email').attr("readonly", true);
                setTimeout(() => {
                    $('#partner_send_otp_btn').html('<i class="fa fa-check"></i> OTP Sent');
                    $('#partner_send_otp_btn').prop('disabled', true);
                    $('#partner_otp_input').val(partnerDummyOtp);
                    $('#partner_otp_section').show();
                    toastr.success('OTP sent to ' + email + ' (Demo OTP: 123456)');
                }, 1000);
            });
           
            $('#partner_verify_otp_btn').click(function() {
                var enteredOtp = $('#partner_otp_input').val();
                if (enteredOtp == partnerDummyOtp) {
                    partnerIsOtpVerified = true;
                    $('#clientEmailVerified').val('1');
                    $(this).html('<i class="fa fa-check"></i> Verified');
                    $(this).removeClass('btn-outline-success').addClass('btn-success');
                    $(this).prop('disabled', true);
                    $('#partner_otp_input').attr('readonly', true);
                    $('#partner_otp_status').html('<span class="text-success"><i class="fas fa-check-circle"></i> Email verified successfully!</span>');
                    toastr.success('Client email verified successfully!');
                } else {
                    toastr.error("Invalid OTP. Please try again.");
                    $('#partner_otp_input').val('');
                }
            });
           
            // Client selection radio buttons
            $('input[name="client_selection"]').change(function() {
                if ($(this).val() === 'existing') {
                    $('#existingClientsList').show();
                    $('#newClientForm').hide();
                } else {
                    $('#existingClientsList').hide();
                    $('#newClientForm').show();
                }
            });
           
            // File upload handlers for partner
            $('#partner_client_aadhar').change(function() {
                if (this.files && this.files[0]) {
                    uploadPartnerDocument('aadhar', this.files[0]);
                }
            });
           
            $('#partner_client_pan').change(function() {
                if (this.files && this.files[0]) {
                    uploadPartnerDocument('pan', this.files[0]);
                }
            });
        });
       
        function uploadPartnerDocument(type, file) {
            var formData = new FormData();
            formData.append('upload_document', true);
            formData.append('document_type', type);
            formData.append('customer_id', partnerUserId);
            formData.append('is_partner_client', '1');
            formData.append('document_file', file);
           
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Store file path in partnerClientData
                        if (!partnerClientData.documents) partnerClientData.documents = {};
                        partnerClientData.documents[type] = response.file_path;
                        toastr.success(type.charAt(0).toUpperCase() + type.slice(1) + ' uploaded successfully!');
                    } else {
                        toastr.error('Upload failed: ' + (response.error || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Upload error:', error);
                    toastr.error('Network error during upload');
                }
            });
        }
       
function loadCartItems() {
    console.log('Loading cart items for partner:', partnerUserId);
   
    $.ajax({
        url: window.location.href,
        type: 'GET',
        data: {
            action: 'get_cart',
            partner_id: partnerUserId,
            timestamp: new Date().getTime() // Add timestamp to prevent caching
        },
        dataType: 'json',
        cache: false, // Prevent caching
        success: function(response) {
            console.log('Cart loaded:', response);
            if (response.success) {
                updateCartDisplay(response.items, response.count);
            } else {
                console.error('Error loading cart:', response.error);
                // If error, still try to update with empty cart
                updateCartDisplay([], 0);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading cart items:', error);
            console.error('Response:', xhr.responseText);
            // On error, show empty cart
            updateCartDisplay([], 0);
        }
    });
}
       
function updateCartDisplay(items, count) {
    console.log('Updating cart display with count:', count, 'items:', items);
   
    // Update the cart count badge (page and header)
    $('#cartCount').text(count || 0);
    $('#headerCartCount').text(count || 0);
   
    // Clear and rebuild the cart items container
    const $container = $('#cartItemsContainer');
   
    if (!items || items.length === 0) {
        $container.html('<p class="text-muted text-center">Your cart is empty</p>');
    } else {
        let html = '';
        items.forEach(function(item) {
            html += `
                <div class="cart-item" id="cart-item-${item.id}">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>${escapeHtml(item.client_name)}</strong><br>
                            <small>Service: ${escapeHtml(item.service_name)}</small><br>
                            <small>Amount: ₹${item.unit_price}</small>
                        </div>
                        <div class="cart-item-actions">
                            <button class="btn btn-sm btn-danger" onclick="removeFromCart(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        $container.html(html);
    }
}
// Helper function to escape HTML and prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}
       
function removeFromCart(cartId) {
    if (!confirm('Remove this item from cart?')) return;
   
    // Show loading state on the button
    var $btn = $(`#cart-item-${cartId} .btn-danger`);
    var originalText = $btn.html();
    $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
   
    $.ajax({
        url: 'remove_from_cart.php',
        type: 'POST',
        data: {
            cart_id: cartId,
            partner_id: partnerUserId
        },
        dataType: 'json',
        success: function(response) {
            console.log('Remove from cart response:', response);
                if (response.success) {
                // Update cart count (page and header)
                $('#cartCount').text(response.cart_count || 0);
                $('#headerCartCount').text(response.cart_count || 0);
               
                // Remove the item from UI immediately for better UX
                $(`#cart-item-${cartId}`).fadeOut(300, function() {
                    $(this).remove();
                   
                    // Check if cart is now empty
                    if ($('#cartItemsContainer .cart-item').length === 0) {
                        $('#cartItemsContainer').html('<p class="text-muted text-center">Your cart is empty</p>');
                    }
                });
               
                // Also reload cart items to ensure sync
                setTimeout(loadCartItems, 500);
               
                toastr.success('Item removed from cart');
            } else {
                toastr.error('Error: ' + (response.error || 'Failed to remove item'));
                // Restore button
                $btn.html(originalText).prop('disabled', false);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error removing from cart:', error);
            console.error('Response:', xhr.responseText);
            toastr.error('Network error. Please try again.');
            // Restore button
            $btn.html(originalText).prop('disabled', false);
        }
    });
}
       
function clearCart() {
    if (!confirm('Clear all items from cart?')) return;
   
    // Show loading state
    var $clearBtn = $('button[onclick="clearCart()"]');
    var originalText = $clearBtn.html();
    $clearBtn.html('<i class="fas fa-spinner fa-spin"></i> Clearing...').prop('disabled', true);
   
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            action: 'clear_cart',
            partner_id: partnerUserId
        },
        dataType: 'json',
        success: function(response) {
            console.log('Clear cart response:', response);
                if (response.success) {
                // Update cart count to 0 (page and header)
                $('#cartCount').text(0);
                $('#headerCartCount').text(0);
               
                // Clear the cart items container immediately
                $('#cartItemsContainer').html('<p class="text-muted text-center">Your cart is empty</p>');
               
                // Optionally collapse the cart view
                $('#cartItemsList').collapse('hide');
               
                toastr.success('Cart cleared successfully');
            } else {
                toastr.error('Error: ' + (response.error || 'Failed to clear cart'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Error clearing cart:', error);
            console.error('Response:', xhr.responseText);
            toastr.error('Network error. Please try again.');
        },
        complete: function() {
            // Restore button
            $clearBtn.html(originalText).prop('disabled', false);
        }
    });
}
       
        function selectExistingClient(client) {
            selectedExistingClient = client;
            $('.existing-client-option').removeClass('selected');
            event.currentTarget.classList.add('selected');
           
            // Pre-fill data for later use
            partnerClientData = {
                id: client.client_id,
                name: client.full_name,
                contact: client.contact,
                email: client.email,
                aadhar: client.aadhar_upload,
                pan: client.pan_upload,
                email_verified: client.email_verified,
                isExisting: true
            };
           
            // Enable next button
            $('#partnerStep1NextBtn').prop('disabled', false);
            toastr.info('Selected client: ' + client.full_name);
        }
       
        function loadServiceFormFields(serviceId) {
            $.ajax({
                url: 'get_service_fields.php',
                type: 'GET',
                data: {
                    service_id: serviceId
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Service fields response:', response);
                    if (response.success && response.fields.length > 0) {
                        formFields = response.fields;
                        displayServiceFields(formFields);
                    } else {
                        formFields = [];
                        $('#serviceSpecificFields').html('<div class="alert alert-info">No additional information required for this service.</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading service fields:', status, error);
                    $('#serviceSpecificFields').html('<p class="text-danger">Error loading form. Please try again.</p>');
                }
            });
        }
       
        function loadPartnerServiceFormFields(serviceId) {
            $.ajax({
                url: 'get_service_fields.php',
                type: 'GET',
                data: {
                    service_id: serviceId
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Partner service fields response:', response);
                    if (response.success && response.fields.length > 0) {
                        partnerFormFields = response.fields;
                        displayPartnerServiceFields(partnerFormFields);
                    } else {
                        partnerFormFields = [];
                        $('#partnerServiceSpecificFields').html('<div class="alert alert-info">No additional information required for this service.</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading partner service fields:', error);
                    partnerFormFields = [];
                    $('#partnerServiceSpecificFields').html('<p class="text-danger">Error loading form. Please try again.</p>');
                }
            });
        }
       
        function displayServiceFields(fields) {
            let html = '<div class="row">';
            fields.forEach(function(field) {
                const fieldId = 'service_field_' + field.id;
                const required = field.is_required ? 'required' : '';
                const requiredStar = field.is_required ? ' <span class="text-danger">*</span>' : '';
                html += '<div class="col-md-12 mb-3">';
                html += '<label for="' + fieldId + '">' + field.field_label + requiredStar + '</label>';
                switch (field.field_type) {
                    case 'text':
                    case 'number':
                    case 'email':
                    case 'date':
                        html += '<input type="' + field.field_type + '" ' +
                            'id="' + fieldId + '" ' +
                            'name="service_fields[' + field.id + ']" ' +
                            'class="form-control service-field" ' +
                            'placeholder="' + (field.field_placeholder || '') + '" ' +
                            required + '>';
                        break;
                    case 'textarea':
                        html += '<textarea ' +
                            'id="' + fieldId + '" ' +
                            'name="service_fields[' + field.id + ']" ' +
                            'class="form-control service-field" ' +
                            'rows="3" ' +
                            'placeholder="' + (field.field_placeholder || '') + '" ' +
                            required + '></textarea>';
                        break;
                    case 'select':
                        html += '<select ' +
                            'id="' + fieldId + '" ' +
                            'name="service_fields[' + field.id + ']" ' +
                            'class="form-control service-field" ' +
                            required + '>';
                        html += '<option value="">' + (field.field_placeholder || 'Select an option') + '</option>';
                        if (field.field_options) {
                            const options = field.field_options.split(',');
                            options.forEach(function(option) {
                                html += '<option value="' + option.trim() + '">' + option.trim() + '</option>';
                            });
                        }
                        html += '</select>';
                        break;
                    case 'radio':
                        html += '<div class="radio-group">';
                        if (field.field_options) {
                            const options = field.field_options.split(',');
                            options.forEach(function(option, index) {
                                const optionId = fieldId + '_' + index;
                                html += '<div class="form-check form-check-inline">';
                                html += '<input type="radio" ' +
                                    'id="' + optionId + '" ' +
                                    'name="service_fields[' + field.id + ']" ' +
                                    'value="' + option.trim() + '" ' +
                                    'class="form-check-input service-field" ' +
                                    required + '>';
                                html += '<label class="form-check-label" for="' + optionId + '">' + option.trim() + '</label>';
                                html += '</div>';
                            });
                        }
                        html += '</div>';
                        break;
                }
                if (field.help_text) {
                    html += '<small class="form-text text-muted">' + field.help_text + '</small>';
                }
                html += '</div>';
            });
            html += '</div>';
            $('#serviceSpecificFields').html(html);
        }
       
        function displayPartnerServiceFields(fields) {
            let html = '<div class="row">';
            fields.forEach(function(field) {
                const fieldId = 'partner_service_field_' + field.id;
                const required = field.is_required ? 'required' : '';
                const requiredStar = field.is_required ? ' <span class="text-danger">*</span>' : '';
                html += '<div class="col-md-12 mb-3">';
                html += '<label for="' + fieldId + '">' + field.field_label + requiredStar + '</label>';
                switch (field.field_type) {
                    case 'text':
                    case 'number':
                    case 'email':
                    case 'date':
                        html += '<input type="' + field.field_type + '" ' +
                            'id="' + fieldId + '" ' +
                            'name="partner_service_fields[' + field.id + ']" ' +
                            'class="form-control partner-service-field" ' +
                            'placeholder="' + (field.field_placeholder || '') + '" ' +
                            required + '>';
                        break;
                    case 'textarea':
                        html += '<textarea ' +
                            'id="' + fieldId + '" ' +
                            'name="partner_service_fields[' + field.id + ']" ' +
                            'class="form-control partner-service-field" ' +
                            'rows="3" ' +
                            'placeholder="' + (field.field_placeholder || '') + '" ' +
                            required + '></textarea>';
                        break;
                    case 'select':
                        html += '<select ' +
                            'id="' + fieldId + '" ' +
                            'name="partner_service_fields[' + field.id + ']" ' +
                            'class="form-control partner-service-field" ' +
                            required + '>';
                        html += '<option value="">' + (field.field_placeholder || 'Select an option') + '</option>';
                        if (field.field_options) {
                            const options = field.field_options.split(',');
                            options.forEach(function(option) {
                                html += '<option value="' + option.trim() + '">' + option.trim() + '</option>';
                            });
                        }
                        html += '</select>';
                        break;
                    case 'radio':
                        html += '<div class="radio-group">';
                        if (field.field_options) {
                            const options = field.field_options.split(',');
                            options.forEach(function(option, index) {
                                const optionId = fieldId + '_' + index;
                                html += '<div class="form-check form-check-inline">';
                                html += '<input type="radio" ' +
                                    'id="' + optionId + '" ' +
                                    'name="partner_service_fields[' + field.id + ']" ' +
                                    'value="' + option.trim() + '" ' +
                                    'class="form-check-input partner-service-field" ' +
                                    required + '>';
                                html += '<label class="form-check-label" for="' + optionId + '">' + option.trim() + '</label>';
                                html += '</div>';
                            });
                        }
                        html += '</div>';
                        break;
                }
                if (field.help_text) {
                    html += '<small class="form-text text-muted">' + field.help_text + '</small>';
                }
                html += '</div>';
            });
            html += '</div>';
            $('#partnerServiceSpecificFields').html(html);
        }
       
        // Customer Step Functions
        function validateStep1AndProceed() {
            var name = $('#customer_full_name').val().trim();
            if (name === '') {
                toastr.warning('Please enter your full name');
                $('#customer_full_name').focus();
                return false;
            }
           
            var contact = $('#customer_contact').val().trim();
            var phonePattern = /^[0-9]{10}$/;
            if (!phonePattern.test(contact)) {
                toastr.warning('Please enter a valid 10-digit mobile number');
                $('#customer_contact').focus();
                return false;
            }
           
            var email = $('#customer_email').val().trim();
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                toastr.warning('Please enter a valid email address');
                $('#customer_email').focus();
                return false;
            }
           
            if (customerIsOtpVerified != 1) {
                toastr.warning('Please verify your email address with OTP first');
                return false;
            }
           
            var aadharFile = $('#aadhar_upload')[0].files[0];
            var aadharExisting = $('input[name="aadhar_existing"]').val();
            if (!aadharFile && !aadharExisting) {
                toastr.warning('Please upload your Aadhar card');
                return false;
            }
           
            var panFile = $('#pan_upload')[0].files[0];
            var panExisting = $('input[name="pan_existing"]').val();
            if (!panFile && !panExisting) {
                toastr.warning('Please upload your PAN card');
                return false;
            }
           
            $('#summaryClientName').text(name);
            goToStep(2);
        }
       
        function validateStep2AndProceed() {
            var isValid = true;
            var firstEmptyField = null;
           
            $('.service-field[required]').each(function() {
                if ($(this).is(':radio')) {
                    var name = $(this).attr('name');
                    if ($('input[name="' + name + '"]:checked').length === 0) {
                        isValid = false;
                        $(this).closest('.radio-group').addClass('is-invalid');
                        if (!firstEmptyField) firstEmptyField = $(this);
                    }
                } else if ($(this).is('select')) {
                    if ($(this).val() === '') {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        if (!firstEmptyField) firstEmptyField = $(this);
                    }
                } else {
                    if ($(this).val().trim() === '') {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        if (!firstEmptyField) firstEmptyField = $(this);
                    }
                }
            });
           
            if (!isValid) {
                toastr.warning("Please fill in all required fields marked with *");
                if (firstEmptyField) firstEmptyField.focus();
                return false;
            }
           
            collectedFormData = {};
            $('input[name^="service_fields"], select[name^="service_fields"], textarea[name^="service_fields"]').each(function() {
                var name = $(this).attr('name');
                var value = $(this).val();
                if ($(this).is(':radio')) {
                    if ($(this).is(':checked')) {
                        collectedFormData[name] = value;
                    }
                } else {
                    collectedFormData[name] = value;
                }
            });
           
            $('#serviceFieldsJson').val(JSON.stringify(collectedFormData));
            goToStep(3);
        }
       
        function goToStep(step) {
            $('.step-item').removeClass('active completed');
           
            for (let i = 1; i < step; i++) {
                $('#step' + i + '-indicator').addClass('completed');
            }
           
            $('#step' + step + '-indicator').addClass('active');
           
            $('.step-container').removeClass('active');
            $('#step' + step).addClass('active');
        }
       
        function processFinalPayment() {
            if (!customerIsOtpVerified) {
                toastr.warning('Please complete step 1 and verify your email');
                goToStep(1);
                return;
            }
           
            var formData = new FormData($('#customerApplicationForm')[0]);
            formData.append('paymentOption', 'netbanking');
            formData.append('action', 'payOrder');
            formData.append('service_fields_data', JSON.stringify(collectedFormData));
            formData.append('billing_name', $('#customer_full_name').val());
            formData.append('billing_mobile', $('#customer_contact').val());
            formData.append('billing_email', $('#customer_email').val());
            formData.append('payAmount', $('#hiddenPayAmount').val());
           
            $('#finalPayBtn').html('<i class="fa fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: 'submitpayment.php',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.res == 'success') {
                        var orderID = data.order_number;
                        var options = {
                            "key": data.razorpay_key,
                            "amount": data.userData.amount * 100,
                            "currency": "INR",
                            "name": "Legal Taxation",
                            "description": data.userData.description,
                            "image": "images/logo.webp",
                            "order_id": data.userData.rpay_order_id,
                            "handler": function(response) {
                                window.location.replace("payment-success.php?oid=" + orderID + "&rp_payment_id=" + response.razorpay_payment_id + "&rp_signature=" + response.razorpay_signature);
                            },
                            "prefill": {
                                "name": data.userData.name,
                                "email": data.userData.email,
                                "contact": data.userData.mobile
                            },
                            "notes": {
                                "address": "Legal Taxation"
                            },
                            "theme": {
                                "color": "#3399cc"
                            }
                        };
                        var rzp1 = new Razorpay(options);
                        rzp1.on('payment.failed', function(response) {
                            window.location.replace("payment-failed.php?oid=" + orderID + "&reason=" + response.error.description + "&paymentid=" + response.error.metadata.payment_id);
                        });
                        rzp1.open();
                    } else {
                        toastr.error('Error: ' + (data.message || 'Payment processing failed'));
                        $('#finalPayBtn').html('<i class="fas fa-lock me-2"></i> Pay ₹' + $('#hiddenPayAmount').val()).prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Payment error:', error);
                    toastr.error('Network error. Please try again.');
                    $('#finalPayBtn').html('<i class="fas fa-lock me-2"></i> Pay ₹' + $('#hiddenPayAmount').val()).prop('disabled', false);
                }
            });
        }
       
        function resetCustomerModal() {
            goToStep(1);
           
            <?php if($customer_email_verified != 1): ?>
            customerIsOtpVerified = 0;
            $('#emailVerified').val('0');
            $('#otp-section').hide();
            $('#otp_input').val('').attr('readonly', false);
            $('#send_otp_btn').html('Send OTP').prop('disabled', false);
            $('#verify_otp_btn').html('Verify OTP').removeClass('btn-success').addClass('btn-outline-success').prop('disabled', false);
            $('#customer_email').attr('readonly', false);
            $('#otp_status').html('');
            <?php endif; ?>
           
            $('.is-invalid').removeClass('is-invalid');
        }
        // ================================================
        // PARTNER FUNCTIONS
        // ================================================
        function partnerGoToStep(step) {
            $('.step-item').removeClass('active completed');
           
            for (let i = 1; i < step; i++) {
                $('#partner-step' + i + '-indicator').addClass('completed');
            }
           
            $('#partner-step' + step + '-indicator').addClass('active');
           
            $('.step-container').removeClass('active');
            $('#partnerStep' + step).addClass('active');
        }
       
        function validatePartnerStep1AndProceed() {
            var selection = $('input[name="client_selection"]:checked').val();
           
            if (selection === 'existing') {
                if (!selectedExistingClient) {
                    toastr.warning('Please select an existing client');
                    return false;
                }
               
                // Use existing client data
                partnerClientData = selectedExistingClient;
                partnerIsOtpVerified = (selectedExistingClient.email_verified == 1);
               
            } else {
                // Validate new client form
                var name = $('#partner_client_full_name').val().trim();
                if (name === '') {
                    toastr.warning('Please enter client\'s full name');
                    $('#partner_client_full_name').focus();
                    return false;
                }
               
                var contact = $('#partner_client_contact').val().trim();
                var phonePattern = /^[0-9]{10}$/;
                if (!phonePattern.test(contact)) {
                    toastr.warning('Please enter a valid 10-digit mobile number');
                    $('#partner_client_contact').focus();
                    return false;
                }
               
                var email = $('#partner_client_email').val().trim();
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    toastr.warning('Please enter a valid email address');
                    $('#partner_client_email').focus();
                    return false;
                }
               
                if (partnerIsOtpVerified != 1) {
                    toastr.warning('Please verify client\'s email address with OTP first');
                    return false;
                }
               
                var aadharFile = $('#partner_client_aadhar')[0].files[0];
                if (!aadharFile) {
                    toastr.warning('Please upload client\'s Aadhar card');
                    return false;
                }
               
                var panFile = $('#partner_client_pan')[0].files[0];
                if (!panFile) {
                    toastr.warning('Please upload client\'s PAN card');
                    return false;
                }
               
                // Store client data
                partnerClientData = {
                    name: name,
                    contact: contact,
                    email: email,
                    email_verified: 1,
                    aadhar: partnerClientData.documents?.aadhar || '',
                    pan: partnerClientData.documents?.pan || '',
                    isExisting: false
                };
            }
           
            // Update summary
            $('#partnerSummaryClientName').text(partnerClientData.name || partnerClientData.full_name);
            $('#partnerSummaryClientContact').text(partnerClientData.contact);
            $('#partnerSummaryClientEmail').text(partnerClientData.email);
           
            partnerGoToStep(2);
        }
       
        function validatePartnerStep2AndProceed() {
            var isValid = true;
            var firstEmptyField = null;
           
            $('.partner-service-field[required]').each(function() {
                if ($(this).is(':radio')) {
                    var name = $(this).attr('name');
                    if ($('input[name="' + name + '"]:checked').length === 0) {
                        isValid = false;
                        $(this).closest('.radio-group').addClass('is-invalid');
                        if (!firstEmptyField) firstEmptyField = $(this);
                    }
                } else if ($(this).is('select')) {
                    if ($(this).val() === '') {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        if (!firstEmptyField) firstEmptyField = $(this);
                    }
                } else {
                    if ($(this).val().trim() === '') {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        if (!firstEmptyField) firstEmptyField = $(this);
                    }
                }
            });
           
            if (!isValid) {
                toastr.warning("Please fill in all required fields marked with *");
                if (firstEmptyField) firstEmptyField.focus();
                return false;
            }
           
            partnerCollectedFormData = {};
            $('input[name^="partner_service_fields"], select[name^="partner_service_fields"], textarea[name^="partner_service_fields"]').each(function() {
                var name = $(this).attr('name');
                var value = $(this).val();
                if ($(this).is(':radio')) {
                    if ($(this).is(':checked')) {
                        partnerCollectedFormData[name] = value;
                    }
                } else {
                    partnerCollectedFormData[name] = value;
                }
            });
           
            $('#partnerServiceFieldsJson').val(JSON.stringify(partnerCollectedFormData));
            partnerGoToStep(3);
        }
       
        function addToCartAndContinue() {
            addToCart(function() {
                // Reset for next client
                resetPartnerModal();
                partnerGoToStep(1);
                toastr.success('Item added to cart! You can add another client.');
            });
        }
       
        function addToCartAndFinish() {
            addToCart(function() {
                // Close modal
                $('#partnerApplyModal').modal('hide');
                toastr.success('Items added to cart successfully!');
            });
        }
       
        function addToCart(callback) {
            // Prepare client data
            var clientData = {
                name: partnerClientData.name || partnerClientData.full_name,
                contact: partnerClientData.contact,
                email: partnerClientData.email,
                aadhar: partnerClientData.aadhar || partnerClientData.aadhar_upload || '',
                pan: partnerClientData.pan || partnerClientData.pan_upload || '',
                email_verified: partnerClientData.email_verified || 1
            };
           
            // Get service fields data properly
            var serviceFieldsData = {};
            $('input[name^="partner_service_fields"], select[name^="partner_service_fields"], textarea[name^="partner_service_fields"]').each(function() {
                var name = $(this).attr('name');
                var value = $(this).val();
               
                // Extract the field ID from the name (e.g., partner_service_fields[1] -> 1)
                var matches = name.match(/\[(\d+)\]/);
                if (matches && matches[1]) {
                    var fieldId = matches[1];
                   
                    if ($(this).is(':radio')) {
                        if ($(this).is(':checked')) {
                            serviceFieldsData[fieldId] = value;
                        }
                    } else if ($(this).is(':checkbox')) {
                        if ($(this).is(':checked')) {
                            if (!serviceFieldsData[fieldId]) serviceFieldsData[fieldId] = [];
                            serviceFieldsData[fieldId].push(value);
                        }
                    } else {
                        serviceFieldsData[fieldId] = value;
                    }
                }
            });
           
            console.log('Sending to cart:', {
                partner_id: partnerUserId,
                service_id: partnerServiceId,
                client_data: clientData,
                service_fields_data: JSON.stringify(serviceFieldsData),
                unit_price: $('#hiddenServicePrice').val()
            });
           
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    action: 'add_to_cart',
                    partner_id: partnerUserId,
                    service_id: partnerServiceId,
                    client_data: JSON.stringify(clientData),
                    service_fields_data: JSON.stringify(serviceFieldsData),
                    unit_price: $('#hiddenServicePrice').val()
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Add to cart response:', response);
                    if (response.success) {
                        // Update cart count (page and header)
                        $('#cartCount').text(response.cart_count);
                        $('#headerCartCount').text(response.cart_count);
                        // Reload cart items
                        loadCartItems();
                        // Execute callback
                        if (callback) callback();
                    } else {
                        toastr.error('Error: ' + (response.error || 'Failed to add to cart'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    console.error('Response:', xhr.responseText);
                    toastr.error('Network error. Please try again. Check console for details.');
                }
            });
        }
       
        function resetPartnerModal() {
            // Reset to step 1
            partnerGoToStep(1);
           
            // Reset form fields
            $('#partner_client_full_name').val('');
            $('#partner_client_contact').val('');
            $('#partner_client_email').val('');
            $('#partner_client_aadhar').val('');
            $('#partner_client_pan').val('');
           
            // Reset OTP
            partnerIsOtpVerified = false;
            $('#clientEmailVerified').val('0');
            $('#partner_otp_section').hide();
            $('#partner_otp_input').val('').attr('readonly', false);
            $('#partner_send_otp_btn').html('Send OTP').prop('disabled', false);
            $('#partner_verify_otp_btn').html('Verify OTP').removeClass('btn-success').addClass('btn-outline-success').prop('disabled', false);
            $('#partner_client_email').attr('readonly', false);
            $('#partner_otp_status').html('');
           
            // Reset client selection
            $('#newClientRadio').prop('checked', true);
            $('#existingClientsList').hide();
            $('#newClientForm').show();
            selectedExistingClient = null;
            $('.existing-client-option').removeClass('selected');
           
            // Reset collected data
            partnerClientData = {};
            partnerCollectedFormData = {};
           
            $('.is-invalid').removeClass('is-invalid');
        }
        function updateCustomerEmail() {
            var email = $('#customer_email').val();
            $.ajax({
                url: 'update_customer_email.php',
                type: 'POST',
                data: {
                    customer_id: customerId,
                    email: email,
                    verified: 1
                },
                success: function(response) {
                    console.log('Email updated in database');
                }
            });
        }
        // Check if we need to auto-open the application modal after login
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('apply') && urlParams.get('apply') === '1') {
                setTimeout(function() {
                    <?php if ($is_customer_logged_in || $is_partner_logged_in): ?>
                        $('.apply_btn').first().trigger('click');
                    <?php else: ?>
                        $('#exampleModal').modal('show');
                    <?php endif; ?>
                }, 500);
            }
        });
    </script>
</body>
</html>
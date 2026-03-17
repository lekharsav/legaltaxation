<?php
include('db.php');

// ============================================
// ALL AJAX HANDLERS MUST COME FIRST
// ============================================

// Remove from cart handler
if(isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    header('Content-Type: application/json');
    
    $cart_id = intval($_POST['cart_id']);
    $partner_id = intval($_POST['partner_id']);
    
    // Verify the item belongs to this partner
    $check_sql = $con->query("SELECT id FROM partner_cart WHERE id = '$cart_id' AND partner_id = '$partner_id'");
    
    if($check_sql && $check_sql->num_rows > 0) {
        $delete_sql = $con->query("DELETE FROM partner_cart WHERE id = '$cart_id' AND partner_id = '$partner_id'");
        
        if($delete_sql) {
            // Get updated cart count
            $count_sql = $con->query("SELECT COUNT(*) as count FROM partner_cart WHERE partner_id = '$partner_id'");
            $count_row = $count_sql->fetch_assoc();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Item removed successfully',
                'cart_count' => $count_row['count']
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $con->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Item not found or does not belong to you']);
    }
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
// NOW THE REST OF THE PAGE
// ============================================

// Check if partner is logged in
$is_partner_logged_in = !empty($_COOKIE['tax_partner_log']);
if (!$is_partner_logged_in) {
    header("Location: partner-login.php");
    exit();
}

$partner_id = $_COOKIE['tax_partner_log'];

// Get cart items
$cart_sql = $con->query("SELECT pc.*, s.title as service_name, s.o_price, pcl.full_name as client_name, pcl.contact, pcl.email
                          FROM partner_cart pc 
                          JOIN service s ON pc.service_id = s.id 
                          JOIN partner_clients pcl ON pc.client_id = pcl.client_id AND pc.partner_id = pcl.partner_id
                          WHERE pc.partner_id = '$partner_id' 
                          ORDER BY pc.created_at DESC");

$cart_items = [];
$total_amount = 0;
while($item = $cart_sql->fetch_assoc()) {
    $cart_items[] = $item;
    $total_amount += floatval($item['unit_price']);
}

// Get partner details
$partner_sql = $con->query("SELECT * FROM partner WHERE id='$partner_id'");
$partner = $partner_sql->fetch_assoc();

// Field names mapping
$field_names = [
    1 => 'Full Name (as per PAN)',
    2 => 'PAN Number',
    3 => 'Date of Birth'
];
?>
<!doctype html>
<html lang="en-gb" class="no-js">
<head>
    <title>My Cart - Legal Taxation Partner</title>
    <link rel="shortcut icon" href="images/favicon.webp" type="image/webp" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="css/slick.css" rel="stylesheet">
    <link href="css/slick-theme.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

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
            background: #f8f9fa;
        }

        /* HEADER STYLES */
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

        /* Cart Page Styles */
        .cart-section {
            padding: 50px 0;
            min-height: 70vh;
        }
        
        .cart-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }
        
        .cart-header {
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .cart-header h2 {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .cart-header h2 i {
            color: #2575fc;
            margin-right: 10px;
        }
        
        .cart-product-item {
            background: #f8f9fa;
            border-left: 4px solid #2575fc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .cart-product-item:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .cart-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .client-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        
        .client-name i {
            color: #2575fc;
            margin-right: 8px;
        }
        
        .item-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-remove {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-remove:hover {
            background: #c82333;
        }
        
        .btn-remove i {
            margin-right: 5px;
        }
        
        .cart-item-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        
        .detail-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 15px;
            font-weight: 500;
            color: #333;
        }
        
        .service-fields {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }
        
        .service-fields h6 {
            font-size: 14px;
            font-weight: 600;
            color: #555;
            margin-bottom: 10px;
        }
        
        .field-row {
            display: flex;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .field-name {
            width: 120px;
            color: #666;
        }
        
        .field-value {
            flex: 1;
            color: #333;
            font-weight: 500;
        }
        
        .cart-summary {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .summary-row.total {
            font-size: 20px;
            font-weight: 600;
            color: #2575fc;
            border-top: 2px solid #fff;
            padding-top: 15px;
            margin-top: 15px;
        }
        
        .cart-actions {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            margin-top: 30px;
        }
        
        .btn-clear {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-clear:hover {
            background: #5a6268;
        }
        
        .btn-checkout {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-checkout:hover {
            background: #218838;
        }
        
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-cart i {
            font-size: 80px;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .empty-cart h3 {
            font-size: 24px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .empty-cart p {
            color: #999;
            margin-bottom: 30px;
        }
        
        .btn-shop {
            background: #2575fc;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-shop:hover {
            background: #1a5dcf;
            color: white;
        }
        
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 20px;
        }
        
        .breadcrumb a {
            color: #2575fc;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        /* Checkout Modal Styles */
        .modal-checkout-content {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            padding: 10px;
            border-radius: 15px;
        }
        
        .modal-checkout-body {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
        }
        
        .checkout-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .checkout-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .checkout-item:last-child {
            border-bottom: none;
        }
        
        .checkout-total {
            font-size: 18px;
            font-weight: 600;
            color: #2575fc;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
        }
        
        .payment-logo {
            max-width: 120px;
            margin: 15px 0;
        }
    </style>
</head>
<body>

<?php include("includes/header.php"); ?>

<section class="cart-section">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="partner/dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Cart</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="cart-container">
                    <div class="cart-header">
                        <h2><i class="fas fa-shopping-cart"></i> My Cart (<?php echo count($cart_items); ?> items)</h2>
                    </div>
                    
                    <?php if(empty($cart_items)): ?>
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>Your cart is empty</h3>
                        <p>Start adding services for your clients to see them here.</p>
                        <a href="service.php" class="btn-shop">Browse Services</a>
                    </div>
                    <?php else: ?>
                    
                    <div id="cartItemsList">
                        <?php foreach($cart_items as $item): 
                            $service_fields = json_decode($item['service_fields_data'], true);
                        ?>
                        <div class="cart-product-item" id="cart-item-<?php echo $item['id']; ?>">
                            <div class="cart-item-header">
                                <div class="client-name">
                                    <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($item['client_name']); ?>
                                </div>
                                <div class="item-actions">
                                    <button class="btn-remove" onclick="removeFromCart(<?php echo $item['id']; ?>)">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                            
                            <div class="cart-item-details">
                                <div class="detail-item">
                                    <span class="detail-label">Client ID</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($item['client_id']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Contact</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($item['contact']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Email</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($item['email']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Service</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($item['service_name']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Amount</span>
                                    <span class="detail-value">₹<?php echo number_format(floatval($item['unit_price']), 2); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Added On</span>
                                    <span class="detail-value"><?php echo date('d M Y', strtotime($item['created_at'])); ?></span>
                                </div>
                            </div>
                            
                            <?php if(!empty($service_fields)): ?>
                            <div class="service-fields">
                                <h6>Service Details:</h6>
                                <div class="row">
                                    <?php foreach($service_fields as $field_id => $field_value): ?>
                                    <div class="col-md-4">
                                        <div class="field-row">
                                            <span class="field-name"><?php echo $field_names[$field_id] ?? 'Field '.$field_id; ?>:</span>
                                            <span class="field-value"><?php echo htmlspecialchars($field_value); ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="cart-summary">
                        <div class="summary-row">
                            <span>Subtotal (<?php echo count($cart_items); ?> items)</span>
                            <span>₹<?php echo number_format($total_amount, 2); ?></span>
                        </div>
                        <div class="summary-row total">
                            <span>Total Amount</span>
                            <span>₹<?php echo number_format($total_amount, 2); ?></span>
                        </div>
                    </div>
                    
                    <div class="cart-actions">
                        <button class="btn-clear" onclick="clearCart()">
                            <i class="fas fa-trash-alt"></i> Clear Cart
                        </button>
                        <button class="btn-checkout" onclick="showCheckoutModal()">
                            <i class="fas fa-credit-card"></i> Proceed to Checkout
                        </button>
                    </div>
                    
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-checkout-content">
            <div class="modal-checkout-body">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="checkout-summary">
                    <h6>Order Summary</h6>
                    <div id="checkoutItemsList"></div>
                    <div class="checkout-total d-flex justify-content-between">
                        <span>Total Amount:</span>
                        <span id="checkoutTotalAmount">₹0</span>
                    </div>
                </div>
                
                <form id="checkoutForm">
                    <input type="hidden" name="partner_id" id="checkoutPartnerId" value="<?php echo $partner_id; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Billing Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="billing_name" 
                                   value="<?php echo htmlspecialchars($partner['name'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label>Billing Mobile <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="billing_mobile" 
                                   value="<?php echo htmlspecialchars($partner['contact'] ?? ''); ?>" 
                                   required maxlength="10" pattern="[0-9]{10}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label>Billing Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="billing_email" 
                               value="<?php echo htmlspecialchars($partner['email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-secondary">Payment Method</h6>
                        <img src="https://razorpay.com/assets/razorpay-glyph.svg"
                             alt="Razorpay"
                             class="payment-logo">
                        <p class="text-muted small">Pay securely by Credit/Debit card or Internet Banking through Razorpay</p>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="payNowBtn" onclick="processPayment()">
                            <i class="fas fa-lock me-2"></i> Pay Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
// Configure toastr
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": 3000
};

// Global variables
var partnerUserId = <?php echo json_encode($partner_id); ?>;
var cartItems = <?php echo json_encode($cart_items); ?>;
var totalAmount = <?php echo $total_amount; ?>;

console.log('Cart items loaded:', cartItems);
console.log('Total amount:', totalAmount);

// Helper function to escape HTML
function escapeHtml(text) {
    if (!text) return '';
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}

function removeFromCart(cartId) {
    if (!confirm('Remove this item from cart?')) return;
    
    var $btn = $(`#cart-item-${cartId} .btn-remove`);
    var originalText = $btn.html();
    $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
    
    $.ajax({
        url: 'cart.php',
        type: 'POST',
        data: {
            action: 'remove_from_cart',
            cart_id: cartId,
            partner_id: partnerUserId
        },
        dataType: 'json',
        success: function(response) {
            console.log('Remove response:', response);
            if (response.success) {
                $(`#cart-item-${cartId}`).fadeOut(300, function() {
                    $(this).remove();
                    
                    // Update header cart count if element exists
                    if ($('#headerCartCount').length) {
                        $('#headerCartCount').text(response.cart_count);
                    }
                    
                    // Check if cart is empty
                    if ($('#cartItemsList .cart-product-item').length === 0) {
                        location.reload();
                    } else {
                        // Update total
                        updateCartTotal();
                        toastr.success('Item removed from cart');
                    }
                });
            } else {
                toastr.error('Error: ' + (response.error || 'Failed to remove item'));
                $btn.html(originalText).prop('disabled', false);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            console.error('Response:', xhr.responseText);
            toastr.error('Network error. Please try again.');
            $btn.html(originalText).prop('disabled', false);
        }
    });
}

function clearCart() {
    if (!confirm('Clear all items from cart?')) return;
    
    var $btn = $('.btn-clear');
    var originalText = $btn.html();
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Clearing...').prop('disabled', true);
    
    $.ajax({
        url: 'cart.php',
        type: 'POST',
        data: {
            action: 'clear_cart',
            partner_id: partnerUserId
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                toastr.success('Cart cleared successfully');
                location.reload();
            } else {
                toastr.error('Error: ' + (response.error || 'Failed to clear cart'));
                $btn.html(originalText).prop('disabled', false);
            }
        },
        error: function() {
            toastr.error('Network error. Please try again.');
            $btn.html(originalText).prop('disabled', false);
        }
    });
}

function updateCartTotal() {
    // Recalculate total from remaining items
    let newTotal = 0;
    $('.cart-product-item').each(function() {
        let amountText = $(this).find('.detail-item:contains("Amount") .detail-value').text();
        let amount = parseFloat(amountText.replace('₹', '').replace(/,/g, ''));
        if (!isNaN(amount)) newTotal += amount;
    });
    
    // Update summary
    $('.summary-row:first span:last').text('₹' + newTotal.toFixed(2));
    $('.summary-row.total span:last').text('₹' + newTotal.toFixed(2));
}

function showCheckoutModal() {
    console.log('Showing checkout modal with items:', cartItems);
    
    // Check if cart is empty
    if (!cartItems || cartItems.length === 0) {
        toastr.warning('Your cart is empty');
        return;
    }
    
    // Populate checkout modal with cart items
    let itemsHtml = '';
    let total = 0;
    
    cartItems.forEach(function(item) {
        // Safely convert price to number
        let price = 0;
        if (item.unit_price !== undefined && item.unit_price !== null) {
            price = parseFloat(item.unit_price);
            if (isNaN(price)) price = 0;
        }
        total += price;
        
        itemsHtml += `
            <div class="checkout-item">
                <span>${escapeHtml(item.client_name || 'Unknown Client')} - ${escapeHtml(item.service_name || 'Unknown Service')}</span>
                <span>₹${price.toFixed(2)}</span>
            </div>
        `;
    });
    
    $('#checkoutItemsList').html(itemsHtml);
    $('#checkoutTotalAmount').text('₹' + total.toFixed(2));
    
    // Show modal
    $('#checkoutModal').modal('show');
}

function processPayment() {
    // Validate form
    var name = $('#billing_name').val().trim();
    var mobile = $('#billing_mobile').val().trim();
    var email = $('#billing_email').val().trim();
    
    if (!name) {
        toastr.warning('Please enter billing name');
        return;
    }
    
    var phonePattern = /^[0-9]{10}$/;
    if (!phonePattern.test(mobile)) {
        toastr.warning('Please enter a valid 10-digit mobile number');
        return;
    }
    
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        toastr.warning('Please enter a valid email address');
        return;
    }
    
    // Disable button and show loading
    var $btn = $('#payNowBtn');
    var originalText = $btn.html();
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
    
    $.ajax({
        url: 'process_partner_payment.php',
        type: 'POST',
        data: {
            action: 'process_partner_payment',
            partner_id: partnerUserId,
            billing_name: name,
            billing_mobile: mobile,
            billing_email: email
        },
        dataType: 'json',
        success: function(response) {
            console.log('Payment response:', response);
            if (response.success) {
                var options = {
                    "key": response.razorpay_key,
                    "amount": response.userData.amount * 100,
                    "currency": "INR",
                    "name": "Legal Taxation",
                    "description": response.userData.description,
                    "image": "images/logo.webp",
                    "order_id": response.userData.rpay_order_id,
                    "handler": function(razorpayResponse) {
                        window.location.replace("payment-success.php?oid=" + response.order_number + 
                            "&rp_payment_id=" + razorpayResponse.razorpay_payment_id + 
                            "&rp_signature=" + razorpayResponse.razorpay_signature +
                            "&type=partner_cart");
                    },
                    "prefill": {
                        "name": response.userData.name,
                        "email": response.userData.email,
                        "contact": response.userData.mobile
                    },
                    "notes": {
                        "partner_id": partnerUserId,
                        "item_count": response.item_count
                    },
                    "theme": {
                        "color": "#3399cc"
                    }
                };
                
                var rzp = new Razorpay(options);
                rzp.on('payment.failed', function(response) {
                    window.location.replace("payment-failed.php?oid=" + response.order_number + 
                        "&reason=" + response.error.description + 
                        "&paymentid=" + response.error.metadata.payment_id);
                });
                rzp.open();
                
                // Close the checkout modal
                $('#checkoutModal').modal('hide');
                
            } else {
                toastr.error('Error: ' + (response.error || 'Payment processing failed'));
                $btn.html(originalText).prop('disabled', false);
            }
        },
        error: function(xhr, status, error) {
            console.error('Payment error:', error);
            console.error('Response:', xhr.responseText);
            toastr.error('Network error. Please try again.');
            $btn.html(originalText).prop('disabled', false);
        }
    });
}

// Auto-refresh cart data when page becomes visible
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        location.reload();
    }
});
</script>

</body>
</html>
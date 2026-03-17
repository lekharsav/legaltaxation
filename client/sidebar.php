<?php
// client/sidebar.php - Self-contained Customer Sidebar

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$customer_name = 'Customer';
$customer_id = 'N/A';
$customer_image = '';

// Check if cookie exists for customer login
if(isset($_COOKIE['tax_customer_log']) && !empty($_COOKIE['tax_customer_log'])){
    $customer_id = $_COOKIE['tax_customer_log'];
    
    // If customer variables are not already defined, fetch them
    if(!isset($customer_name)){
        // Try to include database connection
        $db_included = false;
        if(file_exists('../db.php')){
            include_once("../db.php");
            $db_included = true;
        } elseif(file_exists('../../db.php')){
            include_once("../../db.php");
            $db_included = true;
        }
        
        // If we have database connection, fetch customer data
        if($db_included && isset($con)){
            $sql = $con->query("SELECT * FROM customer WHERE id='$customer_id' AND status='1'");
            if($sql && $sql->num_rows > 0){
                $customer = $sql->fetch_assoc();
                $customer_name = $customer["name"];
                $customer_image = $customer["image"] ?? '';
            }
        }
    }
}

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">
        <img src="../images/logo.webp" alt="Legal Taxation Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Customer Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?php if(!empty($customer_image)): ?>
                    <img src="../uploads/customers/<?php echo $customer_image; ?>" 
                         class="img-circle elevation-2" alt="User Image" 
                         onerror="this.src='../assets/images/default-user.jpg'">
                <?php else: ?>
                    <div class="img-circle elevation-2 bg-primary d-flex align-items-center justify-content-center" 
                         style="width: 40px; height: 40px;">
                        <i class="fas fa-user text-white"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="info">
                <a href="my-profile.php" class="d-block"><?php echo htmlspecialchars($customer_name); ?></a>
                <small>Customer ID: #<?php echo str_pad($customer_id, 5, '0', STR_PAD_LEFT); ?></small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="my-profile.php" class="nav-link <?php echo $current_page == 'my-profile.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>My Profile</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="my-services.php" class="nav-link <?php echo $current_page == 'my-services.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>My Services</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="my-documents.php" class="nav-link <?php echo $current_page == 'my-documents.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <p>My Documents</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="my-payments.php" class="nav-link <?php echo $current_page == 'my-payments.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>Payments</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="support-tickets.php" class="nav-link <?php echo $current_page == 'support-tickets.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-headset"></i>
                        <p>Support</p>
                    </a>
                </li>

                <!-- Browse Services -->
                <li class="nav-item">
                    <a href="../service.php" class="nav-link" target="_blank">
                        <i class="nav-icon fas fa-plus-circle"></i>
                        <p>Browse Services</p>
                    </a>
                </li>
                
                <!-- Back to Website -->
                <li class="nav-item">
                    <a href="../index.php" class="nav-link" target="_blank">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Back to Website</p>
                    </a>
                </li>

                <!-- Change Password -->
                <li class="nav-item">
                    <a href="change-password.php" class="nav-link <?php echo $current_page == 'change-password.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Change Password</p>
                    </a>
                </li>
                
                <!-- Logout -->
                <li class="nav-item">
                    <a href="../logout.php?return=index" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>